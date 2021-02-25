<?php 

	header("Access-Control-Allow-Origin: *");
	header('Content-type: text/xml');
	header('Pragma: public');
	header('Cache-control: private');
	header('Expires: -1');
	
	
	if ( !empty ($_POST['user'])) {
		if( $_POST['user'] != 'james@websitesuccess.co.uk') {
			http_response_code(401);
			die();
			
	}} else {
		http_response_code(401);
		die();
		}
	
	if ( !empty ($_POST['pass'])) {
		if( $_POST['pass'] != 'Barnham01!') {
			http_response_code(401);
			die();
	}} else {
		http_response_code(401);
		die();
	}
	
	
	$gettoken = curl_init('https://websitesuccess.api.affinitylive.com/oauth2/v0/token.xml');
	//curl_setopt($gettoken, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	//curl_setopt($gettoken, CURLOPT_USERPWD, 'Basic NWYxOWViYTUzNkB3ZWJzaXRlc3VjY2Vzcy5hZmZpbml0eWxpdmUuY29tOm9laGZZfmw1ODRDbUgzbndBZi5XcE1maHZBUmNwM0sw');
	curl_setopt($gettoken, CURLOPT_POSTFIELDS, 'client_id=5f19eba536@websitesuccess.affinitylive.com&client_secret=oehfY~l584CmH3nwAf.WpMfhvARcp3K0&grant_type=client_credentials');
	curl_setopt($datach, CURLOPT_HEADER, 0); 
	curl_setopt($datach, CURLOPT_RETURNTRANSFER, true );
	
	$curl = curl_exec($gettoken);

	curl_close($gettoken);

?>