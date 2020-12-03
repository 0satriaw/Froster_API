<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;
use App\Notifications\VerifyApiEmail;

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

        $user = User::create($registrationData)->sendApiEmailVerificationNotification();
        // $success['message'] = 'Please confirm yourself by clicking on verify user button sent to you on your email';//DENGAN EMAIL BELUMMM
        // $user = User::create($registrationData);
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
        if($user->email_verified_at!=null){
            $token = $user->createToken('Authenticaton Token')->accessToken;
            return response([
                'message'=>'Authenticated',
                'user'=>$user,
                'token_type'=>'Bearer',
                'access_token'=>$token
            ]);
        }else{
            return response([
                'message'=>'Please Verify Email',
            ],401);
        }
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();

        return response()->json([
            'message'=>'Succesfully logged out'
        ]);
    }

    public function show($id){
        $user = User::find($id);

        if(!is_null($user)){
            return response([
                'message'=>'Retrieve User Success',
                'data'=>$user
            ],200);
        }

        return response([
            'message'=>'User Not Found',
            'data'=>null
        ],404);
    }

    public function update(Request $request, $id){
        $user = User::find($id);

        if(is_null($user)){
            return response([
                'message'=>'User Not Found',
                'data'=>null
            ],404);
        }

        $updateData = $request->all();
        //validate update blm
        $validate = Validator::make($updateData,[
            'name'=>'required|max:60',
            'email'=>'required|email:rfc,dns|unique:users',
            'no_tlp'=>'required|numeric|digits_between:10,13|starts_with:08',
            'alamat'=>'required'
        ]);

        if($validate->fails())
            return response(['message'=>$validate->errors()],404);//return error invalid input

        $user->name = $updateData['name'];
        $user->email = $updateData['email'];
        $user->no_tlp = $updateData['no_tlp'];
        $user->alamat = $updateData['alamat'];

        if($user->save()){
            return response([
                'message'=>'Update User Success',
                'data'=>$user,
            ],200);
        }//return user yg telah diedit

        return response([
            'message'=>'Update User Failed',
            'data'=>null,
        ],404);//return message saat user gagal diedit
    }

    public function updatePassword(Request $request,$id){
        $user = User::find($id);

        if(is_null($user)){
            return response([
                'message'=>'User Not Found',
                'data'=>null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'password'=>'required',
            'newPassword'=>'required',
            'confirmPassword'=>'required'
        ]);

        if($validate->fails()){
            return response(['message'=>$validate->errors()],404);//return error invalid input
        }else{
                if((Hash::check(request('password'), Auth::user()->password))==false){
                    return response([
                        'message'=>'Please check your old password ',
                        'data'=>null,
                    ],404);//return message saat user gagal diedit
                }else if($updateData['newPassword'] != $updateData['confirmPassword']){
                    return response([
                        'message'=>'new password doesnt match',
                        'data'=>null,
                    ],404);//return message saat user gagal diedit
                }else{
                    $user->password = bcrypt($updateData['newPassword']);
                }
        }

        if($user->save()){
            return response([
                'message'=>'Update User Success',
                'data'=>$user,
            ],200);
        }//return user yg telah diedit

        return response([
            'message'=>'Update User Failed',
            'data'=>null,
        ],404);//return message saat user gagal diedit
    }

    public function uploadProfile(Request $request, $id){
        $user = User::find($id);
        if(is_null($user)){
            return response([
                'message' => 'User not found',
                'data' => null
            ],404);
        }

        if(!$request->hasFile('image')) {
            return response([
                'message' => 'Upload Photo User Failed',
                'data' => null,
            ],400);
        }
        $file = $request->file('image');

        if(!$file->isValid()) {
            return response([
                'message'=> 'Upload Photo User Failed',
                'data'=> null,
            ],400);
        }

        $image = public_path().'/profile/';
        $file -> move($image, $file->getClientOriginalName());
        $image = '/profile/';
        $image = $image.$file->getClientOriginalName();
        $updateData = $request->all();
        Validator::make($updateData, [
            'gambar_product' => $image
        ]);
        $user->gambar_product = $image;
        if($user->save()){
            return response([
                'message' => 'Upload Photo User Success',
                'path' => $image,
            ],200);
        }

        return response([
            'messsage'=>'Upload Photo User Failed',
            'data'=>null,
        ],400);
    }

    public function destroy($id){
        $user = User::find($id);

        if(is_null($user)){
            return response([
                'message'=>'User Not Found',
                'data'=>null
            ],404);
        }//return message saat data user tidak ditemukan

        if($user->delete()){
            return response([
                'message'=>'Delete User Success',
                'data'=>$user,
            ],200);
        }//return message saat berhasil menghapus data

        return response([
            'message'=>'Delete User Failed',
            'data'=>null
        ],400);//return message saat gagal menghapus data user
    }

}
