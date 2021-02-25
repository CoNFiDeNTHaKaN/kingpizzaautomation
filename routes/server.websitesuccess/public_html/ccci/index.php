<?php

  // ini_set('display_errors', 1);
  // ini_set('display_startup_errors', 1);
  // error_reporting(E_ALL);

 require 'vendor/autoload.php';

  $goCardless = new \GoCardlessPro\Client([
      'access_token' => 'live_Edhr_b6G7R_zYJr3bepcakpyz8Y3fAemUWsxE9Sv',
      'environment' => \GoCardlessPro\Environment::LIVE,
  ]);
  $webhookReturn = "https://server.websitesuccess.co.uk/ccci/webhook";

  if (isset($_GET['dev'])) {
    if ($_GET['dev'] == 'true') {
      $goCardless = new \GoCardlessPro\Client([
          'access_token' => 'sandbox_LpoghlOm7rruxBsN2yrfuiPhT4Bg78SzI2nYUMsf',
          'environment' => \GoCardlessPro\Environment::SANDBOX,
      ]);
      $webhookReturn = "https://server.websitesuccess.co.uk/ccci/webhook?dev=true";
    }
  }

  $redirectDb = (new MongoDB\Client)->db->redirects;
  $completionRedirect = "https://www.chichestercci.org.uk/thank-you";

  function createRedirect()
  {

    global $goCardless, $redirectDb, $webhookReturn;
    //generate cookie hash and set
    $cookiehash = hash('md5', rand(1000000,9999999));
    setcookie('ccciSession', $cookiehash);

 $getCustomerXML = <<<XML
  <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
    <soap12:Body>
      <Contact_RetrieveByEmailAddress xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
        <username>tech@websitesuccess.co.uk</username>
        <password>DevTime69!</password>
        <siteId>56912</siteId>
        <emailAddress>{$_POST['email']}</emailAddress>
      </Contact_RetrieveByEmailAddress>
    </soap12:Body>
  </soap12:Envelope>
XML;

    $customer = efusionSoapy($getCustomerXML);

    $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $customer);
    // var_dump($customer);
    // var_dump($response);
    $xml = new SimpleXMLElement($response);
    $body = $xml->xpath('//soapBody')[0];
    $customer = json_decode(json_encode((array)$body), TRUE);
    $goCardlessFields = $customer['Contact_RetrieveByEmailAddressResponse']['Contact_RetrieveByEmailAddressResult']['crmForms']['CrmForms']['crmFormFields']['CrmFormFields'];
    $customerGoCardless = [];
    foreach ($goCardlessFields as $goCardlessField) {
      $customerGoCardless[ $goCardlessField['fieldName'] ] = $goCardlessField['fieldValue'];
    }

    $existingSubscriptionToUpdate = true;

    if (!empty($customerGoCardless['Customer']) &&
        !empty($customerGoCardless['Mandate']) &&
        !empty($customerGoCardless['Subscription'])) {

        try {
          $goCardlessSubscription = $goCardless->subscriptions()->get( $customerGoCardless['Subscription'] );
        } catch (\Exception $e) {
          $goCardlessSubscription = false;
        }
        try {
          $goCardlessMandate = $goCardless->mandates()->get( $customerGoCardless['Mandate'] );
        } catch (\Exception $e) {
          $goCardlessMandate = false;
        }

        $goCardlessMandateStatus = ($goCardlessMandate) ? $goCardlessMandate->status : false;

        // check mandate is still active and if so change subscriptions, otherwise proceed with redirect flow
        if ($goCardlessMandateStatus !== "failed" && $goCardlessMandateStatus !== "cancelled" && $goCardlessMandateStatus !== "expired") {
          $goCardless->subscriptions()->update($goCardlessSubscription->id,[
            "params" => [
              "metadata" => [
                "Cancellation" => "Cancelled by API due to customer membership change"
              ]
            ]
          ]);
          $goCardless->subscriptions()->cancel($goCardlessSubscription->id);



          // add direct debit to mandate
          $newSubscription = $goCardless->subscriptions()->create([
            "params" => [
              "amount" => $_POST['monthly-cost'],
              "currency" => "GBP",
              "name" => $_POST['membership-type'],
              "interval_unit" => "yearly",
              "links" => [
                "mandate" => $goCardlessMandate->id
              ]
            ]
           ]);


        } else {
          $existingSubscriptionToUpdate = false;
        }

    } else {
      $existingSubscriptionToUpdate = false;
    }

    if ($existingSubscriptionToUpdate) {
      // existing has been upgraded, proceed to update SZs
      updateCRM($_POST['email'], $_POST['secure-zone-id'], $_POST['remove-secure-zone-id'], $customerGoCardless['Mandate'], $newSubscription->id, $customerGoCardless['Customer']);
    } else {
      // otherwise get the redirect flow moving
      //generate redirect flow
      $redirect = $goCardless->redirectFlows()->create([
        "params" => ["description" => $_POST['membership-type'],
        "session_token" => $cookiehash,
        "success_redirect_url" => $webhookReturn,
        "prefilled_customer" => [
          "given_name" => $_POST['first-name'],
          "family_name" => $_POST['last-name'],
          "email" => $_POST['email']
        ]]
      ]);



      //store session/redirect info in db
      $redirectDbInsert = $redirectDb->insertOne([
        'redirect_id' => $redirect->id,
        'redirect_url' => $redirect->redirect_url,
        'session_id' => $cookiehash,
        'first_name' => $_POST['first-name'],
        'last_name' => $_POST['last-name'],
        'description' => $_POST['membership-type'],
        'secure-zone-id' => $_POST['secure-zone-id'],
        'remove-secure-zone-id' => $_POST['remove-secure-zone-id'],
        'monthly-cost' => $_POST['monthly-cost'],
        'email' => $_POST['email'],

      ]);

      header(('Location:'.$redirect->redirect_url));

    }


  }

  function catchWebhookAndUpdateSecureZone()
  {
      global $goCardless, $redirectDb;

      // catch redirect completion
      // check session id and retrieve database record
      $redirectComplete = $goCardless->redirectFlows()->complete($_GET['redirect_flow_id'], [
        "params" => [
          "session_token" => $_COOKIE['ccciSession']
        ]
      ]);

      $redirectInfo = $redirectDb->findOne([
        'redirect_id' => $redirectComplete->id
      ]);


      $dayofmonth = date('d');
      if ($dayofmonth <= 15) {
        $mandateGoCardless = $goCardless->mandates()->get($redirectComplete->links->mandate);
        $nextDateGC = $mandateGoCardless->next_possible_charge_date;
        $nextdate = date('Y-m-d', strtotime($nextDateGC));

      } else {
        $nextdate = date("Y-m-d", strtotime(date('m', strtotime('+1 month')).'/01/'.date('Y').' 00:00:00'));
      }

      // add direct debit to mandate
      // "start_date" => $nextdate,

      $subscription = $goCardless->subscriptions()->create([
        "params" => [
          "amount" => (int) $redirectInfo['monthly-cost'],
          "currency" => "GBP",
          "name" => $redirectInfo['description'],
          "interval_unit" => "yearly",
          "links" => [
            "mandate" => $redirectComplete->links->mandate
          ]
        ]
       ]);
       //
       // var_dump($subscription);
       // var_dump($redirectInfo);

       $email = $redirectInfo['email'];
       $adds = $redirectInfo['secure-zone-id'];
       $removes = $redirectInfo['remove-secure-zone-id'];
       $mandate =  $subscription->links->mandate;
       $subscription = $subscription->id;

       $mandateGoCardless = $goCardless->mandates()->get($mandate);
       $customer = $mandateGoCardless->links->customer;

       updateCRM($email, $adds, $removes, $mandate, $subscription, $customer);

  }

  function updateCRM($email, $adds, $removes, $mandate, $subscription, $customer) {
    global $completionRedirect;

    $retrieveSCXml = <<<XML
      <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
        <soap12:Body>
          <SecureZoneList_Retrieve xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
            <username>tech@websitesuccess.co.uk</username>
            <password>DevTime69!</password>
            <siteId>56912</siteId>
          </SecureZoneList_Retrieve>
        </soap12:Body>
      </soap12:Envelope>
XML;
    $retrieveSC = efusionSoapy($retrieveSCXml);

    $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $retrieveSC);
    $xml = new SimpleXMLElement($response);
    $body = $xml->xpath('//soapBody')[0];
    $siteSCsraw = json_decode(json_encode((array)$body), TRUE)['SecureZoneList_RetrieveResponse']['SecureZoneList_RetrieveResult']['SecureZone'];
    $siteSCs = [];
    foreach ($siteSCsraw as $siteSC) {
      // var_dump($siteSC);
      $siteSCs[ $siteSC['secureZoneID'] ] = $siteSC['secureZoneName'];
    }
    $unsubscribes = '';
    $subscribes = '';

    $removes = explode(',', $removes);
    $adds = explode(',', $adds);

    // for each remove add an unsubscribe chunk
    foreach ($removes as $remove) {
      if (!empty($remove)) {
        $removeId = $remove;
        $removeName = $siteSCs[ $remove ];
      $unsubscribe = <<<XML
       <SecureZone>
           <secureZoneID>{$removeId}</secureZoneID>
           <secureZoneName>{$removeName}</secureZoneName>
           <secureZoneExpiryDate>12/31/9999 11:59:59 PM</secureZoneExpiryDate>
           <secureZoneUnsubscribe>true</secureZoneUnsubscribe>
       </SecureZone>
XML;
        $unsubscribes .= $unsubscribe;
      }
    }

    // for each add add a chunk
    foreach ($adds as $add) {
      $addId = $add;
      $addName = $siteSCs[$add];
    $subscribe = <<<XML
     <SecureZone>
         <secureZoneID>{$addId}</secureZoneID>
         <secureZoneName>{$addName}</secureZoneName>
         <secureZoneExpiryDate>12/31/9999 11:59:59 PM</secureZoneExpiryDate>
         <secureZoneUnsubscribe>false</secureZoneUnsubscribe>
     </SecureZone>
XML;
      $subscribes .= $subscribe;
    }

  $secureZoneXML = <<<XML
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
<soap12:Body>
<Contact_SecureZoneListUpdateInsert xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
 <username>tech@websitesuccess.co.uk</username>
 <password>DevTime69!</password>
 <siteId>56912</siteId>
 <emailaddress>{$email}</emailaddress>
 <securezoneList>
 {$subscribes}
 {$unsubscribes}
 </securezoneList>
