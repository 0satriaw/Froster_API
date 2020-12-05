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
    Route::get('user','Api\AuthController@index');
    Route::put('user/{id}','Api\AuthController@update');
    Route::put('changeprofile/{id}','Api\AuthController@updateProfile');
    Route::put('changepassword/{id}','Api\AuthController@updatePassword');
    Route::get('user/{id}','Api\AuthController@show');
    Route::delete('user/{id}','Api\AuthController@destroy');

    //CRUD PRODUCT
    Route::get('product','Api\ProductController@index');
    Route::get('product/{id}','Api\ProductController@show');
    Route::post('product','Api\ProductController@store');
    Route::put('product/{id}','Api\ProductController@update');
    Route::delete('product/{id}','Api\ProductController@destroy');
    Route::post('product/gambar_product/{id}', 'Api\ProductController@uploadGambar');

    //CRUD ONGKIR
    Route::get('ongkir','Api\OngkirController@index');
    Route::get('ongkir/{id}','Api\OngkirController@show');
    Route::post('ongkir','Api\OngkirController@store');
    Route::put('ongkir/{id}','Api\OngkirController@update');
    Route::delete('ongkir/{id}','Api\OngkirController@destroy');

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




