<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Cookie;
use Config;
use App\Restaurant;
use Illuminate\Support\Facades\Auth;
use App\Basket;
use App\Rating;


class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        if (empty($request->postcode)) {
            if ($request->hasCookie('eko_postcode')) {
                $existingPostcode  = $request->cookie('eko_postcode');
                $request->request->add(['postcode' => $existingPostcode]);
                return redirect()->route('restaurants.list', $request->request->all());
            }
            return redirect('home')->withErrors('Please enter your postcode to search Eat Kebab Online.');
        } else {
            $cookieDuration = Config::get('settings.basketduration');
            Cookie::queue('eko_postcode', $request->postcode, $cookieDuration);
            $postcodeDetails = UtilityController::getPostcode($request->postcode);

            if (!$postcodeDetails || ($postcodeDetails->match_type !== "unit_postcode")) {
                return redirect()->route('home')->withErrors('Sorry we couldn\'t locate that postcode');
            }

            $userLat = $postcodeDetails->data->latitude;
            $userLng = $postcodeDetails->data->longitude;

            $restaurants = Restaurant::withinRange($userLat, $userLng)->get();

           // $restaurants = Restaurant::PostCode($request->postcode);

            return view('restaurants.list-view', ['restaurants'=>$restaurants]);
        }
        return view('restaurants.list-view');
    }


    public function detail(Request $request, $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->first();
        if (is_null($restaurant)) {
            abort(404);
        }

        if(Auth::check()){ 
            if($request->hasCookie('eko_basket_id')){
                $basket=Basket::where('hash', $request->cookie('eko_basket_id'))->first();
                $basket->update(['fulfilment_method' => null]);
                if($basket->restaurant->id!=$restaurant->id){
                    $might_already_exist = true;
                do {
                  $new_hash = Str::random(32);
                  $might_already_exist = Basket::where('hash', $new_hash)->exists();
                } while ($might_already_exist);
                $basket=Basket::create([
                    'hash' => $new_hash,
                    'contents' => [],
                    'user_id' => Auth::id(),
                    'restaurant_id' => $restaurant->id,
                    
                ]);
                }
            }
            else{
                $might_already_exist = true;
                do {
                  $new_hash = Str::random(32);
                  $might_already_exist = Basket::where('hash', $new_hash)->exists();
                } while ($might_already_exist);
                $basket=Basket::create([
                    'hash' => $new_hash,
                    'contents' => [],
                    'user_id' => Auth::id(),
                    'restaurant_id' => $restaurant->id,
                    
                ]);
            }
        }else{
            if($request->hasCookie('eko_basket_id')){
                $basket=Basket::where('hash', $request->cookie('eko_basket_id'))->first();
                if($basket->restaurant->id!=$restaurant->id){
                    $might_already_exist = true;
                do {
                  $new_hash = Str::random(32);
                  $might_already_exist = Basket::where('hash', $new_hash)->exists();
                } while ($might_already_exist);
                $basket=Basket::create([
                    'hash' => $new_hash,
                    'contents' => [],
                    'restaurant_id' => $restaurant->id,
                    
                ]);
                }
            }
            else{
                $might_already_exist = true;
                do {
                  $new_hash = Str::random(32);
                  $might_already_exist = Basket::where('hash', $new_hash)->exists();
                } while ($might_already_exist);
                $basket=Basket::create([
                    'hash' => $new_hash,
                    'contents' => [],
                    'restaurant_id' => $restaurant->id,
                    
                ]);
            }
        }
        $cookieDuration = Config::get('settings.basketduration');
        Cookie::queue('eko_basket_id', $basket->hash, $cookieDuration);
        return view('restaurants.detail', ['restaurant' => $restaurant,
                                            'basket' => $basket]);
    }

    public function review(Request $request){
        $restaurant = Restaurant::where('slug', $request->slug)->first();
        $request->validate([
            'rating' => 'required',
            'title' => 'required',
            'content' => 'required'
        ]);
        Rating::create([
            'title' => $request->title,
            'content' => $request->content,
            'rating' => $request->rating,
            'restaurant_id' => $restaurant->id,
            'user_id' => Auth::id()
        ]);

        return $this->detail($request,$request->slug);
    }
}
