<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require "vendor/autoload.php";
require 'options.php';

  $globals = array(
      '$_SERVER' => $_SERVER, '$_ENV' => $_ENV,
      '$_REQUEST' => $_REQUEST, '$_GET' => $_GET,
      '$_POST' => $_POST, '$_COOKIE' => $_COOKIE,
      '$_FILES' => $_FILES,
      '$PHPINPUT' => file_get_contents("php://input")
  );
  $epoch = date('Ymd-His');
  $filename = "/requestlogs/$epoch-input.txt";
  $json = json_encode($globals);
  $view = isset($_GET['view']);
  $orderId = $_REQUEST['ObjectID'];
  function __autoload($className){
      $filePath = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
      $includePaths = explode(PATH_SEPARATOR, get_include_path());
      foreach($includePaths as $includePath){
          if(file_exists($includePath . DIRECTORY_SEPARATOR . $filePath)){
              require_once $filePath;
              return;
          }
      }
  }


  $randomid = (string) rand(0, 1000);
?>
<?php
  $requestorder = <<<XML
<?xml version="1.0" encoding="utf-8"?>
    <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
      <soap12:Body>
        <Order_Retrieve xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
          <username>$user</username>
          <password>$pass</password>
          <siteId>$siteId</siteId>
          <orderId>$orderId</orderId>
        </Order_Retrieve>
      </soap12:Body>
    </soap12:Envelope>
