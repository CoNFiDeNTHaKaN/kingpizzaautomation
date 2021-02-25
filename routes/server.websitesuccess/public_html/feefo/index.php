<?php
	header("Access-Control-Allow-Origin: *");
	header('Pragma: public');
	header('Cache-control: private');
	header('Expires: -1');
  ini_set('default_mimetype', '');
  ini_set('display_errors', 'on');
  ini_set('log_errors', 'on');
  ini_set('error_log', 'error_log');
  ini_set('error_reporting', '-1');
    error_reporting(-1);
  $method = $_SERVER['REQUEST_METHOD'];
  $merchants = array(
    'simply-african-food-ltd' => array(
      'host' => 'ftp2.feefo.com',
      'user' => 'simply-african-food-ltd',
      'pass' => 'YYPORnjyNKU',
      'port' => '21',
        'siteid' => '1956382',
        'siteuser' => 'tech@websitesuccess.co.uk',
        'sitepass' => 'DevTime69!',
        'apiurl' => 'https://safood.worldsecuresystems.com/catalystwebservice/catalystcrmwebservice.asmx'
    )
  );
  if ($method === 'GET') {
    if (!empty($_REQUEST['merchantidentifier']) && !empty($_REQUEST['json'])) {

      $url = "http://cdn2.feefo.com/api/xmlfeedback?merchantidentifier={$_REQUEST['merchantidentifier']}&json={$_REQUEST['json']}";
      $curlies = curl_init();
      curl_setopt($curlies, CURLOPT_URL, $url);
      curl_setopt($curlies, CURLOPT_RETURNTRANSFER, true);
			$curlexec = curl_exec($curlies);


      if ($curlexec) {
	      if ($_REQUEST['json'] === "true") {
	        header('Content-type : application/json');
	        http_response_code(200);
	        print ($curlexec);
	      } else {
	        header('Content-type : text/xml');
	        http_response_code(200);
	        print ($curlexec);
	      }
			} else {
	        http_response_code(500);
	      }
    } else {
      http_response_code(400);
    }
  } else if ($method === 'POST') {


		$merchantdetails = $merchants['simply-african-food-ltd'];
		if (!empty($_REQUEST['ObjectID'])) {
		  $orderId = $_REQUEST['ObjectID'];
		?>
<?php
  $requestorder = <<<XML
<?xml version="1.0" encoding="utf-8"?>
    <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
      <soap12:Body>
        <Order_Retrieve xmlns="http://tempuri.org/CatalystDeveloperService/CatalystCRMWebservice">
          <username>{$merchantdetails['siteuser']}</username>
          <password>{$merchantdetails['sitepass']}</password>
          <siteId>{$merchantdetails['siteid']}</siteId>
          <orderId>$orderId</orderId>
        </Order_Retrieve>
      </soap12:Body>
    </soap12:Envelope>
XML;
?>
		<?php
		    function soapy($xmltosend) {
		      global $merchantdetails;
		      $curlobj = curl_init();
		      $headers = array(
		        "Content-type: application/soap+xml;charset=\"utf-8\"",
		        "Accept: text/xml",
		        "Content-length: ".strlen($xmltosend)
		      );
		      $curlopts = array(
		        CURLOPT_URL => $merchantdetails['apiurl'],
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

		    $orderDetails = soapy($requestorder);
		    $orderxml = simplexml_load_string($orderDetails);
		    $orderarray = clone $orderxml->children('soap', true)->Body->children()->children()->children();

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
          <username>{$merchantdetails['siteuser']}</username>
          <password>{$merchantdetails['sitepass']}</password>
          <siteId>{$merchantdetails['siteid']}</siteId>
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

	    foreach ($customerarray->phoneNos->PhoneNo as $phonenumber) {
	      if ($phonenumber->phoneNoTypeID == '7') { // email
	        $emailaddress = clone $phonenumber->phoneNo;
	      }
	    }

		?>
<?php
			$searchcode = strtoupper($orderarray->products->Product->productName);
            $feefodate = substr($orderarray->invoiceDate, 0, 10);
			$feefoXML = <<<XML
            <items>
				   <item><name>$customerarray->fullName</name>
				      <email>$emailaddress</email>
				      <date>$feefodate</date>
				      <description>{$orderarray->products->Product->productName}</description>
				      <merchant_identifier>{$_REQUEST['merchantidentifier']}</merchant_identifier>
				      <product_search_code>$searchcode</product_search_code>
				      <order_ref>{$orderarray->orderId}</order_ref>
							<customer_ref>$orderarray->entityId</customer_ref>
				   </item>
				</items>
XML;
?>
<?php
			$ftpconnection = ftp_connect( $merchantdetails['host'], $merchantdetails['port'] ) or die ("Couldn't connect, please check ftp details");
			$ftplogin = ftp_login($ftpconnection, $merchantdetails['user'], $merchantdetails['pass']) or die ("Connected to server, but authentication failed, please check credentials");
			$tmpfile = tmpfile();
			fwrite($tmpfile, $feefoXML);
			$remotename = "Order_{$orderarray->orderId}_{$orderarray->createDate}.xml";
			fseek($tmpfile, 0);
			$putfile = ftp_fput($ftpconnection, $remotename, $tmpfile, FTP_BINARY);
            if ($putfile) {
                echo "$feefoXML";
            }
  }}

?>
