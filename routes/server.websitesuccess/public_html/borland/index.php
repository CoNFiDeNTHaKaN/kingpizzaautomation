<?php

	header("Access-Control-Allow-Origin: *");
  ini_set('display_errors', '0');
  ini_set('log_errors', '1');
  error_reporting(E_ALL);

  $ftplogin = array(
    'host' => "FTP.EXPERTAGENT.CO.UK",
    'user' => "BorlandandBorland",
    'pass' => "QxZchfvN"
  );

  if (isset($_GET['file'])) {
    $filetoget = $_GET['file'];
  } else {
    http_response_code(400);
    die('Please use file parameter to request file or set file as "list" to return all files in root directory');
  }

  // open connection
  $ftpstream = ftp_connect($ftplogin['host']);
  if (!$ftpstream) {
    http_response_code(202);
    die('ERROR : cannot connect');
  }
  // log in against connection
  $ftplogin = ftp_login( $ftpstream, $ftplogin['user'], $ftplogin['pass'] );
  ftp_pasv($ftpstream, true);
  if (!$ftplogin)  {
    http_response_code(202);
    die('ERROR : cannot login');
  }

  if ($filetoget == 'list') {
    header('Content-Type: text/plain');
    print(json_encode(ftp_nlist($ftpstream,"")));
    die();
  }

  ob_start();
  $tmpfile = 'php://output';

  if (ftp_get($ftpstream, $tmpfile, $filetoget, FTP_ASCII)) {
		http_response_code(200);
		header('Content-Type: text/xml');
    ob_end_flush();
  } else {
  	http_response_code(202);
    die ( "ERROR : cannot get file" );
  }


?>
