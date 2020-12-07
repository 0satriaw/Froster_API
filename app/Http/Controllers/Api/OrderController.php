<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use Validator;
use App\Product;
use DB;

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

    // Show Order User tertentu//
    public function showOrder($id_user){
        $orders = DB::table('orders')->where('id_user',$id_user)->get();

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
        $id_user = $storeData['id_user'];
        $id_product = $storeData['id_product'];
        // return $storeData;

        //-------------------------------------------Belum dicoba---------------------------------------------------------//
        $product = Product::where('id', $id_product)->first();
        if($product['stok_product']<$storeData['quantity']){
            return response([
                'message'=>'Stock Product tidak cukup',
                'data'=>null,
            ],200);
        }

        //-------------------------------------------Belum dicoba---------------------------------------------------------//

        $orders = Order::where([
            ['id_user', $id_user],
            ['id_product', $id_product]
        ])->first();

        // return $orders;

        if($orders!=null){
            return $this->update($request,$id_user);
        }

        $validate = Validator::make($storeData,[
            'nama_product'=>'required',
            'harga_product'=>'required',
            'quantity'=>'required',
            'id_product'=>'required',
            'id_user'=>'required'
        ]);
        $product['stok_product'] = $product['stok_product'] - $storeData['quantity'];
        $product->save();
        $storeData['total'] = $storeData['harga_product']*$storeData['quantity'];

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
    public function destroy($id){
        $orders = Order::where('id', $id)->first();
        // return $orders;
        $id_product = $orders['id_product'];
        $product = Product::where('id', $id_product)->first();
        if(is_null($orders)){
            return response([
                'message'=>'Order Not Found',
                'data'=>null
            ],404);
        }else{
            $product['stok_product'] = $product['stok_product'] + $orders['quantity'];
            $product->save();
        }

        //return message saat data order tidak ditemukan

        if($orders->delete()){
            return response([
                'message'=>'Delete Order Success',
                'data'=>$orders,
            ],200);
        }//return message saat berhasil menghapus data

        return response([
            'message'=>'Delete Order Failed',
            'data'=>null
        ],400);//return message saat gagal menghapus data order
    }

    //UPDATE SESUAI PRODUK BELUM sesuai
    public function update(Request $request, $id){
        $storeData =$request->all();
        $id_user = $storeData['id_user'];
        $id_product = $storeData['id_product'];
        // return $storeData;

        //-------------------------------------------Belum dicoba---------------------------------------------------------//
        $product = Product::where('id', $id_product)->first();
        if($product['stok_product']<$storeData['quantity']){
            return response([
                'message'=>'Stock Product tidak cukup',
                'data'=>null,
            ],200);
        }else{
            $product['stok_product'] = $product['stok_product'] - $storeData['quantity'];
            $product->save();
        }
        //-------------------------------------------Belum dicoba---------------------------------------------------------//

        $orders = Order::where([
            ['id_user', $id_user],
            ['id_product', $id_product]
        ])->first();

        if(is_null($orders)){
            return response([
                'message'=>'Order Not Found',
                'data'=>null
            ],404);
        }

        //validate update blm
        $validate = Validator::make($storeData,[
            'nama_product'=>'required',
            'harga_product'=>'required',
            'quantity'=>'required',
            // 'total'=>'required',
            'id_product'=>'required',
            'id_user'=>'required'
        ]);

        if($validate->fails())
            return response(['message'=>$validate->errors()],404);//return error invalid input

        $qty = $orders['quantity'] + $storeData['quantity'];
        $totalHarga = $qty * $orders['harga_product'];

        $orders['nama_product'] = $storeData['nama_product'];
        $orders['harga_product'] = $storeData['harga_product'];
        $orders['quantity'] = $qty;
        $orders['total'] = $totalHarga;


        if($orders->save()){
            return response([
                'message'=>'Update Order Success',
                'data'=>$orders,
            ],200);
        }//return order yg telah diedit
        // $orders = Order::updateOrCreate($storeData);
        return response([
            'message'=>'Update success ',
            'data'=>$orders,
        ],404);//return message saat order gagal diedit
    }

    //update yg cart id disini adalah id order
    //ini mau buat fungsi update khusus untuk cart tapi ga work aneh
    public function updateCart(Request $request, $id){
        $orders = Order::find($id);

        // return $orders;
        if(is_null($orders)){
            return response([
                'message'=>'Order Not Found',
                'data'=>null
            ],404);
        }

        // return $storeData;
        $storeData = $request->all();

          //-------------------------------------------Belum dicoba---------------------------------------------------------//
          $product = Product::where('id', $id_product)->first();
          if($product['stok_product']<$storeData['quantity']){
              return response([
                  'message'=>'Stock Product tidak cukup',
                  'data'=>null,
              ],200);
          }
          //-------------------------------------------Belum dicoba---------------------------------------------------------//

        //validate update blm
        $validate = Validator::make($storeData,[
            'nama_product'=>'required',
            'harga_product'=>'required',
            'quantity'=>'required',
            // 'total'=>'required',
            'id_product'=>'required',
            'id_user'=>'required'
        ]);

        if($validate->fails())
            return response(['message'=>$validate->errors()],400);//return error invalid input

        $qty = $orders['quantity'] + $storeData['quantity'];
        $totalHarga = $qty * $orders['harga_product'];

        $orders['nama_product'] = $storeData['nama_product'];
        $orders['harga_product'] = $storeData['harga_product'];
        $orders['quantity'] = $qty;
        $orders['total'] = $totalHarga;


        if($orders->save()){
            return response([
                'message'=>'Update Order Success',
                'data'=>$orders,
            ],200);
        }//return order yg telah diedit
        // $orders = Order::updateOrCreate($storeData);
        return response([
            'message'=>'Update success ',
            'data'=>$orders,
        ],404);//return message saat order gagal diedit
    }
}
