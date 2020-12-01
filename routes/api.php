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


    //ORDER
    Route::get('order','Api\OrderController@index');
    Route::get('order/{id}','Api\OrderController@show');
    Route::post('order','Api\OrderController@store');
    Route::put('order/{id}','Api\OrderController@update');
    Route::delete('order/{id}','Api\OrderController@destroy');


    //TRANSAKSI
    Route::get('transaksi','Api\TransaksiController@index');
    Route::post('transaksi/{id}','Api\TransaksiController@store');
});




