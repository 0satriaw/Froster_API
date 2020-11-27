<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $registrationData = $request->all();
        $validate = Validator::make($registrationData,[
            'name'=>'required|max:60',
            'email'=>'required|email:rfc,dns|unique:users',
            'password'=>'required',
            'no_tlp'=>'required|numeric|digits_between:10,13|starts_with:08'
        ]);

        if($validate->fails())
            return response(['message'=>$validate->errors()],400); //return error invalid

        $registrationData['password'] = bcrypt($request->password); //enkripsi password
        // $user = User::create($registrationData)->sendEmailVerificationNotification();//DENGAN EMAIL BELUMMM
        $user = User::create($registrationData);

        //COBA commit di branch satria


        return response([
            'message'=>'Register Success',
            'user'=>$user,
        ],200);
    }

    public function login(Request $request){
        $loginData = $request->all();
        $validate = Validator::make($loginData,[
            'email'=>'required|email:rfc,dns',
            'password'=>'required'
        ]);

        if($validate->fails())
            return response(['message'=>$validate->errors()],400);

        if(!Auth::attempt($loginData))
            return response(['message'=>'Invalid Credentials'],401);

        $user = Auth::user();
        $token = $user->createToken('Authenticaton Token')->accessToken;

        return response([
            'message'=>'Authenticated',
            'user'=>$user,
            'token_type'=>'Bearer',
            'access_token'=>$token
        ]);
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();

        return response()->json([
            'message'=>'Succesfully logged out'
        ]);
    }
}
