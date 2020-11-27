<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
//BELUMMMM
class VerificationController extends Controller
{
    public function verify($user_id, Request $request){
        if(!$request->hasValidSignature()){
            return $this->respondUnAuthorizedRequest();
        }

        User::findOrFail($user_id);

        if(!$user->hasVerifiedEmail()){
            $user->markEmailAsVerified();
        }

        return redirect()->to('/');
    }

    public function resend(){
        if(!auth()->user()->hasVerifiedEmail()){
            return $this->responseBadRequest();
        }

        auth()->user()->sendEmailVerificationNotification();

        return $this->respondWithMessage("Email verification link sent on your email");
    }
}
