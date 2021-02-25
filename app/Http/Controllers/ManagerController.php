<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use App\User;
use App\Restaurant;
use App\Order;
use Carbon\Carbon;

class ManagerController extends Controller
{
    public function index()
    {
        return view('manager.main');
    }

    public function register()
    {
        return view('manager.register');
    }

    public function registerSubmit(Request $request)
    {
        // validate form data
        $validatedData = $request->validate([
        "first_name" => "required|min:2",
        "last_name" => "required|min:2",
        "email" => [
          "required",
          "email",
          Rule::unique('users')->ignore(2)
        ],
        "password" => "required",
        "restaurant.name" => "required",
        "restaurant.description" => "required",
        "restaurant.contact_number" => "required",
        "restaurant.opening_hours" => "required",
        "restaurant.order_hours" => "required",
        "restaurant.delivery_hours" => "required",
        "restaurant.collection_waiting_time" => "required|numeric",
        "restaurant.delivery_waiting_time" => "required|numeric",
        "restaurant.address_line_1" => "required",
        "restaurant.address_city" => "required",
        "restaurant.address_county" => "required",
        "restaurant.address_postcode" => "required",
        "restaurant.delivery_minimum" => "required|numeric",
        "restaurant.delivery_fee" => "required|numeric",
        "restaurant.logo" => "required|mimes:jpeg,png,jpg",
        "restaurant.cover_image" => "mimes:jpeg,png,jpg",
      ]);

        $user = User::create([
          'first_name' => $request->first_name,
          'last_name' => $request->last_name,
          'email' => $request->email,
          'password' => Hash::make($request->password)
      ]);

        // create restaurant listing and link
        $restaurant = Restaurant::create(Arr::add($request->except(['restaurant.logo', 'restaurant.cover_image'])['restaurant'], 'user_id', $user->id));

        if ($request->hasFile('logo')) {
            $restaurant->clearMediaCollection('logo');
            $restaurant->addMediaFromRequest('logo')->toMediaCollection('logo');
        }

        if ($request->hasFile('cover_image')) {
            $restaurant->clearMediaCollection('cover_image');
            $restaurant->addMediaFromRequest('cover_image')->toMediaCollection('cover_image');
        }

        return redirect()->route('manager.edit-menu');
    }

    public function orders()
    {
        return view('manager.orders');
    }

    public function getOrders(Request $request)
    {
        $user = Auth::user();
        $since = Carbon::parse('-24 hours');
        $orders = Order::orderByDesc('created_at')->where([
        ['created_at','>=', $since],
        ['restaurant_id','=', $user->restaurant->id ]
      ])->get();
        return response()->json([
        'orders' => $orders,
      ]);
    }

    public function confirmOrder(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->predicted_time = $order->desired_time->hour($request->hour)->minute($request->minute);
        $save = $order->save();
        return $order;
    }

    public function cancelOrder(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->delete();
        return;
    }

    public function orderHistory()
    {
        return view('manager.order-history');
    }

    public function getOrderHistory(Request $request)
    {
        $user = Auth::user();
        $orders = Order::orderByDesc('created_at')->where([
        ['restaurant_id','=', $user->restaurant->id ]
      ])->get();
        return response()->json([
        'orders' => $orders,
      ]);
    }

    public function editInfo()
    {
        $user = Auth::user();
        $restaurant = $user->restaurant;
        return view('manager.edit-info')->with([
        'user' => $user,
        'restaurant' => $restaurant,
      ]);
    }

    public function editInfoSubmit(Request $request)
    {
        $user = Auth::user();
        $restaurant = $user->restaurant;
        $updateRestaurant = $restaurant->update(Arr::except($request->restaurant, ['logo','cover']));

        if ($request->hasFile('restaurant.logo')) {
            $restaurant->addMediaFromRequest('restaurant.logo')->toMediaCollection('logo');
        }

        if ($request->hasFile('restaurant.cover')) {
            $restaurant->addMediaFromRequest('restaurant.cover')->toMediaCollection('cover');
        }

        if ($request->password!="") {
          $user->update([
            'password' => Hash::make($request->password)
          ]);
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
        ]);

        if ($updateRestaurant) {
            $request->session()->flash('success', 'Your information was successfully updated');
            return redirect()->route('manager.index');
        } else {
            return back()->withErrors(['Sorry, something went wrong, please try again.']);
        }
    }

    public function editMenu()
    {
        return view('manager.edit-menu');
    }

    public function editMenuSubmit(Request $request)
    {
        $restaurant = Auth::user()->restaurant;
        // save groups into menu attribute
        if ($restaurant->menu) {
            $restaurant->menu()->delete();
        }
        foreach ($request->menu as $groupData) {
            $group = $restaurant->menu()->create(Arr::only($groupData, ['name', 'description', 'slug']));

            if (!empty($groupData['items'])) {
                foreach ($groupData['items'] as $groupItemData) {
                    $groupItem = $group->items()->create(Arr::add($groupItemData, 'restaurant_id', $restaurant->id));

                    if (!empty($groupData['flags'])) {
                        foreach ($groupItemData['flags'] as $flag) {
                            $groupItemFlags = $groupItem->flags()->create(['name' => $flag]);
                        }
                    }

                    if (!empty($groupData['items'])) {
                        dump($groupItemData['sizes']);
                        $groupItemSizes = $groupItem->sizes()->createMany($groupItemData['sizes']);
                    }

                    foreach ($groupItemData['options'] as $itemOptionGroupData) {
                        $itemOptionGroup = $groupItem->options()->create($itemOptionGroupData);
                        $itemOptionGroup->option_values()->createMany($itemOptionGroupData['option_values']);
                    }
                }
            }
        }

        $restaurant->load('menu');

        return response()->json($restaurant->menu);
    }

    public function deleteCover($index){
        $restaurant = Auth::user()->restaurant;
        $mediaItems=$restaurant->getMedia('cover');
        $mediaItems[$index]->delete();
        return redirect()->route('manager.editInfo');
    }
}
