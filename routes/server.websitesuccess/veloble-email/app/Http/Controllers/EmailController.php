<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class EmailController extends Controller
{
    public function send (Request $r) {
      $email = json_decode(base64_decode($r->email));

      Mail::send("emails.$email->layout", ['v'=>$email->params], function ($m) use ($email) {
        if (isset($email->fromName) && isset($email->fromEmail)) {
          $m->from( $email->fromEmail, $email->fromName);
        } else {
          $m->from('donotreply@veloble.com', 'Veloble');
        }
        $m->to($email->to)->subject($email->subject);
      });
    }
}
