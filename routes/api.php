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
// Route::get('email/verify/{id}','VerificationController@verify')->name('verification.verify');
// Route::get('email/resend','VerificationController@resend')->name('verification.resend');

    


Route::group(['middleware'=>'auth:api'],function(){
    
    Route::get('product','Api\ProductController@index');
    Route::get('product/{id}','Api\ProductController@show');
    Route::post('product','Api\ProductController@store');
    Route::put('product/{id}','Api\ProductController@update');
    Route::delete('product/{id}','Api\ProductController@destroy');
    Route::post('product/gambar_product/{id}', 'Api\ProductController@uploadGambar');

    Route::get('ongkir','Api\OngkirController@index');
    Route::get('ongkir/{id}','Api\OngkirController@show');
    Route::post('ongkir','Api\OngkirController@store');
    Route::put('ongkir/{id}','Api\OngkirController@update');
    Route::delete('ongkir/{id}','Api\OngkirController@destroy');

    Route::post('logout','Api\AuthController@logout');
});
