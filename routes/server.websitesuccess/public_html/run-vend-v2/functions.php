<?php

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  const FILE_LOCATION = "./db.json";

  function db_getAll () {
    if (!is_file(FILE_LOCATION)) {
      file_put_contents(FILE_LOCATION, '{}');
    }
    $file = file_get_contents(FILE_LOCATION);
    $json = json_decode($file) ?: json_decode('{}');
    return $json;
  }

  function db_get($key) {
    $db = db_getAll();
    return $db->{$key};
  }

  function db_set($key, $value) {
    $db = db_getAll();
    $db->{$key} = $value;
    return (bool) file_put_contents(FILE_LOCATION, json_encode($db));
  }

  function log_toFile($status, $string) {
    // TODO: log files daily, delete over 10 days old
    if ($status == "success") {
        $filename = "./success.log";
    } else if ($status == "error") {
        $filename = "./error.log";
    }
    file_put_contents($filename, $string, FILE_APPEND);
  }

  function sxml_append(SimpleXMLElement $to, SimpleXMLElement $from) {
    $toDom = dom_import_simplexml($to);
    $fromDom = dom_import_simplexml($from);
    $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
  }


    function efusionSoapy($xmltosend) {
      $soap_listretrieve = "https://theruncompanycopy.worldsecuresystems.com/catalystwebservice/catalystecommercewebservice.asmx";
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
        var_dump($xmltosend);
        var_dump($curlinfo);
        var_dump($curlexec);
        // $error = simplexml_load_string($curlexec)->children('soap', true)->Body->Fault->Reason->Text;
        $date = date("l jS \of F H:i:s");
        $errorResponse = "
  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  +
  +   Error occured : {$curlinfo['http_code']} [{$date}]

  +   {$xmltosend}
  +
  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        ";
        log_toFile('error', $errorResponse);
        return false;
      } else {
        return $curlexec;
      }
      curl_close($curlobj);
    }

    function vendGet ($getUrl) {
      global $VEND_TOKEN;
      $start = microtime(true);
      $curlobj = curl_init();
      $auth = "Authorization: Bearer " . $VEND_TOKEN;
      $headers = array(
        "Content-Type: application/json",
        "Accept: application/json",
        $auth
      );
      $curlopts = array(
        CURLOPT_URL => $getUrl,
        CURLOPT_HEADER => 0,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => TRUE
      );
      curl_setopt_array($curlobj, $curlopts);
      $curlexec = curl_exec($curlobj);
      $curlinfo = curl_getinfo($curlobj);
      $time_elapsed_secs = microtime(true) - $start;
      $sleepFor = (int) ((1-$time_elapsed_secs) * 1000000);
      if ($sleepFor > 30) {
        usleep($sleepFor);
      }
      return json_decode($curlexec);
    }

?>
