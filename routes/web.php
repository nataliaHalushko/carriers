<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/test','TestController@test');

Route::get('/r/{code}',function ($code){
    \App\Models\Redirect::whereCode($code)->first();
    return redirect(\App\Models\Redirect::whereCode($code)->first()->link??\env("APP_URL"));
})->name('redirect');

Route::get('/manager{any}', 'FrontendController@admin')->where('any', '.*');
Route::any('/{any}', 'FrontendController@app')->where('any', '^(?!api).*$');