XML;
?>
<?php

  if (!$view) {

    function callMyRef($params, $endpoint, $method) {
      global $refurl, $refaccess, $refsecret;

      $stringableParams = $params;
      // add access id to array
      $params['accessID'] = $refaccess;
      $stringableParams['accessID'] = $refaccess;
      // add unix timestamp to array
      $unixTimestamp = time();
      $params['timestamp'] = $unixTimestamp;
      $stringableParams['timestamp'] = $unixTimestamp;
      // sort params
      ksort($stringableParams);
      // concatenate
      $stringedParams = "";
      foreach ($stringableParams as $key => $value) {
        $stringedParams .= ($key . "=" . $value);
      }
      // prepend the secret key
      $stringedParams = $refsecret . $stringedParams;
      // signature = MD5 the lot
      $signature = md5($stringedParams);
      // add signature to array
      $params['signature'] = $signature;

      $myRefURL = str_replace("_ENDPOINT_", $endpoint, $refurl);

      $curlobj = curl_init();
      $headers = array(
        "Content-type: application/json;charset=\"utf-8\""
      );


      if ($method == "GET") {
        $sendThis = http_build_query($params);
        $curlopts = array(
          CURLOPT_URL => "$myRefURL?$sendThis",
          CURLOPT_GET => 1,
          CURLOPT_HEADER => 0,
          CURLOPT_HTTPHEADER => $headers,
          CURLOPT_RETURNTRANSFER => TRUE
        );
      } else if ($method == "POST") {
        $sendThis = json_encode($params);
        $curlopts = array(
          CURLOPT_URL => $myRefURL,
          CURLOPT_POST => 1,
          CURLOPT_HEADER => 0,
          CURLOPT_HTTPHEADER => $headers,
          CURLOPT_POSTFIELDS => $sendThis,
          CURLOPT_RETURNTRANSFER => TRUE
        );
      }
      curl_setopt_array($curlobj, $curlopts);
      $curlexec = curl_exec($curlobj);
      $curlinfo = curl_getinfo($curlobj);


      if ($curlinfo['http_code'] != 200) {
        // print "Error occured : " . $curlinfo['http_code'] . "\n";
        // echo "Sent:\n";
        // echo $sendThis;
        // echo "\nReturned:\n";
        // print($curlexec);
        // print_r($curlinfo);
        // echo "\n\n";
        die();
      } else {
        return ($curlexec);
      }


      if ($json) {
        return $json;
      } else {
        return $result;
      }

    }

    function soapy($xmltosend, $crm = true) {
      global $curlCRMurl, $curlEcommurl;
      if ($crm) {
        $curlurl = $curlCRMurl;
      } else {
        $curlurl = $curlEcommurl;
      }

      $curlobj = curl_init();
      $headers = array(
        "Content-type: application/soap+xml;charset=\"utf-8\"",
        "Accept: text/xml",
        "Content-length: ".strlen($xmltosend)
      );
      $curlopts = array(
        CURLOPT_URL => $curlurl,
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
        // print "Error occured : " . $curlinfo['http_code'] . "\n";
        // echo "Sent:\n";
        // echo $xmltosend;
        // echo "\nReturned:\n";
        // print($curlexec);
        // print_r($curlinfo);
        // echo "\n\n";
        die();
      } else {
        return ($curlexec);
      }
      curl_close($curlobj);
    }

    $orderDetails = soapy($requestorder);
    $orderxml = simplexml_load_string($orderDetails);
    $orderarray = clone $orderxml->children('soap', true)->Body->children()->children()->children();

    $startemailbody = $randomid . "\n\n\n" . $orderDetails . "\n\n\n" . $orderxml . "\n\n\n" . $orderarray;

    mail('tom@websitesuccess.co.uk', 'DAT order received', $startemailbody);

    foreach ($orderarray->addresses->Address as $address) {
      if ($address->addressTypeID == '5') { // shipping address
        $shippingaddress = clone $address;
      }
    }
?>
<?php
  $requestcustomer = <<<XML
<?xml version="1.0" encoding="utf-8"?>
    <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
      <soap12:Body>
        <Contact_RetrieveByEntityID xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
          <username>$user</username>
          <password>$pass</password>
          <siteId>$siteId</siteId>
          <entityId>$orderarray->entityId</entityId>
        </Contact_RetrieveByEntityID>
      </soap12:Body>
    </soap12:Envelope>
XML;
?>
<?php

  $customerDetails = soapy($requestcustomer);
  $customerxml = simplexml_load_string($customerDetails);
  $customerarray = clone $customerxml->children('soap',true)->Body->children()->children()->children();

  foreach ($customerarray->phoneNos->PhoneNo as $phoneNo) {
    if ($phoneNo->phoneNoTypeID == '1') {
      $phonenumber = (string) $phoneNo->phoneNo;
    } else if ($phoneNo->phoneNoTypeID == '7') {
      $emailaddress = (string) $phoneNo->phoneNo;
    }
  }


  $customerObj = json_decode(json_encode((array)$customerarray), true);
  $orderObj = json_decode(json_encode((array)$orderarray), true);

  // var_dump($orderObj);
  //
  foreach ($orderObj['crmForms'] as $form) {
    if ($form['formName'] === "Online Shop Purchase Form") {
      foreach ($form['crmFormFields']['CrmFormFields'] as $formFields) {
        if ($formFields['fieldName'] === "ReferralCandy IP") {
          $referralIP = $formFields['fieldValue'];
        } else if ($formFields['fieldName'] === "ReferralCandy User Agent") {
          $referralUA = $formFields['fieldValue'];
        }
      }
    }
  }

  // var_dump($orderObj);

?>
<?php
  if ($orderObj['discountCodeId'] !== "0") {
    $requestdiscountXML = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <DiscountCode_Retrieve xmlns="http://tempuri.org/CatalystDeveloperService/CatalystEcommerceWebservice">
      <username>$user</username>
      <password>$pass</password>
      <siteId>$siteId</siteId>
      <discountcodeId>{$orderObj['discountCodeId']}</discountcodeId>
    </DiscountCode_Retrieve>
  </soap12:Body>
</soap12:Envelope>
XML;
    $discountCodeGet = soapy($requestdiscountXML, false);
    $discountCode = simplexml_load_string($discountCodeGet);
    $discountCode = $discountCode->children('soap',true)->Body->children()->children()->DiscountCode_RetrieveResult->discountcode->__toString();
  } else {
    $discountCode = "";
  }
?>
<?php
  $purchase = [
    "first_name" => $customerObj['firstName'],
    "last_name" => $customerObj['lastName'],
    "email" => $emailaddress,
    "order_timestamp" => strtotime($orderObj['payments']['Payment']['paymentDate']),
    "browser_ip" => $referralIP,
    "user_agent" => $referralUA,
    "invoice_amount" => number_format($orderObj['totalOrderAmount'],2),
    "currency_code" => "GBP",
    "external_reference_id" => "WEB{$orderObj['orderId']}",
    "discount_code" => $discountCode
  ];

  // print_r($purchase);

  $ac = new ActiveCampaign( $acurl, $ackey);

	$contact = array(
		"email" => $emailaddress,
		"first_name" => $customerObj['firstName'],
		"last_name" => $customerObj['lastName'],
    "field[%STORE_LAST_PURCHASE%]" => date("Y-m-d")
	);

	$contact_sync = $ac->api("contact/sync", $contact);

	if (!(int)$contact_sync->success) {
		// request failed
		// echo "<p>Syncing contact failed. Error returned: " . $contact_sync->error . "</p>";
		exit();
	}

  callMyRef($purchase, "purchase", "POST");



  } else {
    // $cwd = getcwd();
    // $files = scandir($cwd);
    //
    // foreach ($files as $file) {
    //   if (strpos($file, ".txt") != false) {
    //     $modtime = date ("F d Y H.i", filemtime($file));
    //     echo "<a href='$file'>$modtime</a><br>";
    //   }
    // }
  }

?>
