<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class EmailController extends Controller
{
    public function send (Request $r) {
      // $email = json_decode(base64_decode($r->email));

      // $email = (object) $r['email'];

      $email = json_decode(json_encode($r['email']), FALSE);

      // var_dump($email);

      // return dd($email);

      Mail::send("emails.{$email->layout}", ['v'=>$email->params], function ($m) use ($email) {
        if (isset($email->fromName) && isset($email->fromEmail)) {
          $m->from( $email->fromEmail, $email->fromName);
        } else {
          $m->from('donotreply@thecuriosityapproach.co.uk', 'The Curiosity Approach Member\'s Portal');
        }
        $m->to($email->to)->subject($email->subject);
      });

      return response()->json(true);
    }
}
