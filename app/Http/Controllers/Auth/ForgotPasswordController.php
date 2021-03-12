<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request){
		$message="We send you an email to reset your password. <br> <br>
      
      ⚠️ Warning <br>
      Some times because of your Internet connection the email take time to reach your inbox. <br>
      
      If is take more then two minutes please reset your password again. <br>
      
      Thank you for your passion.  <br>
      
      If you have login problem please do not hesitate to contact us <br> 01243 822 822 <br>
      
      Eat Kebab online Management <br>";
		$this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

       return Password::RESET_LINK_SENT
                    ? redirect()->route('user.resetPassword')->with('message' , $message)
                    : redirect()->route('user.resetPassword')->with('message' , 'Something went wrong. Please try again.');
	}

}
