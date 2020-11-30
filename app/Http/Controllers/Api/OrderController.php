<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //Semua Orderan Orang tanpa terkecuali
    public function index(){
        $orders = Order::all();

        if(count($orders)>0){
            return response([
                'message'=>'Retrieve All Success',
                'data'=>$orders
            ],200);
        }

        return response([
            'message'=>'Empty',
            'data'=>null
        ],404);
    }

    //Semua Orderan dengan id nb:id disini adalah id user kayanya blm cari dengan id user ini
    public function show($id_user){
        $orders = Order::find($id_user);

        if(!is_null($orders)){
            return response([
                'message'=>'Retrieve Order Success',
                'data'=>$orders
            ],200);
        }

        return response([
            'message'=>'Order Not Found',
            'data'=>null
        ],404);
    }

    //Create Order baru cek
    public function store(Request $request){
        $storeData =$request->all();
        $validate = Validator::make($storeData,[
            'nama_product'=>'required',
            'harga_product'=>'required',
            'quantity'=>'required',
            'total'=>'required',
            'id_product'=>'required',
            'id_user'=>'required'
        ]);

        if($validate->fails()){
            return response(['message'=>$validate->errors()],400);
        }

        $orders = Order::create($storeData);
        return response([
            'message'=>'Add Order Success',
            'data'=>$orders,
        ],200);
    }

    //DELETE DENGAN Id_product
    public function destroy($id_product){
        $orders = Order::find($id_product);

        if(is_null($orders)){
            return response([
                'message'=>'Order Not Found',
                'data'=>null
            ],404);
        }//return message saat data product tidak ditemukan

        if($orders->delete()){
            return response([
                'message'=>'Delete Order Success',
                'data'=>$orders,
            ],200);
        }//return message saat berhasil menghapus data

        return response([
            'message'=>'Delete Order Failed',
            'data'=>null
        ],400);//return message saat gagal menghapus data product
    }

    //UPDATE SESUAI PRODUK BELUM sesuai
    public function update(Request $request, $id_product){
        $orders = Order::find($id_product);

        if(is_null($orders)){
            return response([
                'message'=>'Order Not Found',
                'data'=>null
            ],404);
        }

        $updateData = $request->all();
        //validate update blm
        $validate = Validator::make($updateData,[
            'nama_product'=>'required',
            'harga_product'=>'required',
            'quantity'=>'required',
            'total'=>'required',
            'id_product'=>'required',
            'id_user'=>'required'
        ]);

        if($validate->fails())
            return response(['message'=>$validate->errors()],404);//return error invalid input

        $orders->nama_product = $updateData['nama_product'];
        $orders->harga_product = $updateData['harga_product'];
        $orders->quantity = $updateData['quantity'];
        $orders->total = $updateData['total'];

        if($orders->save()){
            return response([
                'message'=>'Update Order Success',
                'data'=>$orders,
            ],200);
        }//return product yg telah diedit

        return response([
            'message'=>'Update Order Failed',
            'data'=>null,
        ],404);//return message saat product gagal diedit
    }
}