</Contact_SecureZoneListUpdateInsert>
</soap12:Body>
</soap12:Envelope>
XML;

 // add secure zones to user
  $secureZones = efusionSoapy($secureZoneXML);

  $retrieveCRM = <<<XML
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <Contact_RetrieveByEmailAddress xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
      <username>tech@websitesuccess.co.uk</username>
      <password>DevTime69!</password>
      <siteId>56912</siteId>
      <emailAddress>{$email}</emailAddress>
    </Contact_RetrieveByEmailAddress>
  </soap12:Body>
</soap12:Envelope>
XML;

  $retrieveCRMSOAP = efusionSoapy($retrieveCRM);
  $xml = simplexml_load_string($retrieveCRMSOAP);
  $CrmForms = $xml->children('soap', true)->Body->children()->children()->children()->crmForms->CrmForms;

  foreach ($CrmForms as $CrmForm) {
    if((string) $CrmForm->formName[0] === "GoCardless") {
      $crmFormFields = $CrmForm->crmFormFields->children();

      foreach ($crmFormFields as $crmFormField) {
        if ((string) $crmFormField->fieldName === "Customer") {
          $crmFormField->fieldValue = $customer;
        } else if ((string) $crmFormField->fieldName === "Mandate") {
          $crmFormField->fieldValue = $mandate;
        } else if ((string) $crmFormField->fieldName === "Subscription") {
          $crmFormField->fieldValue = $subscription;
        }
      }
    }
  }

  $customerXML = $xml->children('soap', true)->Body->children()->children();
  $customerXML->addChild('emailAddress', $email);
  $customerXML = str_replace('Contact_RetrieveByEmailAddressResult', 'ContactRecord', $customerXML->asXML());


  $customerUpdateXML = <<<XML
  <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <ContactList_UpdateInsert xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
      <username>tech@websitesuccess.co.uk</username>
      <password>DevTime69!</password>
      <siteId>56912</siteId>
      <contactsList>
        {$customerXML}
      </contactsList>
    </ContactList_UpdateInsert>
  </soap12:Body>
