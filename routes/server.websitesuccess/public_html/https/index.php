<?php

	header("Access-Control-Allow-Origin: *");

  if ($_GET['url']) {
    $url = urldecode($_GET['url']);
    $content = file_get_contents( $url );
    echo $content;
  } else if ($_POST['url']) {
    $url = $_POST['url'];
    $content = file_get_contents( $url );
    echo $content;
  } else {
    http_response_code(400);
  }
 
