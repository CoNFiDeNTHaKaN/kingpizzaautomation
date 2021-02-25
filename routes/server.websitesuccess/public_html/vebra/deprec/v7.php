<?php
	//http_response_code(200);
	header('Content-type: text/xml');
	header('Pragma: public');
	header('Cache-control: private');
	header('Expires: -1');
	header("Access-Control-Allow-Origin: *");
	
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	
	function easyLog( $text ) {
		echo('<script>console.log("'. $text .'")</script>');
	}
	
	session_start();
	
	if ( !empty ($_GET['action'])) {
		if( $_GET['action'] == 'destroy') {
			session_destroy();	
			//echo 'destroyed<br>';
	}}
	
	$property = '';
	if ( !empty($_GET['property'])) {
		global $property;
		$property = $_GET['property'];
	}
	$token = '';
	if ( !empty($_SESSION['token'])) {
		global $property;
		$token = $_SESSION['token'];
	}
	$session_stamp = '';
	if ( !empty($_SESSION['stamp'])) {
		global $property;
		$session_stamp = $_SESSION['stamp'];
	}
	
	// API CREDENTIALS //
	$username = "borandborun"; 
	$password = "5dRs8sC.x";
	$datafeedID = "BORLANDBAPI";
	$request = 'http://webservices.vebra.com/export/BORLANDBAPI/v8/branch/23358/property/'.$property;

	if ( empty($_GET['property']) ) {
		$GLOBALS['request'] = "http://webservices.vebra.com/export/BORLANDBAPI/v8/branch/23358/property";	
	}
	if ( !empty($_GET['other']) ) {
		$GLOBALS['request'] = $_GET['other'];	
	}
	
	if ( empty($_SESSION['stamp']) ) { // if not timestamp set then current session not open 
		$_SESSION['stamp'] = time();
		$session_stamp = $_SESSION['stamp'];
	} 
	
	$session_age = time() - $session_stamp;
	
	//echo($token.' token <br>'. time() .' time <br>' . $session_stamp.' stamp <br>'.$session_age.' age <br>');
	
	// get token
	function getToken() {
				//echo( 'make a token' );
		$file = "headers_" . date('Y-m-d_H-i-s', time() ) . '.txt' ;
		$fh = fopen($file, "w");
				//echo '<a target="_blank" href="/'. $file. '">headers</a> <br>'. session_id().'<br>';
	
	
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
		
		foreach ($headers as $headerLine) { //for each line of the array explode the line by ':' (Seperating the header name from its value)
		
			$line = explode(':', $headerLine);
			$header = $line[0];
			$value = trim($line[1]);
			//If the request is successful and we are returned a token
			if($header == "Token") {
				$GLOBALS['token'] = base64_encode($value);
							//echo 'token - ' . $GLOBALS['token'];
				$_SESSION['token'] = $GLOBALS['token'];
			}
		}
		$_SESSION['stamp'] = time();
		
		$session_age = 0;
	}
	//if not token set OR if timestamp was more than 1 hour ago, get new token
	if ( empty( $token ) || $session_age > 3600 ) {
		getToken();	
					//echo 'asdfasdf';
	}
	
	//manually set token
	if( !empty($_GET['token'])) {
		global $token;
		$token = base64_encode($_GET['token']);
		$_SESSION['token'] = $token;
		$_SESSION['stamp'] = time();
		$GLOBALS['session_age'] = 0;
	}
	
	
	//echo '<hr>';
	$datach = curl_init($request);
		curl_setopt($datach, CURLOPT_HEADER, 0); 
		curl_setopt($datach, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$GLOBALS['token']));
		curl_setopt($datach, CURLOPT_RETURNTRANSFER, true );
		
		//Execute the curl session
		$curl = curl_exec($datach);
		//Store the curl session info/returned headers into the $info array
		$info = curl_getinfo($datach);
		
		
		//Check if we have been authorised or not
		if($info['http_code'] == '401') {
					echo 'Session '. $session_age;
		} elseif ($info['http_code'] == '200') {
					//echo '200';
		}
		
					//echo '<pre>';
					//print_r($info);
					//echo '</pre>';
					
		$xcurl = new SimpleXMLElement($curl);
		$xcurl->addChild('session_age', 'Approximately | ' . gmdate("i:s", $session_age) );
		$xcurl->addChild('token_used', $token . '_b64' );
		echo( $xcurl->asXML() );
		//echo ("<info><token_age>{$session_age}</token_age></info>");
		//Close the curl session
		curl_close($datach);
		
	
?>