</soap12:Envelope>
XML;

  $customerUpdate = efusionSoapy($customerUpdateXML);
  // var_dump($customerUpdate);

  header(("Location:$completionRedirect?reg=true"));
  }

  function efusionSoapy($xmltosend) {
    $soap_listretrieve = "https://ccci.worldsecuresystems.com/catalystwebservice/catalystcrmwebservice.asmx";
    $curlobj = curl_init();
    $headers = array(
      "Content-type: application/soap+xml;charset=\"utf-8\"",
      "Accept: text/xml",
      "Content-length: ".strlen($xmltosend)
    );
    $curlopts = array(
      CURLOPT_URL => $soap_listretrieve,
      CURLOPT_POST => 1,
      CURLOPT_HEADER => 0,
      CURLOPT_HTTPHEADER => $headers,
      CURLOPT_POSTFIELDS => $xmltosend,
      CURLOPT_RETURNTRANSFER => TRUE
    );
    curl_setopt_array($curlobj, $curlopts);
    $curlexec = curl_exec($curlobj);
    $curlinfo = curl_getinfo($curlobj);
    if ($curlinfo['http_code'] != 200) {
      // var_dump($xmltosend);
      // var_dump($curlinfo);
      // var_dump($curlexec);
      // $error = simplexml_load_string($curlexec)->children('soap', true)->Body->Fault->Reason->Text;
      $date = date("l jS \of F H:i:s");
      $errorResponse = "
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
+
+   Error occured : {$curlinfo['http_code']} [{$date}]

+   {$xmltosend}
+
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

      ";
      // echo $errorResponse;
      return false;
    } else {
      return $curlexec;
    }
    curl_close($curlobj);
  }

  if ( isset($_SERVER['PATH_INFO']) ) {
    if (str_replace('/', '', $_SERVER['PATH_INFO']) == 'create-redirect') {
      call_user_func('createRedirect');
    } elseif (str_replace('/', '', $_SERVER['PATH_INFO']) == 'webhook') {
      call_user_func('catchWebhookAndUpdateSecureZone');
    } else {
      http_response_code(404);
    }
  } else {
    //
    // http_response_code(404);
    // $redirectDbAll = $redirectDb->find();
    //
    // foreach ($redirectDbAll as $document) {
    //   var_dump($document);
    // }
  }
