<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Basket;
use App\Order;
use App\MenuItem;
use Config;
use Stripe;
use Cookie;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Bschmitt\Amqp\Facades\Amqp;
use App\UserAddress;
use \App\Http\Middleware\PhoneVerified;
class OrderController extends Controller
{

	public function __construct()
	{

	}

  public function updateBasket (Request $request)
  {
    /*
    $cookieDuration = Config::get('settings.basketduration');
    if ($request->hasCookie('eko_basket_id')) {
      $basket_id = $request->cookie('eko_basket_id');
    } else {
      $might_already_exist = true;
      do {
        $new_hash = Str::random(32);
        $might_already_exist = Basket::where('hash', $new_hash)->exists();
        $basket_id = $new_hash;
      } while ($might_already_exist);
    }
    $basket = Basket::firstOrCreate(['hash'=>$basket_id]);
    

    $basket->contents = $request->customerOrderItems;
    $basket->restaurant_id = $request->restaurant_id;
    $basket->fulfilment_method = $request->fulfilment_method;

    $basket->save();
    return response('')->cookie('eko_basket_id', $basket->hash, $cookieDuration);
    */


    $basket= Basket::findOrFail($request->basket_id);
    $basket->contents=$request->customerOrderItems==null ? [] : $request->customerOrderItems;
    $basket->fulfilment_method=$request->fulfilment_method;
    $basket->save();
    return view('restaurants.basket', ['basket' => $basket])->render();
  }

  public function resumeBasket (Request $request)
  {
    if ($request->hasCookie('eko_basket_id')) {
      $basket_id = $request->cookie('eko_basket_id');
      $basket = Basket::where([
        'hash' => $basket_id,
        'restaurant_id' => $request->restaurant_id
      ]);
      $exists = $basket->exists();
      if ($exists) {
        $basket = $basket->first();
        $response = [
          'customerOrderItems' => $basket->contents,
          'fulfilmentMethod' => $basket->fulfilment_method
        ];
        return response()->json( $response );
      } else {
        return response('', 404);
      }
    }
    return response('', 404);
  }

  public function checkout (Request $request)
  {
    if (!Auth::check()) {
      return view('user.login');
    }
    
    if ($request->hasCookie('eko_basket_id')) {
      $basket_id = $request->cookie('eko_basket_id');
      $basket = Basket::where([
        'hash' => $basket_id
      ]);
      $exists = $basket->exists();
      if ($exists) {
        $basket = $basket->first();
        if($basket->fulfilment_method==null){
          return back()->withErrors(['Please select take away or delivery(click view basket if you are on mobile). If you are not able to select both, the restaurant is not currently taking any orders']);
        }
        return view('order-now.checkout')->with('basket', $basket);
      } else {
        return response('', 404);
      }
    } else {
        return response('', 404);
    }
    


  }

  public function confirm (Request $request)
  {
    if (!Auth::check()) {
      return view('user.login');
    }

    if ($request->hasCookie('eko_basket_id')) {
      $basket_id = $request->cookie('eko_basket_id');
      $basket = Basket::where([
        'hash' => $basket_id
      ]);
      $exists = $basket->exists();
      if ($exists) {
        $basket = $basket->first();
        $lastOrder=$basket->user->orders()->where('collection','0')->latest()->first();

        $availableTimes = [];
        $leadTime = ($basket->fulfilment_method === "delivery") ? $basket->restaurant->delivery_lead_time : $basket->restaurant->collection_lead_time;
        $earliestPossibleTime = time() + ($leadTime * 240 );
        $earliestPossibleTime = ($earliestPossibleTime - ($earliestPossibleTime % 300)) + 300;

        $nextPossibleTime = $earliestPossibleTime;
        $availableTimes[ date( 'G:i',$nextPossibleTime) ] = $nextPossibleTime;
        for ($i=1; $i<10; $i++) {
          $nextPossibleTime += 600;
          $availableTimes[ date( 'G:i',$nextPossibleTime) ] = $nextPossibleTime;
        }

        return view('order-now.purchase')->with([
          'basket' => $basket,
          'times' => $availableTimes,
          'lastOrder' => $lastOrder ?? 'none'
        ]);
      } else {
        return redirect()->route('home');
      }
    } else {
        return redirect()->route('home');
    }
  }

