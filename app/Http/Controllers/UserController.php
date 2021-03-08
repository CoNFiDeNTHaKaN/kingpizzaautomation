<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\UserAddress;
use Mews\Captcha;

class UserController extends Controller
{
    public function register () {
      return view('user.register',['captcha' => captcha_img()]);
    }
    public function registerSubmit (Request $request) {
      $validatedData = $request->validate([
        "first_name" => "required|min:2",
        "last_name" => "required|min:2",
		    //"captcha" => "required|captcha",
        "email" => [
          "required",
          "email",
          Rule::unique('users')
        ],
        "password" => "required",
      ],
	  $messages = [
		'captcha' => 'The captcha is wrong!',
	  ]
	  );
	  

      if($user = User::create([
          'first_name' => $request->first_name,
          'last_name' => $request->last_name,
          'email' => $request->email,
          'password' => Hash::make($request->password)
      ])) {
        Auth::login($user);
        return redirect()->route('home');
      }
      


      if($user->exists() && $user->wasRecentlyCreated) {
        Auth::login($user, true);
        if ($request->has('redirectTo')) {
          return redirect($request->redirectTo);
        } else {
          return redirect()->route('user.account');
        }
      } else {
        return back()->withErrors(['alert', 'Something went wrong, please try again or contact support.']);
      }
    }

    public function login () {
      return view('user.login');
    }
    public function loginSubmit (Request $request) {
      $validatedData = $request->validate([
        "email" => "required|email|exists:users",
        "password" => "required",
      ]);

      if (Auth::attempt($request->only('email','password'))) {
        if (empty($request->input('url'))) {
          return redirect()->route('home');
        } else {
          return redirect( $request->input('url') );
        }
      } else {
        return back()->withErrors(['Those details don\'t match our records']);
      }
    }

    public function resetPassword () {
      return view('user.reset-password');
    }
    public function resetPasswordSubmit () {
      return view('user.reset-password');
    }

    public function logout () {
      Auth::logout();
      return redirect()->route('home')->withErrors('alert', 'You have been successfully logged out.');
    }

    public function account () {
      return view('user.account');
    }
    public function orders () {
      $userOrders = Auth::user()->orders()->orderByDesc('created_at')->get();
      return view('user.orders', ["orders" => $userOrders]);
    }
    public function editInfo () {
      return view('user.edit-info');
    }
    public function editInfoSubmit (Request $request) {
      $validatedData = $request->validate([
        "first_name" => "required|min:2",
        "last_name" => "required|min:2",
        "email" => [
          "required",
          "email",
          Rule::unique('users')->ignore(Auth::id())
        ],
      ]);

      $user = Auth::user();
      $user->update([
          'first_name' => $request->first_name,
          'last_name' => $request->last_name,
          'email' => $request->email,
          'contact_number' => $request->contact_number,
      ]);

      if ($request->password!="") {
        $user->update([
          'password' => Hash::make($request->password)
        ]);
      }

      return back()->with('success','Your information was successfully updated.');
    }

    public function savedCards () {
      return view('user.saved-cards');
    }
    public function deleteCard () {
      $user = Auth::user();
      $user->update([
        "stripe_card_id" => null,
        "stripe_card_brand" => null,
        "stripe_card_last4" => null,
      ]);
      return redirect()->route('user.savedCards')->with('success','Your information has been cleared.');
    }

	 public function editAddress ($id) {
	  $address=UserAddress::findOrFail($id);
      return view('user.edit-address' , ['address' => $address]);
    }
	
	public function updateAddress (Request $request) {
      $address=UserAddress::findOrFail($request->addressid);
      if ($address->user_id == Auth::id()) {
          $address->update([
            "name" => $request->name,
            'address_line1' => $request->line1,
            'address_line2' => $request->line2,
            'city' => $request->city,
            'postcode' => $request->postcode,
    ]);
      
          return redirect(route('user.savedAddresses'))->with('success', 'Address updated successfully.');
      }else return redirect(route('user.savedAddresses'));
    }

    public function savedAddresses () {
      return view('user.saved-addresses');
    }
	
	public function addAddress(Request $request){
		
		$validatedData = $request->validate([
			"name" => "required",
			"line1" => "required",
			"line2" => "required",
			"city" => "required",
			"postcode" => "required", ]);
		
		$user=Auth::user();
		
		$userAddress=UserAddress::create([
			'user_id' => $user->id,
			"name" => $request->name,
			'address_line1' => $request->line1,
			'address_line2' => $request->line2,
			'city' => $request->city,
			'postcode' => $request->postcode,
		]);
		 return back()->with('success','New address created successfully.');
	}
	
    public function deleteAddress ($id) {
     $address=UserAddress::findOrFail($id);
        if ($address->user_id == Auth::id()) {
            $address->delete( );
            return redirect()->route('user.savedAddresses')->with('success', 'Your information has been cleared.');
        }else return redirect()->route('user.savedAddresses');
      
    }

}
