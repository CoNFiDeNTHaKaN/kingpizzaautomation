<?php
	header('Content-type: text/xml');
	header('Pragma: public');
	header('Cache-control: private');
	header('Expires: -1');
	header("Access-Control-Allow-Origin: *");
	
	function easyLog( $text ) {
		echo('<script>console.log("'. $text .'")</script>');
	}
	
	session_start();
	
	if ( !empty ($_GET['action'])) {
		if( $_GET['action'] == 'destroy') {
			session_destroy();	
	}}
	
	$property = '';
	if ( !empty($_GET['property'])) {
		global $property;
		$property = $_GET['property'];
	}
	
	$tokenage = file_get_contents("time.txt");
	$tokenfile = file_get_contents("token.txt");
	$token = base64_encode( $tokenfile );
	 
	$username = "borandborun"; 
	$password = "5dRs8sC.x";
	$datafeedID = "BORLANDBAPI";
	$request = 'http://webservices.vebra.com/export/BORLANDBAPI/v8/branch/23358/property/'.$property;

	if ( empty($_GET['property']) ) {
		$GLOBALS['request'] = "http://webservices.vebra.com/export/BORLANDBAPI/v8/branch/23358/property";	
	}
	
	function getToken() {
		$file = "headers/log	_" . date('Y-m-d_H-i-s', time() ) . '.txt' ;
		$fh = fopen($file, "w");

		global $username, $password, $request, $session_age;

		$tokench = curl_init($request);
		curl_setopt($tokench, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($tokench, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($tokench, CURLOPT_HEADER, 1); 
		curl_setopt($tokench, CURLOPT_FILE, $fh);
		curl_setopt($tokench, CURLOPT_NOBODY, 1); 
		curl_exec($tokench);
		curl_close($tokench);
		fclose($fh);

		$headers = file($file, FILE_SKIP_EMPTY_LINES);

		foreach ($headers as $headerLine) { 
			$line = explode(':', $headerLine);
			$header = $line[0];
			$value = trim($line[1]);
			if($header == "Token") {
				file_put_contents('token.txt', $value);
				$GLOBALS['token'] = base64_encode($value);
				file_put_contents('time.txt', time() );
			}
		}
	}
	
	if ((time() - $tokenage) > 3600) {
		getToken();
	}
	
	if( !empty($_GET['token'])) {
		global $token;
		file_put_contents('token.txt', $_GET['token']);
		$token = base64_encode($_GET['token']);
		$_SESSION['token'] = $token;
		
		file_put_contents('time.txt', time() );
	}
	
		$datach = curl_init($request);
		curl_setopt($datach, CURLOPT_HEADER, 0); 
		curl_setopt($datach, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$GLOBALS['token']));
		curl_setopt($datach, CURLOPT_RETURNTRANSFER, true );
		
		$curl = curl_exec($datach);
		$info = curl_getinfo($datach);
		
		
		/*if($info['http_code'] == '401') {
		} elseif ($info['http_code'] == '200') {
		}*/
		
		$xcurl = new SimpleXMLElement($curl);
		$xcurl->addChild('session_age', 'Approximately | ' . gmdate("i:s", (time() - $tokenage)) );
		$xcurl->addChild('token_used', $token . '_b64' );
		echo( $xcurl->asXML() );
		curl_close($datach);
		
?>