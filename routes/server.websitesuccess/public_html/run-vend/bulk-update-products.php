<?php
  //set up SOAPS
  date_default_timezone_set('Europe/London');
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  VEND_TOKEN = "BQoSDDIat2f8bCOn1uQ3N_1QxqXygPWaq0Cnl591";

  function logtofile($status, $string) {
    // TODO: log files daily, delete over 10 days old
    if ($status == "success") {
        $filename = "./success.log";
    } else if ($status == "error") {
        $filename = "./error.log";
    }
    file_put_contents($filename, $string, FILE_APPEND);
  }

  function soapy($xmltosend) {
    $payload = json_decode( urldecode( $_POST['payload'] ) );
    $count = $payload->count;
    $variationcode = $payload->product->sku;
    $productcode = $payload->product->handle;
    $soap_listretrieve = "https://theruncompany.worldsecuresystems.com/catalystwebservice/catalystecommercewebservice.asmx";
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
      $error = simplexml_load_string($curlexec)->children('soap', true)->Body->Fault->Reason->Text;
      $date = date("l jS \of F H:i:s");
      $errorResponse = "
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
+
+   Error occured : {$curlinfo['http_code']} [{$date}]
+   {$error}
+   $productcode : $variationcode
+
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

      ";
      echo $errorResponse;
      logtofile('error', $errorResponse);
      die();
    } else {
      return $curlexec;
    }
    curl_close($curlobj);
  }

  function vendcurl($geturl) {

  }


 function init () {
   $siteId = "3228953";
   $username = "tech@websitesuccess.co.uk";
   $password = "DevTime69!";

   $request = $_SERVER;
   $webhooktype = $_POST['type'];
   $payload = json_decode( urldecode( $_POST['payload'] ) );
   dd($payload);

   // TODO: get all pages of products


   // TODO: iterate Products
     // TODO: structure product for eFusion
     // TODO: push to eFusion

 }

 init();
