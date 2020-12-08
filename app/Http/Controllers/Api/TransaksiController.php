<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\Transaksi;
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
    public function store(Request $request){
        //find Transaksi dengan id user setelah itu baru bisa di store
        ///blmmmmmmm
        $storeData =$request->all();
        $orders = Order::where(
            'id_user', $storeData['id_user'],
        )->get();
        // return $orders;
        if($orders){
            foreach($orders as $order) {
                $temp =  Transaksi::where('id_product',$order->id_product)->first();
                // return $temp;
                // return $order;
                if($temp!=null){
                    $temp->sold_items =$order->quantity+$temp['sold_items'];
                    $temp->total =$order->total+$temp['total'];
                    // return $temp['total'];
                    // return $temp->total;
                    $temp->save();
                    // return $order->total;
                }else{
                    Transaksi::create([
                        'nama_product' => $order->nama_product,
                        'sold_items' => $order->quantity,
                        'total' => $order->total,
                        'id_product' => $order->id_product
                    ]);
                }
                $order->delete();
            }
            return response([
                'message'=>'Add Transaksi Success',
                // 'data'=>$order,
            ],200);
        }
        return response([
            'message'=>'Add Transaksi Failed',
            // 'data'=>null,
        ],200);
    }

    // public function update(Transaksi $request, $id){
    //     $transaksi = Transaksi::where('id_product',$id)->first();
    //     if(is_null($transaksi)){
    //         return response([
    //             'message'=>'Transaksi Not Found',
    //             'data'=>null
    //         ],404);
    //     }

    //     $updateData = $request->all();
    //     //validate update blm
    //     $validate = Validator::make($updateData,[
    //         'nama_product'=>'required',
    //         'sold_items'=>'required',
    //         'total'=>'required',
    //         'id_product'=>'required'
    //     ]);

    //     if($validate->fails())
    //         return response(['message'=>$validate->errors()],404);//return error invalid input

    //     $transaksi['nama_product'] = $updateData['nama_product'];
    //     $transaksi['sold_items'] = $updateData['sold_items'];
    //     $qty = $transaksi['sold_items']+$updateData['quantity'];
    //     //update stok
    //     $transaksi['total'] = $updateData['total']+$transaksi['total'];

    //     $transaksi->save();

    // //     if($transaksi->save()){
    // //         return response([
    // //             'message'=>'Update Transaksi Success',
    // //             'data'=>$transaksi,
    // //         ],200);
    // //     }//return product yg telah diedit

    // //     return response([
    // //         'message'=>'Update Transaksi Failed',
    // //         'data'=>null,
    // //     ],404);//return message saat product gagal diedit
    // }

}
