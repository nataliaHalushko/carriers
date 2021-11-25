<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('test','API\TempController@test');

Route::post('liqpay/callback','API\TicketController@liqpayCallback');

Route::prefix('mobile')->namespace('API\Mobile')->group(function (){
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('changePassword', 'UserController@changePassword')->middleware('auth:api');
    Route::post('sendResetCode', 'AuthController@sendCode');
    Route::post('checkResetCode', 'AuthController@checkCode');
    Route::post('resetPassword', 'AuthController@resetPassword');
    Route::get('settlement','SettlementController@settlementSearch');
    Route::get('settlement/popular','SettlementController@settlementPopular');

    Route::post('trip/search','TripController@tripSearch');
    Route::get('trip/carriers','TripController@getCarriers');
    Route::post('ticket/checkout','TicketController@ticketCheckout');
    Route::post('ticket/buy','TicketController@ticketBuy');
    Route::get('ticket/my','TicketController@ticketMy');
    Route::post('user/{user}', 'UserController@update')->middleware('auth:api');
    Route::resource('user', 'UserController')->middleware('auth:api');

});


Route::prefix('web')->group(function (){

    Route::post('register', 'API\AuthController@register');
    Route::post('login', 'API\AuthController@login')->name('login');
    Route::post('changePassword', 'API\AuthController@changePassword');
    Route::post('resetPassword', 'API\AuthController@resetPassword');
    Route::get('settlement','API\SettlementController@settlementSearch');
    Route::get('settlement/popular','API\SettlementController@settlementPopular');

    Route::post('trip/search','API\TripController@tripSearch');
    Route::get('trip/carriers','API\TripController@getCarriers');

    Route::post('ticket/checkout','API\TicketController@ticketCheckout');
    Route::post('ticket/buy','API\TicketController@ticketBuy');
    Route::get('ticket/my','API\TicketController@ticketMy')->middleware('auth:api');
    Route::resource('user', 'API\UserController')->middleware('auth:api')->names([
        'index'=>'web.user'
    ]);

});

Route::prefix('admin')->namespace('Admin')->group(function (){
    Route::resource('route', 'RouteController');

    Route::prefix('bus')->group(function (){
        Route::get('/','BusController@index');
        Route::get('/brands','BusController@brands');
        Route::get('/form','BusController@form');
        Route::get('/{bus}','BusController@show');
        Route::post('/','BusController@store');
        Route::put('/{bus}','BusController@update');
        Route::delete('/{bus}','BusController@destroy');
    });
    Route::prefix('schema')->group(function (){
        Route::get('/','SchemaController@index');
        Route::get('/form','BusController@form');
        Route::get('/{bus}','BusController@show');
        Route::post('/','BusController@store');
        Route::put('/{bus}','BusController@update');
        Route::delete('/{bus}','BusController@destroy');
    });

    Route::get('driver/form', 'DriverController@form');
    Route::resource('driver', 'DriverController');

    Route::resource('carrier', 'CarrierController');
    Route::resource('trip', 'TripController');



});


Route::get('unauthenticated',function (){
    return response()->json([
        'success' => false,
        'message' =>'Unauthenticated.',
    ], 401);
})->name('unauthenticated');
