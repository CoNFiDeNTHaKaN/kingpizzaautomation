<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

  define('APPLICATION_NAME', 'eFusion Amazon Orders');
  define('APPLICATION_VERSION', '0.1');


header('Content-Type: application/json');

  $siteId = "1897384";
  $user = "tech@websitesuccess.co.uk";
  $pass = "DevTime69";
  $curlurl = "https://sendabunch.worldsecuresystems.com/CatalystWebService/CatalystCRMWebservice.asmx";


  $linnworksId = "3872cd22-6491-4fdd-8ea3-b5de824f741c";
  $linnworksSecret = "30e113d2-ae33-4dcf-a65d-a6b5646dd255";
  $linnworksToken = "f3643c162c751f2621fb84642341d098";

  class OrderToPush
  {
    public $Site = "Sendabunch.co.uk";
    public $OrderState = "None";
    public $PaymentStatus = "Paid";
    public $Source = "eFusion";
    public $SubSource = "Sendabunch";
    public $Currency = "GBP";
    public $OrderItems = [];

function __construct() {
    $this->OrderItems[0] = [
        "TaxCostInclusive" => true,
        "UseChannelTax" => true,
        "IsService" => false
      ];
    }
  }

?>
