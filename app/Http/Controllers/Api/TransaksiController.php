<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use Validator;
use DB;

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
        //find Transaksi dengan id user setelah itu baru bisa di store
        ///blmmmmmmm
        $storeData =$request->get('myArray');
        if($storeData){
            foreach($storeData as $transaksi) {
                $orders =  DB::table('orders')->where('id_user',$transaksi->id_product)->get();
                if($orders){
                    return $this->update($transaksi, $transaksi->id);
                }else{
                    Transaksi::create([
                        'nama_product' => $transaksi['nama_product'],
                        'sold_items' => $transaksi['sold_items'],
                        'total' => $transaksi['total'],
                        'id_product' => $transaksi['id_product']

                    ]);
                    if($validate->fails()){
                        return response(['message'=>$validate->errors()],400);
                    }
                    return response([
                        'message'=>'Add Transaksi Success',
                        'data'=>$transaksis,
                    ],200);
                }
            }

        }
    }

    public function update(Request $request, $id){
        $transaksi = Transaksi::find($id);

        if(is_null($transaksi)){
            return response([
                'message'=>'Transaksi Not Found',
                'data'=>null
            ],404);
        }

        $updateData = $request->all();
        //validate update blm
        $validate = Validator::make($updateData,[
            'nama_product'=>'required',
            'sold_items'=>'required',
            'total'=>'required',
            'id_product'=>'required'
        ]);

        if($validate->fails())
            return response(['message'=>$validate->errors()],404);//return error invalid input

        $transaksi->nama_product = $updateData['nama_product'];
        $transaksi->sold_items = $updateData['sold_items'];
        $qty = $transaksi->sold_items+$updateData['quantity'];
        //update stok
        $transaksi->total = $updateData['total']+$transaksi->total;

        if($transaksi->save()){
            return response([
                'message'=>'Update Transaksi Success',
                'data'=>$transaksi,
            ],200);
        }//return product yg telah diedit

        return response([
            'message'=>'Update Transaksi Failed',
            'data'=>null,
        ],404);//return message saat product gagal diedit
    }

}