  public function pay (Request $request)
  {
    if (!Auth::check()) {
      return view('user.login');
    }

    $user = Auth::user();


    // validate data
    $validatedData = $request->validate([
      'first_name' => "required|min:2",
      'last_name' => "required|min:2",
	    'desired_time' => "required",
      'delivery.address_line_1' => "filled",
      'delivery.city' => "filled",
      'delivery.postcode' => "filled",

      'card_address_line_1' => Rule::requiredIf(function () use ($request,$user) {
        return (count($user->addresses)==0 && $request->paymentMethod=='card');
	   }),
      'card_address_city' =>  Rule::requiredIf(function () use ($request,$user) {
        return (count($user->addresses)==0 && $request->paymentMethod=='card');
	   }),
      'card_address_postcode' =>  Rule::requiredIf(function () use ($request,$user) {
        return (count($user->addresses)==0 && $request->paymentMethod=='card');
	   }),

    ]);


      $basket = Basket::where([
        'id' => $request->basket_id,
        'hash' => $request->basket_hash,
      ])->first();

    // TODO: check if delivery address is within range, if delivering

    $order = Order::create([
      'restaurant_id' => $basket->restaurant->id,
      'user_id' => $user->id,
      'basket_id' => $basket->id,
      'paid' => false,
      'payment_type' => $request->paymentMethod,
      'collection' => ($basket->is_collection),
      'desired_time' => $request->desired_time,
	  'notes' => $request->notes,
      'order_status_id' => 3
    ]);
    if ($basket->fulfilment_method === "delivery") {
		if($request->delivery!=null){
			$order->update([
			'delivery_line1' => $request->delivery['address_line_1'],
			'delivery_line2' => $request->delivery['address_line_2'],
		'	delivery_city' => $request->delivery['city'],
			'delivery_postcode' => $request->delivery['postcode'],
			]);
		}else{
			$address=UserAddress::where('id',$request->addressid)->firstOrFail();
			$order->update([
			'delivery_line1' => $address->address_line1,
			'delivery_line2' => $address->address_line2,
			'delivery_city' => $address->city,
			'delivery_postcode' => $address->postcode,
			]);
		}
    }


    // check is user paying card or cash
    if ($request->paymentMethod === "card") {
		
		if($request->has('billingaddressid')){
			$address=UserAddress::where('id',$request->billingaddressid)->firstOrFail();
			$order->update([
			'billing_line1' => $address->address_line1,
			'billing_line2' => $address->address_line2,
			'billing_city' => $address->city,
			'billing_postcode' => $address->postcode,
			]);
		}else{
			$order->update([
			'billing_line1' => $request->card_address_line1,
			'billing_line2' => $request->card_address_line2,
			'billing_city' => $request->card_address_city,
			'billing_postcode' => $request->card_address_postcode,
			]);
		}
		
      // check is user has existing stripe id
      if (is_null($user->stripe_customer_id)) {
        // else create customer
        $stripeCustomer = Stripe::customers()->create([
          'email' => $user->email,
          'name' => $user->name,
          'phone' => $user->telephone
        ]);

        $user->stripe_customer_id = $stripeCustomer['id'];
        $user->save();
      }

      $card = Stripe::cards()->create($user->stripe_customer_id, $request->stripe_token);

      $user->update([
        'stripe_card_id' => $card['id'],
        'stripe_card_last4' => $card['last4'],
        'stripe_card_brand' => $card['brand'],
      ]);


      $discounted=$basket->discounted_total;

      $intent = Stripe::paymentIntents()->create([
        'amount' => $discounted['discounted_total'],
        'currency' => 'gbp',
        'customer' => $user->stripe_customer_id,
        'payment_method' => $card['id'],
        'confirm' => true,
        'metadata' => [
          'Basket ID' => $basket->id,
          'Restaurant' => $basket->restaurant->name . " | " . $basket->restaurant->id,
          'Order ID' => 'WEB' . $order->id
        ]
      ]);

      if ($intent['status'] === "succeeded") {
        $order->update([
          'paid' => true,
          'payment_id' => $intent['id']
        ]);
      }

    }

    Cookie::forget('eko_basket_id');
   // Mail::to('tech@websitesuccess.co.uk')->send(new \App\Mail\OrderAlertForRestaurant($order, $user, $basket));
    Mail::to('order@eatkebabonline.co.uk')->send(new \App\Mail\OrderReceiptForCustomer($order, $user, $basket));
  $discounted=$basket->discounted_total;
	Amqp::publish('', json_encode(['order' => $order , 'amount' => $discounted['discounted_total'] , 'discounted' => $basket->discounted_total, 'total_orders' => count($user->orders)]) , ['queue' => env('RABBITMQ_QUEUE_NAME', 'order_queue')]);
    return redirect()->route('restaurants.thanks',['id' => $order->id]);
  }

  public function resetLocation(Request $request) {
    Cookie::queue(Cookie::forget('eko_postcode'));
    return redirect('home');
  }

  public function thanks(Request $request, $id) {
    Cookie::queue(Cookie::forget('eko_basket_id'));
    $order = Order::find($id);
    return view('order-now.thanks')->with(['order' => $order]);
  }

  public function orderModal($id){
    $item=MenuItem::findOrFail($id);
    return response()->json($item);
  }

}
