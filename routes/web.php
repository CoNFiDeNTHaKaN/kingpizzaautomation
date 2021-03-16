<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/banned' , function(){ return view('banned'); })->name('banned');

Route::get('/', function () {
    return view('pages.home');
})->name('home');
Route::get('/mail-template', function () {
    return view('mail.template');
});


Route::prefix('user')->name('user.')->group(function () {
    Route::get('/login', 'UserController@login')->name('login');
    Route::post('/login', 'UserController@loginSubmit')->name('loginSubmit');

    Route::get('/reset', 'UserController@resetPassword')->name('resetPassword');
    Route::post('/reset', 'UserController@resetPasswordSubmit')->name('resetPasswordSubmit');

    Route::get('/register', 'UserController@register')->name('register');
    Route::post('/register', 'UserController@registerSubmit')->name('registerSubmit');

    Route::get('/logout', 'UserController@logout')->name('logout');

    Route::get('/account', 'UserController@account')->name('account')->middleware('auth');
    Route::get('/orders', 'UserController@orders')->name('orders')->middleware('auth');

    Route::get('/saved-cards', 'UserController@savedCards')->name('savedCards')->middleware('auth');
    Route::get('/saved-cards/delete', 'UserController@deleteCard')->name('deleteCard')->middleware('auth');


    Route::get('/saved-addresses', 'UserController@savedAddresses')->name('savedAddresses')->middleware('auth');
	Route::post('/saved-addresses', 'UserController@addAddress')->name('addAddress')->middleware('auth');
    Route::get('/saved-addresses/delete/{id}', 'UserController@deleteAddress')->name('deleteAddress')->middleware('auth');
	Route::get('/saved-addresses/edit/{id}', 'UserController@editAddress')->name('editAddress')->middleware('auth');
	Route::post('/saved-addresses/update', 'UserController@updateAddress')->name('updateAddress')->middleware('auth');


    Route::get('/edit-info', 'UserController@editInfo')->name('editInfo')->middleware('auth');
    Route::post('/edit-info', 'UserController@editInfoSubmit')->name('editInfoSubmit')->middleware('auth');

    Route::get('/verify-phone' , 'UserController@verifyPhone')->name('verifyPhone');
    Route::post('/verify-phone' , 'UserController@sendVerificationCode')->name('sendVerificationCode');
    Route::get('/verify-phone/{code}' , 'UserController@verifyLink')->name('verifyLink');

    
});

Route::prefix('order-now')->name('restaurants.')->group(function () {
    Route::get('/', 'RestaurantController@index')->name('list');
    Route::get('/detail', 'RestaurantController@detail')->name('detail')->middleware('phoneVerified');

    Route::post('/{slug}/review' , 'RestaurantController@review')->name('submit-review');

    Route::post('/update-basket', 'OrderController@updateBasket')->name('updateBasket');
    Route::get('/resume-basket', 'OrderController@resumeBasket')->name('resumeBasket');

    Route::get('/checkout', 'OrderController@checkout')->name('checkout')->middleware('preventback')->middleware('phoneVerified');

    Route::get('/confirm', 'OrderController@confirm')->name('confirm')->middleware('preventback')->middleware('phoneVerified');
    Route::post('/submitpayment', 'OrderController@pay')->name('pay')->middleware('phoneVerified');
    Route::get('/thanks/{id}', 'OrderController@thanks')->name('thanks');

    Route::get('/clear-location', 'OrderController@resetLocation')->name('clearLocation');

    Route::get('/{slug}', 'RestaurantController@detail')->name('goto')->middleware('preventback')->middleware('phoneVerified');
});

Route::middleware(['isrestaurant'])->prefix('managers')->name('manager.')->group(function () {
    Route::get('/', 'ManagerController@index')->name('index');

    Route::get('/register', 'ManagerController@register')->name('register');
    Route::post('/register', 'ManagerController@registerSubmit')->name('registerSubmit');

    Route::get('/orders', 'ManagerController@orders')->name('orders');
    Route::get('/get-orders', 'ManagerController@getOrders')->name('getOrders');
    Route::post('/confirm-order','ManagerController@confirmOrder')->name('confirmOrder');
    Route::post('/cancel-order','ManagerController@cancelOrder')->name('cancelOrder');

    Route::get('/order/history', 'ManagerController@orderHistory')->name('orderHistory');
    Route::get('/get-order-history', 'ManagerController@getOrderHistory')->name('getOrderHistory');

    Route::get('/edit-menu', 'ManagerController@editMenu')->name('editMenu');
    Route::post('/edit-menu', 'ManagerController@editMenuSubmit')->name('editMenuSubmit');

    Route::get('/edit-info', 'ManagerController@editInfo')->name('editInfo');
    Route::post('/edit-info', 'ManagerController@editInfoSubmit')->name('editInfoSubmit');

    Route::get('/edit-info/deleteCover/{index}' , 'ManagerController@deleteCover')->name('deleteCover');
});

Route::prefix('u')->name('utility.')->group(function () {
    Route::get('/addresses', 'UtilityController@getAddresses')->name('addresses');
});

Route::prefix('ajax')->group(function () {
    Route::get('/menu/{id}','OrderController@orderModal');
    Route::post('updateBasket' , 'OrderController@updateBasket');
});

Auth::routes(['verify' => true]);

Route::get('/{path}', 'PageController@show')->name('showPage');
