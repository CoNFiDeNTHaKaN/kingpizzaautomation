<?php

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: *");
  header("Access-Control-Allow-Methods: *");
  header('Pragma: public');
  header('Cache-control: private');
  header('Expires: -1');
  header('Content-Type: application/json', true);

  $SITEID = "121067";
  $USEREMAIL = $_SERVER['HTTP_USEREMAIL'];
  $USERID = $_SERVER['HTTP_USERID'];
  $SESSIONID = $_SERVER['HTTP_SESSIONID'];
  $USERNAME = "tech@websitesuccess.co.uk";
  $PASSWORD = "DevTime69";
?>
<?php
  function efusionCRMToArray($efusionCRMCustomer)
  {
      if (array_key_exists('crmForms', $efusionCRMCustomer)) {
          foreach ($efusionCRMCustomer['crmForms'] as $form) {
              if ($form['formName'] == "LMS") {
                  $valueArray = [];
                  foreach ($form['crmFormFields']['CrmFormFields'] as $fields) {
                      $valueArray[ $fields['fieldName'] ] = $fields['fieldValue'];
                      $valueArray[ str_replace('?','', explode(' (', $fields['fieldName'])[0]) ] = $fields['fieldValue'];
                  }
              }
          }
          $efusionCRMCustomer = array_merge($efusionCRMCustomer, $valueArray);

          $efusionCRMCustomer['isAdmin'] = (bool) $efusionCRMCustomer['Company Administrator? (Yes or No)'];
      }
      if (array_key_exists('phoneNos', $efusionCRMCustomer)) {
          $phoneNoTypes = [
            "5" => "Cell Phone",
            "7" => "Email 1",
            "8" => "Email 2",
            "9" => "Email 3",
            "2" => "Home Fax",
            "1" => "Home Phone",
            "6" => "Pager",
            "10" => "Web Address",
            "4" => "Work Fax",
            "3" => "Work Phone"
        ];
          $valueArray = [];


          if (!array_key_exists('phoneNoTypeID', $efusionCRMCustomer['phoneNos']['PhoneNo'])) {
              foreach ($efusionCRMCustomer['phoneNos']['PhoneNo'] as $contactInfo) {
                  $type = $phoneNoTypes[ $contactInfo['phoneNoTypeID'] ];
                  $valueArray[ $type ] = $contactInfo['phoneNo'];
              }
          } else {
              $type = $phoneNoTypes[ $efusionCRMCustomer['phoneNos']['PhoneNo']['phoneNoTypeID'] ];
              $valueArray[ $type ] = $efusionCRMCustomer['phoneNos']['PhoneNo']['phoneNo'];
          }

          if (array_key_exists('Email 1', $valueArray)) {
              $valueArray['Email'] = $valueArray['Email 1'];
          }
          if (array_key_exists('Home Phone', $valueArray) || array_key_exists('Work Phone', $valueArray)) {
              $primaryPhoneNo = (array_key_exists('Home Phone', $valueArray)) ? $valueArray['Home Phone'] : $valueArray['Work Phone'];
              $valueArray['Phone'] = $primaryPhoneNo;
          }

          $efusionCRMCustomer = array_merge($efusionCRMCustomer, $valueArray);
      }
      return $efusionCRMCustomer;
  }


  function authenticateSession()
  {
      global $SITEID, $USERID, $USEREMAIL, $SESSIONID;
      $authenticateXML = <<<XML
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <Contact_IsLoggedIn xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
      <siteId>{$SITEID}</siteId>
      <entityId>{$USERID}</entityId>
      <sessionId>{$SESSIONID}</sessionId>
    </Contact_IsLoggedIn>
  </soap12:Body>
</soap12:Envelope>
XML;

      // var_dump($authenticateXML);

      $authenticate = soapy($authenticateXML);
      $isUserLoggedIn = simplexml_load_string($authenticate)->children('soap', true)->Body->children()->children()->Contact_IsLoggedInResult;
      return ($isUserLoggedIn == "true") ? 'true' : 'false';
  }

  function authenticatedUser()
  {
      print authenticateSession();
  }

  function getCurrentUser($asJSON = false)
  {
      global $SITEID, $USERID, $USEREMAIL, $SESSIONID, $USERNAME, $PASSWORD;
      // get entityID from request
      $userId = $USERID;

      // get customer from efusion
      $getUserXML = <<<XML
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <Contact_RetrieveByEntityID xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
      <username>{$USERNAME}</username>
      <password>{$PASSWORD}</password>
      <siteId>{$SITEID}</siteId>
      <entityId>{$userId}</entityId>
    </Contact_RetrieveByEntityID>
  </soap12:Body>
</soap12:Envelope>
XML;

      $getUser = soapy($getUserXML);
      $user = simplexml_load_string($getUser)->children('soap', true)->Body->children()->children()->Contact_RetrieveByEntityIDResult->children();

      // var_dump($getUser);

      $userObj = json_decode(json_encode((array)$user), true);

      $userObj = efusionCRMToArray($userObj);

      if ($asJSON) {
          header('Content-Type: application/json');
          $userObj = json_encode($userObj);
      }

      return $userObj;
  }

  function getCompanyUsers($asJSON = false)
  {
      global $SITEID, $USERID, $USEREMAIL, $SESSIONID, $USERNAME, $PASSWORD;
      // get all users from efusion
      // loop
      $moreRecords = true;
      $recordStart = 0;
      $allCustomerRecords = [];

      // $userCompany

      do {
          // print "
          // SrecordStart
          // $recordStart
          // ";

          $getuserSetXML = <<<XML
      <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <ContactList_Retrieve xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
      <username>{$USERNAME}</username>
      <password>{$PASSWORD}</password>
      <siteId>{$SITEID}</siteId>
      <lastUpdateDate>2000-01-01T00:00:00</lastUpdateDate>
      <recordStart>{$recordStart}</recordStart>
      <moreRecords>true</moreRecords>
    </ContactList_Retrieve>
  </soap12:Body>
</soap12:Envelope>
XML;
          $getuserSet = soapy($getuserSetXML);
          $userSet = simplexml_load_string($getuserSet)->children('soap', true)->Body->children()->children()->ContactList_RetrieveResult->children();
          $userSetObj = json_decode(json_encode((array)$userSet), true)['ContactRecord'];
          $userSetJSON = json_encode(json_decode(json_encode((array)$userSet), true)['ContactRecord']);

          if ($recordStart === 0) {
              // print_r($userSetObj);
          }

          $userSetCount = $userSet->count();

          $recordStart = (int) ($recordStart + $userSetCount);

          $allCustomerRecords = array_merge($allCustomerRecords, $userSetObj);

          // var_dump($userSetJSON);

          $moreRecords = ((string) simplexml_load_string($getuserSet)->children('soap', true)->Body->children()->children()->moreRecords === "true") ? true : false;
      } while ($moreRecords);


      // get core user ()
      $currentUser = getCurrentUser();

      // var_dump($allCustomerRecords);

      // loop users and filter
      $customerMatches = [];
      foreach ($allCustomerRecords as $customer) {
          if (isset($customer['crmForms']['CrmForms']['formName'])) {
              foreach ($customer['crmForms'] as $form) {
                  if ($form['formName'] == "LMS") {
                      foreach ($form['crmFormFields']['CrmFormFields'] as $fields) {
                          if ($fields['fieldName'] == "Company Name") {
                              if ($fields['fieldValue'] == $currentUser['Company Name']) {
                                  if ($customer['entityId'] !== $currentUser['entityId']) {
                                      $customer = efusionCRMToArray($customer);
                                      array_push($customerMatches, $customer);
                                  }
                              }
                          }
                      }
                  }
              }
          }
      }

      // return JSON array of related users
      $return = [
        "currentUser" => $currentUser,
        "companyUsers" => $customerMatches
      ];

      if ($asJSON) {
          header('Content-Type: application/json');
          $return = json_encode($return);
      }

      return $return;
  }


  function isExistingUser($email)
  {
      global $SITEID, $USERID, $USEREMAIL, $SESSIONID, $USERNAME, $PASSWORD;

      // get user from id
      $userEmail = $_POST['email'];

      // is user new else return error
      $getUserByEmailXML = <<<XML
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <Contact_RetrieveByEmailAddress xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
      <username>{$USERNAME}</username>
      <password>{$PASSWORD}</password>
      <siteId>{$SITEID}</siteId>
      <emailAddress>{$userEmail}</emailAddress>
    </Contact_RetrieveByEmailAddress>
  </soap12:Body>
</soap12:Envelope>
XML;

      // var_dump($getUserByEmailXML);

      $getUserByEmail = soapy($getUserByEmailXML);
      $found = (bool) $getUserByEmail;
      return $found;
  }

  function canUserAddMoreUsers ()
  {
    global $SITEID, $USERID, $USEREMAIL, $SESSIONID, $USERNAME, $PASSWORD;
    // get user
    $currentUser = getCurrentUser();

    if (!$currentUser['isAdmin']) {
        http_response_code(401);
        return json_encode([
        'error' => 'You are not authorised to add users.'
      ]);
    }
    // get company user count
    $companyUsers = getCompanyUsers()['companyUsers'];
    $companyUserCount = (count($companyUsers) + 1);
    $companyUserLimit = (int) $currentUser['Company User Limit'];

    // var_dump($companyUserLimit);
    // is company user count >= limit
    if ( ($companyUserCount < $companyUserLimit ) || $companyUserLimit === 0 )
    {
      return 'true';
    } else {
      http_response_code(403);
      return json_encode([
        'error' => 'Your company user limit has been reached, please remove existing users or contact support to increase your allowance.'
      ]);
    }
  }

  function addCompanyUser()
  {
      global $SITEID, $USERID, $USEREMAIL, $SESSIONID, $USERNAME, $PASSWORD;

      $userEmail = $_POST['email'];
      $foundUser = isExistingUser($userEmail);

      if ($foundUser) {
          http_response_code(400);
          return json_encode([
            'error' => 'User already exists.'
          ]);
      }

      $currentUser = getCurrentUser();

      if (!$currentUser['isAdmin']) {
          http_response_code(401);
          return json_encode([
          'error' => 'You are not authorised to add users.'
        ]);
      }

      $externalId = 'API' . rand(10, 99) . time();

      $addUserXML = <<<XML
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <ContactList_UpdateInsert xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
      <username>{$USERNAME}</username>
      <password>{$PASSWORD}</password>
      <siteId>{$SITEID}</siteId>
      <contactsList>
        <ContactRecord>
          <externalId>{$externalId}</externalId>
          <emailAddress>{$_POST['email']}</emailAddress>
          <deleted>false</deleted>
          <firstName>{$_POST['first_name']}</firstName>
          <lastName>{$_POST['last_name']}</lastName>
          <username>{$_POST['email']}</username>
          <password></password>
          <dateOfBirth>{$_POST['dob']}</dateOfBirth>
          <crmForms>
            <CrmForms>
              <formId>542957</formId>
              <formName>LMS</formName>
              <crmFormFields>
                <CrmFormFields>
                  <fieldId>364089</fieldId>
                  <fieldTypeId>3</fieldTypeId>
                  <fieldName>Company Administrator? (Yes or No)</fieldName>
                  <fieldValue>0</fieldValue>
                </CrmFormFields>
                <CrmFormFields>
                  <fieldId>364090</fieldId>
                  <fieldTypeId>1</fieldTypeId>
                  <fieldName>Company User Limit (0 for unlimited)</fieldName>
                  <fieldValue></fieldValue>
                </CrmFormFields>
                <CrmFormFields>
                  <fieldId>364091</fieldId>
                  <fieldTypeId>1</fieldTypeId>
                  <fieldName>Company Name</fieldName>
                  <fieldValue>{$currentUser['Company Name']}</fieldValue>
                </CrmFormFields>
                <CrmFormFields>
                  <fieldId>364092</fieldId>
                  <fieldTypeId>1</fieldTypeId>
                  <fieldName>Location Name</fieldName>
                  <fieldValue>{$_POST['location']}</fieldValue>
                </CrmFormFields>
                <CrmFormFields>
                  <fieldId>364093</fieldId>
                  <fieldTypeId>1</fieldTypeId>
                  <fieldName>Menu ID</fieldName>
                  <fieldValue>{$currentUser['Menu ID']}</fieldValue>
                </CrmFormFields>
              </crmFormFields>
            </CrmForms>
          </crmForms>
          <MasterOptIn>true</MasterOptIn>
        </ContactRecord>
      </contactsList>
    </ContactList_UpdateInsert>
  </soap12:Body>
</soap12:Envelope>
XML;

      $addUser = soapy($addUserXML, true);
      // var_dump($addUser);
      if ($addUser['boolean']) {
          http_response_code(201);
      } else {
          http_response_code(400);
      }


      $newUser = getCompanyUser(false, $_POST['email']);


      // echo "SnewUser";
      // var_dump($newUser);


      $currentUserSZXML = <<<XML
        <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <Contact_RetrieveZonesByEntityID xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
      <username>{$USERNAME}</username>
      <password>{$PASSWORD}</password>
      <siteId>{$SITEID}</siteId>
      <entityId>{$currentUser['entityId']}</entityId>
    </Contact_RetrieveZonesByEntityID>
  </soap12:Body>
</soap12:Envelope>
XML;

      $getCurrentUserSZ = soapy($currentUserSZXML);
      $currentUserSZ = simplexml_load_string($getCurrentUserSZ)->children('soap', true)->Body->children()->children();

      // var_dump($currentUserSZ->Contact_RetrieveZonesByEntityIDResult->children()->asXML());

      $currentUserSZString = "<securezoneList>";
      foreach ($currentUserSZ->Contact_RetrieveZonesByEntityIDResult->children() as $szXML) {
          $asXML = $szXML->asXML();
          if (strpos($asXML, 'EMS') !== false) {
              $currentUserSZString .= $szXML->asXML();
          }
      }
      $currentUserSZString .= "</securezoneList>";

      // var_dump($currentUserSZString);

      $updateNewUserSZXML = <<<XML
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <Contact_SecureZoneListUpdateInsert xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
      <username>{$USERNAME}</username>
      <password>{$PASSWORD}</password>
      <siteId>{$SITEID}</siteId>
      <emailaddress>{$_POST['email']}</emailaddress>
      {$currentUserSZString}
    </Contact_SecureZoneListUpdateInsert>
  </soap12:Body>
</soap12:Envelope>
XML;

      $updateNewUserSZ = soapy($updateNewUserSZXML);
      $newUserSZ = simplexml_load_string($updateNewUserSZ)->children('soap', true)->Body->children()->children();

      // var_dump($newUserSZ);
  }

  function editCompanyUser()
  {
      global $SITEID, $USERID, $USEREMAIL, $SESSIONID, $USERNAME, $PASSWORD;
      // get customer
      $user = getCompanyUser()['user'];
      if ($user === false) {
          http_response_code(404);
          return json_encode([
            'error' => 'User not found, please try again later.'
          ]);
      }

      $currentUser = getCurrentUser();
      if (!canUserAdminUser($currentUser, $user)) {
          http_response_code(404);
          return json_encode([
            'error' => 'You don\'t have access to administrate this user, please contact support.'
          ]);
      }

      // get user xml by email address
      $getUserXML = <<<XML
      <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
        <soap12:Body>
          <Contact_RetrieveByEntityID xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
            <username>{$USERNAME}</username>
            <password>{$PASSWORD}</password>
            <siteId>{$SITEID}</siteId>
            <entityId>{$user['entityId']}</entityId>
          </Contact_RetrieveByEntityID>
        </soap12:Body>
      </soap12:Envelope>
XML;

      $getUser = soapy($getUserXML);

      $userParent = simplexml_load_string($getUser)->children('soap', true)->Body->children()->children()->Contact_RetrieveByEntityIDResult;
      $user = $userParent->children();

      // var_dump($user);

      if ($_POST['first_name']) {
          $user->firstName = $_POST['first_name'];
      }
      if ($_POST['last_name']) {
          $user->lastName = $_POST['last_name'];
      }
      if ($_POST['email']) {
          $hasEmailAddress = false;
          foreach ($user->phoneNos->PhoneNo as $phoneNo) {
              if ($phoneNo->phoneNoTypeID == "7") {
                  $hasEmailAddress = true;
              }
          }
          if ($hasEmailAddress) {
              foreach ($user->phoneNos->PhoneNo as $phoneNo) {
                  if ($phoneNo->phoneNoTypeID == "7") {
                      $phoneNo->phoneNo = $_POST['email'];
                  }
              }
          } else {
              $newEmailChild = $user->phoneNos->addChild('PhoneNo');
              $newEmailChild->addChild('phoneNoTypeID', '7');
              $newEmailChild->addChild('phoneNo', $_POST['email']);
          }
      }
      if ($_POST['contact_number']) {
          $hasPhoneNumber = false;
          foreach ($user->phoneNos->PhoneNo as $phoneNo) {
              if ($phoneNo->phoneNoTypeID == "1") {
                  $hasPhoneNumber = true;
              }
          }
          if ($hasPhoneNumber) {
              foreach ($user->phoneNos->PhoneNo as $phoneNo) {
                  if ($phoneNo->phoneNoTypeID == "1") {
                      $phoneNo->phoneNo = $_POST['contact_number'];
                  }
              }
          } else {
              $newPhoneNumber = $user->phoneNos->addChild('PhoneNo');
              $newPhoneNumber->addChild('phoneNoTypeID', '1');
              $newPhoneNumber->addChild('phoneNo', $_POST['contact_number']);
          }
      }
      if ($_POST['dob']) {
          $user->dateOfBirth = $_POST['dob'];
      }
      if ($_POST['location']) {
          foreach ($user->crmForms->CrmForms as $form) {
              if ($form->formName == "LMS") {
                  foreach ($form->crmFormFields->CrmFormFields as $fields) {
                      if ($fields->fieldName == "Location Name") {
                          $fields->fieldValue = $_POST['location'];
                      }
                  }
              }
          }
      }

      $userUpdatedXML = str_replace('Contact_RetrieveByEntityIDResult', 'ContactRecord', $userParent->asXML());
      $updateUserXML = <<<XML
        <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <ContactList_UpdateInsert xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
      <username>{$USERNAME}</username>
      <password>{$PASSWORD}</password>
      <siteId>{$SITEID}</siteId>
      <contactsList>
        {$userUpdatedXML}
      </contactsList>
    </ContactList_UpdateInsert>
  </soap12:Body>
</soap12:Envelope>
XML;

      $updateUser = soapy($updateUserXML, true)['boolean'];

      // var_dump($updateUser);
      // return confirmation
  }

  function deleteCompanyUser()
  {
    global $SITEID, $USERID, $USEREMAIL, $SESSIONID, $USERNAME, $PASSWORD;

    if (!isset($_POST['confirmed'])) {
      http_response_code(400);
      return json_encode([
        'error' => 'Please confirm you really want to delete this user.'
      ]);
    } else if ($_POST['confirmed'] !== "yes") {
      http_response_code(400);
      return json_encode([
        'error' => 'Please confirm you really want to delete this user.'
      ]);
    }

    $user = getCompanyUser()['user'];
    if ($user === false) {
        http_response_code(404);
        return json_encode([
          'error' => 'User not found, please try again later.'
        ]);
    }

    $currentUser = getCurrentUser();
    if (!canUserAdminUser($currentUser, $user)) {
        http_response_code(404);
        return json_encode([
          'error' => 'You don\'t have access to administrate this user, please contact support.'
        ]);
    }

    // get user xml by email address
    $deleteUserXML = <<<XML
    <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
      <soap12:Body>
        <Contact_DeleteByEntityID xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
          <username>{$USERNAME}</username>
          <password>{$PASSWORD}</password>
          <siteId>{$SITEID}</siteId>
          <entityId>{$user['entityId']}</entityId>
        </Contact_DeleteByEntityID>
      </soap12:Body>
    </soap12:Envelope>
XML;

    $deleteUser = soapy($deleteUserXML, true);
    var_dump($deleteUser);
    if ($deleteUser['boolean']) {
        http_response_code(204);
    } else {
        http_response_code(400);
    }
  }

  function getCompanyUser($asJSON = false, $byEmail = false)
  {
      global $SITEID, $USERID, $USEREMAIL, $SESSIONID, $USERNAME, $PASSWORD;
      // auth
      $currentUser = getCurrentUser();

      if ($byEmail === false) {
          // get user from id
          $userId = $_POST['id'];

          // get customer from efusion
          $getUserXML = <<<XML
      <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
        <soap12:Body>
          <Contact_RetrieveByEntityID xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
            <username>{$USERNAME}</username>
            <password>{$PASSWORD}</password>
            <siteId>{$SITEID}</siteId>
            <entityId>{$userId}</entityId>
          </Contact_RetrieveByEntityID>
        </soap12:Body>
      </soap12:Envelope>
XML;
          $getUser = soapy($getUserXML);
          $user = simplexml_load_string($getUser)->children('soap', true)->Body->children()->children()->Contact_RetrieveByEntityIDResult->children();
      } else {
          $userEmail = $byEmail;

          // get customer from efusion
          $getUserXML = <<<XML
      <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
        <soap12:Body>
          <Contact_RetrieveByEmailAddress xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
            <username>{$USERNAME}</username>
            <password>{$PASSWORD}</password>
            <siteId>{$SITEID}</siteId>
            <emailAddress>{$userEmail}</emailAddress>
          </Contact_RetrieveByEmailAddress>
        </soap12:Body>
      </soap12:Envelope>
XML;
          $getUser = soapy($getUserXML);
          $user = simplexml_load_string($getUser)->children('soap', true)->Body->children()->children()->Contact_RetrieveByEmailAddressResult->children();
      }

      if ($getUser === false) {
          return false;
      }

      // var_dump($user);
      $userObj = json_decode(json_encode((array)$user), true);

      // var_dump($userObj);

      $userObj = efusionCRMToArray($userObj);

      // var_dump(canUserAdminUser($currentUser, $userObj));

      // check retrieving user is the user OR the admin
      if (canUserAdminUser($currentUser, $userObj)) {
          $return = [
          "currentUser" => $currentUser,
          "user" => $userObj
        ];

          if ($asJSON) {
              header('Content-Type: application/json');
              $return = json_encode($return);
          }

          return $return;
      } else {
          return false;
      }
  }

  /**
   * [canUserAdminUser Tests whether User A has privileges to administrate User B]
   * @param  User $adminer [description]
   * @param  User $adminee [description]
   * @return Boolean [True is User A can administrate User B]
   */
  function canUserAdminUser($adminer, $adminee)
  {
      if ($adminer['Company Administrator? (Yes or No)'] == "1") {
          if ($adminer['Company Name'] == $adminee['Company Name']) {
              return true;
          } else {
              return false;
          }
      } else {
          return false;
      }
  }

    function soapy($xmltosend, $returnBoolean = false)
    {
        //
        //  "Cache-Control:no-cache",
        //  CURLOPT_FRESH_CONNECT, TRUE
        $soap_listretrieve = "https://westburyenvironmental.worldsecuresystems.com/catalystwebservice/catalystcrmwebservice.asmx";
        $curlobj = curl_init();
        $headers = array(
        "Content-type: application/soap+xml;charset=\"utf-8\"",
        "Accept: text/xml",
        "Cache-Control:no-cache",
        "Content-length: ".strlen($xmltosend)
      );
        $curlopts = array(
        CURLOPT_URL => $soap_listretrieve,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $xmltosend,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FRESH_CONNECT => true
      );
        curl_setopt_array($curlobj, $curlopts);
        $curlexec = curl_exec($curlobj);
        $curlinfo = curl_getinfo($curlobj);

        // var_dump($curlinfo);

        if ($curlinfo['http_code'] != 200) {
            $error = simplexml_load_string($curlexec)->children('soap', true)->Body->Fault->Reason->Text;
            $date = date("l jS \of F H:i:s");
            var_dump($xmltosend);
            var_dump($curlinfo);
            var_dump($curlexec);
            $errorResponse = "
            ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            +
            +   Error occured : {$curlinfo['http_code']} [{$date}]
            +
            +   {$xmltosend}
            +   {$error}
            +
            ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

                  ";
            echo $errorResponse;
            if ($returnBoolean) {
                return [
                  "bool"=>false,
                  "boolean"=>false,
                  "response" => (string) $curlexec
                ];
            } else {
                return false;
            }
        } else {
            if ($returnBoolean) {
                return [
                  "bool"=>true,
                  "boolean"=>true,
                  "response" => (string) $curlexec
                ];
            } else {
                return (string) $curlexec;
            }
        }
        curl_close($curlobj);
    }


 if (isset($_SERVER['PATH_INFO'])) {
     if (str_replace('/', '', $_SERVER['PATH_INFO']) == 'authenticated-user') {
         call_user_func('authenticatedUser');
     } elseif (authenticateSession() == 'true') {
         if (str_replace('/', '', $_SERVER['PATH_INFO']) == 'get-current-user') {
             print call_user_func('getCurrentUser', true);
         } elseif (str_replace('/', '', $_SERVER['PATH_INFO']) == 'get-company-users') {
             print call_user_func('getCompanyUsers', true);
         } elseif (str_replace('/', '', $_SERVER['PATH_INFO']) == 'get-company-user') {
             print call_user_func('getCompanyUser', true);
         } elseif (str_replace('/', '', $_SERVER['PATH_INFO']) == 'can-user-add-more-users') {
             print call_user_func('canUserAddMoreUsers', true);
         } elseif (str_replace('/', '', $_SERVER['PATH_INFO']) == 'add-company-user') {
             print call_user_func('addCompanyUser');
         } elseif (str_replace('/', '', $_SERVER['PATH_INFO']) == 'edit-company-user') {
             call_user_func('editCompanyUser');
         } elseif (str_replace('/', '', $_SERVER['PATH_INFO']) == 'delete-company-user') {
             print call_user_func('deleteCompanyUser');
         } else {
             http_response_code(404);
         }
     } else {
         http_response_code(401);
     }
 } else {
     http_response_code(404);
 }
