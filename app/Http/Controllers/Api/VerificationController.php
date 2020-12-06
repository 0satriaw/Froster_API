<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use VerifiesEmails;
use Carbon\Carbon;

class VerificationController extends Controller
{
    public function verify(Request $request) {
        $userID = $request['id'];
        $user = User::findOrFail($userID);
        $date = Carbon::now();
        $user->email_verified_at = $date; // to enable the “email_verified_at field of that user be a current time stamp by mimicing the must verify email feature
        $user->save();
        // return response()->json('Email Verified');
        //redirect ke link hostingan nantinya
        return redirect()->to('http://localhost:8081/verified');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
        return response()->json('User already have verified email!', 422);
        // return redirect($this->redirectPath());
    }
        $request->user()->sendEmailVerificationNotification();
        return response()->json('The notification has been resubmitted');
        // return back()->with(‘resent’, true);
    }
}
