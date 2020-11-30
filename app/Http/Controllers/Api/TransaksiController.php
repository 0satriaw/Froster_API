<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
     //ORDERAN TRANSAKSI SMUA UDAH PASTI GAN
     public function index(){
        $transaksis = Transaksi::all();

        if(count($transaksis)>0){
            return response([
                'message'=>'Retrieve All Success',
                'data'=>$transaksis
            ],200);
        }

        return response([
            'message'=>'Empty',
            'data'=>null
        ],404);
    }

    //Create Transaksi baru cek
    public function store(Request $request, $id_user){
        //find Order dengan id user setelah itu baru bisa di store
        ///blmmmmmmm
        $storeData =$request->all();
        $validate = Validator::make($storeData,[
            'nama_product'=>'required',
            'sold_items'=>'required',
            'total'=>'required',
            'id_product'=>'required'
        ]);

        if($validate->fails()){
            return response(['message'=>$validate->errors()],400);
        }

        $transaksis = Transaksi::create($storeData);
        return response([
            'message'=>'Add Transaksi Success',
            'data'=>$transaksis,
        ],200);
    }

}
