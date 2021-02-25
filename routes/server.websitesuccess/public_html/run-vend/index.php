<?php
  //set up SOAPS
  date_default_timezone_set('Europe/London');
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  function logtofile($status, $string) {
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
      // print "Error occured : " . $curlinfo['http_code'] . "\n";
      // echo "Sent:\n";
      // echo $xmltosend;
      // echo "\nReturned:\n";
      $error = simplexml_load_string($curlexec)->children('soap', true)->Body->Fault->Reason->Text;
      // print($error);
      // print($curlexec);
      // print_r($curlinfo);
      // echo "\n\n";
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


 function init (){
   $siteId = "3228953";
   $username = "tech@websitesuccess.co.uk";
   $password = "DevTime69!";


   $request = $_SERVER;
   $webhooktype = $_POST['type'];
   $payload = json_decode( urldecode( $_POST['payload'] ) );
   if (
     !is_null( $payload->count ) ||
     !is_null( $payload->product->sku ) ||
     !is_null( $payload->product->handle )
   ) {
     $count = $payload->count;
     $variationcode = $payload->product->sku;
     $productcode = $payload->product->handle;
   }

   $getProduct = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
                <soap12:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap12=\"http://www.w3.org/2003/05/soap-envelope\">
                  <soap12:Body>
                    <Product_Retrieve xmlns=\"http://tempuri.org/CatalystDeveloperService/CatalystEcommerceWebservice\">
                      <username>$username</username>
                      <password>$password</password>
                      <siteId>$siteId</siteId>
                      <productCode>$productcode</productCode>
                    </Product_Retrieve>
                  </soap12:Body>
                </soap12:Envelope>";

   $product = new SimpleXMLElement(soapy($getProduct));
  //  var_dump (soapy($getProduct));
   $variations = $product->children('soap', true)->Body->children()->children()->children()->variations->ProductVariation;
   $productFields = $product->children('soap', true)->Body->children()->children()->children();
  //  echo $product->asXML();
    $foundVariation = false;
   foreach ($variations as $variation) {
     if ($variation->code == $variationcode) {
      //  echo $variation->asXml();
       $variation->inStock = $count;
       $foundVariation = true;
     }
   }
  //  echo $product->asXML();

  $productInfo = "";
   foreach ($productFields as $productField) {
     $productInfo .= $productField->asXml();
   }

   $updateProduct = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
      <soap12:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap12=\"http://www.w3.org/2003/05/soap-envelope\">
        <soap12:Body>
          <Product_UpdateInsert xmlns=\"http://tempuri.org/CatalystDeveloperService/CatalystEcommerceWebservice\">
            <username>$username</username>
            <password>$password</password>
            <siteId>$siteId</siteId>
            <productList>
              <Products>
                {$productInfo}
              </Products>
            </productList>
          </Product_UpdateInsert>
        </soap12:Body>
      </soap12:Envelope>";

    // echo $updateProduct;
if ($foundVariation) {
    $sendUpdate = soapy($updateProduct);

    echo $sendUpdate;

    $date = date("l jS \of F H:i:s");
    $successResponse = "
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
+
+   Success : done [{$date}]
+   $productcode : $variationcode
+
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    ";
    logtofile('success', $successResponse);
  } else {
  $date = date("l jS \of F H:i:s");
  $errorResponse = "
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
+
+   Error occured : the product exists but the variation doesn't [{$date}]
+   $productcode : $variationcode
+
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  ";
  echo $errorResponse;
  logtofile('error', $errorResponse);
  }

 }

 init();
