<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;

class UtilityController extends Controller
{
    public function getAddresses (Request $request) {
      $apiKey = Config::get('services.getaddress.key');
      $postcode = $request->postcode;
      $lookupUrl = "https://api.getaddress.io/find/{$postcode}?api-key={$apiKey}";

      if (empty($postcode) || (strlen($postcode) < 5)) {
        abort(400);
      }

      try {
        $response = file_get_contents($lookupUrl);

        if ($response) {
          abort(400);
        }

        return response()->json( json_decode( $response ) );
      } catch (Exception $e) {
        abort(400);
      }

    }

    public static function getPostcode ($postcode) {
      $postcode = urlencode($postcode);
      $lookupUrl = "http://api.getthedata.com/postcode/{$postcode}";
      $response = json_decode(file_get_contents($lookupUrl));
      if ($response->status == 'no_match') {
        return false;
      } else {
        return $response;
      }
    }
}
