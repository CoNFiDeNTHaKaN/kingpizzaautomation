<?php
require __DIR__ . '/vendor/autoload.php';

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
  require 'options.php';
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
  use Carbon\Carbon;
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

    function soapy($xmltosend) {
      global $curlurl;
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
        print "Error occured : " . $curlinfo['http_code'] . "\n";
        echo "Sent:\n";
        echo $xmltosend;
        echo "\nReturned:\n";
        print($curlexec);
        print_r($curlinfo);
        echo "\n\n";
        die();
      } else {
        return ($curlexec);
      }
      curl_close($curlobj);
    }

    function post ($url, $data, $additionalHeader = "") {
      $headers = "Content-type: application/x-www-form-urlencoded\r\n" . $additionalHeader;
      $options = array(
          'http' => array(
              'header'  => $headers,
              'method'  => 'POST',
              'content' => http_build_query($data)
          )
      );
      $context  = stream_context_create($options);
      $result = file_get_contents($url, false, $context);
      $json = json_decode($result);
      // if ($result === FALSE) { /* Handle error */ }

      if ($json) {
        return $json;
      } else {
        return $result;
      }

    }

    $orderDetails = soapy($requestorder);
    $orderxml = simplexml_load_string($orderDetails);
    $orderarray = clone $orderxml->children('soap', true)->Body->children()->children()->children();
    $orderJSON = json_decode(json_encode( (array)$orderarray ), TRUE);

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
  $customerJSON = json_decode(json_encode((array)$customerarray), TRUE);


  foreach ($customerarray->phoneNos->PhoneNo as $phoneNo) {
    if ($phoneNo->phoneNoTypeID == '1') {
      $phonenumber = (string) $phoneNo->phoneNo;
    } else if ($phoneNo->phoneNoTypeID == '7') {
      $emailaddress = (string) $phoneNo->phoneNo;
    }
  }

  $authorizeByApplicationData = [
    "applicationId" =>  $linnworksId,
    "applicationSecret" => $linnworksSecret,
    "token" => $linnworksToken
  ];
  $authorizeByApplication = post("https://api.linnworks.net/api/Auth/AuthorizeByApplication", $authorizeByApplicationData);
  $l_token = $authorizeByApplication->Token;

  foreach ($orderarray->addresses->Address as $address) {
    if ($address->addressTypeID == "8") {
      $orderBillingAddress = json_decode(json_encode((array)$address), TRUE);
    }
  }

  $ordersToPush = [];
  $orderItem = 0;

  foreach ($orderarray->products->Product as $product) {

    $item = json_decode(json_encode((array)$product), TRUE);
    $recipientInfoArr = explode(" | ",$product->productDescription);
    array_pop($recipientInfoArr);
    $recipientInfo = array();
    foreach ($recipientInfoArr as $info) {
      $split = explode( " : ", $info );
      $item[ $split[0] ] = $split[1];
    }
    //
    // echo "+++ orderJSON +++\n";
    // var_dump($orderJSON);
    //
    // echo "+++ customerJSON +++\n";
    // var_dump($customerJSON);
    //
    // echo "\n\n+++ item +++\n";
    // var_dump($item);

    $orderToPush = new OrderToPush;
    $orderToPush->ReceivedDate = $orderJSON['lastUpdateDate'];
    $orderToPush->DispatchBy = Carbon::createFromFormat("d-m-Y", $item["Preferred Delivery Date"] )->toIso8601String();
    $orderToPush->PaidOn = $orderToPush->DispatchBy;
    $orderToPush->ChannelBuyerName = $customerJSON['fullName'];
    $orderToPush->ReferenceNumber = $orderJSON['orderId'] . "-" . str_pad($orderItem, 2, '0', STR_PAD_LEFT);

    $orderToPush->BillingAddress = [];
    $orderToPush->BillingAddress['FullName'] = (string) $customerJSON['fullName'];
    $orderToPush->BillingAddress['Address1'] = (string) $orderBillingAddress['addressLine1'];
    $orderToPush->BillingAddress['Address2'] = (string) $orderBillingAddress['addressLine2'];
    $orderToPush->BillingAddress['Town'] = (string) $orderBillingAddress['city'];
    $orderToPush->BillingAddress['Region'] = (string) $orderBillingAddress['state'];
    $orderToPush->BillingAddress['PostCode'] = (string) $orderBillingAddress['zipcode'];
    $orderToPush->BillingAddress['Country'] = (string) $orderBillingAddress['countryCode'];
    $orderToPush->BillingAddress['PhoneNumber'] = (isset($phonenumber)) ? $phonenumber : "";
    $orderToPush->BillingAddress['EmailAddress'] = (string) $emailaddress;

    $orderToPush->DeliveryAddress = [];
    $orderToPush->DeliveryAddress['FullName'] = (string) $item['Recipient First Name'] . " " . $item['Recipient Last Name'];
    $orderToPush->DeliveryAddress['Address1'] = (string) $item['Address Line 1'];
    $orderToPush->DeliveryAddress['Address2'] = (string) $item['Address Line 2'];
    $orderToPush->DeliveryAddress['Town'] = (string) $item['Town'];
    $orderToPush->DeliveryAddress['Region'] = (string) $item['County'];
    $orderToPush->DeliveryAddress['PostCode'] = (string) strtoupper($item['Postcode']);
    $orderToPush->DeliveryAddress['Country'] = "GB";

    $orderToPush->Notes = [];
    $orderToPush->Notes[0]['NoteEntryDate'] = Carbon::now()->toIso8601String();
    $orderToPush->Notes[0]['NoteUserName'] = "API";
    $orderToPush->Notes[0]['Internal'] = false;
    $orderToPush->Notes[0]['Note'] = (string) $item['Gift Message'];

    $orderToPush->OrderItems[0]['PricePerUnit'] = $item['unitPrice'];
    $orderToPush->OrderItems[0]['Qty'] = $item['units'];
    $orderToPush->OrderItems[0]['ChannelSKU'] = $item['productCode'];
    $orderToPush->OrderItems[0]['ItemTitle'] = $item['productName'];
    $orderToPush->OrderItems[0]['ItemNumber'] = $orderJSON['orderId'] . "-" . str_pad($orderItem, 2, '0', STR_PAD_LEFT);


    //
    // echo "\n\n+++ orderToPush +++\n";
    // var_dump($orderToPush);

    array_push($ordersToPush, (array) $orderToPush);

    $orderItem++;
  }


  $createOrdersData = [
    "orders" => json_encode($ordersToPush)
  ];

  $createOrders = post("https://eu-ext.linnworks.net//api/Orders/CreateOrders", $createOrdersData, "Authorization: {$l_token}\r\n");

  return $createOrders;

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
