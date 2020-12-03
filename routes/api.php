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



Route::post('register','Api\AuthController@register');
Route::post('login','Api\AuthController@login');
// Auth::routes(['verify' => true]);

Route::get('email/verify/{id}', 'Api\VerificationController@verify')->name('verificationapi.verify');
Route::get('email/resend', 'Api\VerificationController@resend')->name('verificationapi.resend');

Route::group(['middleware'=>'auth:api'],function(){

    // CRUD USER
    Route::post('logout','Api\AuthController@logout');
    Route::put('user/{id}','Api\AuthController@update');
    Route::put('changeprofile/{id}','Api\AuthController@updateProfile');
    Route::put('changepassword/{id}','Api\AuthController@updatePassword');
    Route::get('user/{id}','Api\AuthController@show');
    Route::delete('user/{id}','Api\AuthController@destroy');

    //ORDER
    Route::get('order','Api\OrderController@index');
    Route::get('order/{id}','Api\OrderController@show');
    Route::post('order','Api\OrderController@store');
    Route::put('order/{id}','Api\OrderController@update');
    Route::put('updatecart/{id}','Api\OrderController@updateCart');
    Route::delete('order/{id}','Api\OrderController@destroy');
    Route::get('orderuser/{id_user}','Api\OrderController@showOrder');

    //TRANSAKSI
    Route::get('transaksi','Api\TransaksiController@index');
    Route::post('transaksi','Api\TransaksiController@store');
    Route::put('transaksi/{id}','Api\TransaksiController@update');
});




