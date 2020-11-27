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
    // CRUD USER
    Route::post('logout','Api\AuthController@logout');
});
