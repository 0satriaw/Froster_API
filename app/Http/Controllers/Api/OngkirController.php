<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Ongkir;
class OngkirController extends Controller
{
    public function index(){
        $ongkir = Ongkir::all();

        if(count($ongkir)>0){
                return response([
                'message' =>'Retrieve All Success',
                'data' =>$ongkir
                ],200);
            }

        return response([
            'message' => 'Empty',
            'data' =>null
            ],404);
        
        
    }

    public function show ($id){
        $ongkir=Ongkir::find($id);

        
        if(!is_null($ongkir)){
            return response([
                'message'  => 'Retrieve Ongkir Success',
                'data' => $ongkir
            ],200);

        }

        return response([
            'message' => 'Ongkir Not Found',
            'data' => null
        ],404);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'region' => 'required|max:60|unique:ongkir',
            'sub_district' => 'required|max:255',
            'harga_ongkir' => 'required|numeric',
        
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

        $ongkir = Ongkir::create($storeData);
        return response([
            'message' => 'Add Ongkir Success',
            'data' => $ongkir,
        ],200);
   


    }

    public function destroy($id){
        $ongkir = Ongkir::find($id);

            if(is_null($ongkir)){
                return response([
                    'message' => 'Ongkir Not Found',
                    'data'=>null
                ],404);
            }

            if($ongkir->delete()){
                return response([
                    'message' => 'Delete Ongkir Success',
                    'data' =>$ongkir,
                ],200);
            }
            return response([
                'message' => 'Delete ongkir Failed',
                'data' => null,
            ],400);
        
    }

    public function update(Request $request, $id){
        $ongkir = Ongkir::find($id);
        if(is_null($ongkir)){
            return response([
                'message'=>'Ongkir Not Found',
                'data'=>null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'region'=>'max:60',Rule::unique('ongkir')->ignore($ongkir),
            'sub_district' => 'max:60',
            'harga_ongkir' =>'numeric',

        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);
            

            $ongkir->region =  $updateData['region'];
            $ongkir->sub_district = $updateData['sub_district'];
            $ongkir->harga_ongkir= $updateData['harga_ongkir'];


        if($ongkir->save()){
            return response([
                'message' => 'Update Ongkir Success',
                'data'=> $ongkir,
            ],200);
        }

        return response([
            'messsage'=>'Update Ongkir Failed',
            'data'=>null,
        ],400);
    }
}
