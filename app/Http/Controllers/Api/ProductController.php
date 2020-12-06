<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Product;

class ProductController extends Controller
{
    public function index(){
        $product = Product::all();

        if(count($product)>0){
                return response([
                'message' =>'Retrieve All Success',
                'data' =>$product
                ],200);
            }

        return response([
            'message' => 'Empty',
            'data' =>null
            ],404);


    }

    public function show ($id){
        $product=Product::find($id);


        if(!is_null($product)){
            return response([
                'message'  => 'Retrieve Product Success',
                'data' => $product
            ],200);

        }

        return response([
            'message' => 'Product Not Found',
            'data' => null
        ],404);
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'nama_product' => 'required|max:60|unique:product',
            'deskripsi_product' => 'required|max:255',
            'harga_product' => 'required|numeric',
            'stok_product' => 'required|numeric'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);

            //tambahan image biar bisa langsung pas add
        if($files = $request->file('gambar_product')){
        $imageName = $files->getClientOriginalName();
        $request->gambar_product->move(public_path('images'),$imageName);
        $product = Product::create([
            'nama_product'=>$request->nama_product,
            'deskripsi_product'=>$request->deskripsi_product,
            'harga_product'=>$request->harga_product,
            'stok_product'=>$request->stok_product,
            'gambar_product'=>'/images/'.$imageName,
        ]);
        return response([
            'message'=>'Add Product Success',
            'data'=>$product
        ],200);
        }

        // $product = Product::create($storeData);
        // return response([
        //     'message' => 'Add Product Success',
        //     'data' => $product,
        // ],200);

    }

    public function destroy($id){
        $product = Product::find($id);

            if(is_null($product)){
                return response([
                    'message' => 'Product Not Found',
                    'data'=>null
                ],404);
            }

            if($product->delete()){
                return response([
                    'message' => 'Delete Product Success',
                    'data' =>$product,
                ],200);
            }
            return response([
                'message' => 'Delete Product Failed',
                'data' => null,
            ],400);

    }

    public function update(Request $request, $id){
        $product = Product::find($id);
        if(is_null($product)){
            return response([
                'message'=>'Product Not Found',
                'data'=>null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'nama_product'=>['max:60',Rule::unique('product')->ignore($product)],
            'deskripsi_product' => 'max:255',
            'harga_product' =>'numeric',
            'stok_product'=>'numeric'
        ]);

        if($validate->fails())
            return response(['message'=> $validate->errors()],400);


            $product->nama_product =  $updateData['nama_product'];
            $product->deskripsi_product = $updateData['deskripsi_product'];
            $product->harga_product= $updateData['harga_product'];
            $product->stok_product = $updateData['stok_product'];

        if($product->save()){
            return response([
                'message' => 'Update Product Success',
                'data'=> $product,
            ],200);
        }

        return response([
            'messsage'=>'Update Product Failed',
            'data'=>null,
        ],400);
    }

    public function uploadGambar(Request $request, $id){
        $product= Product::find($id);
        if(is_null($product)){
            return response([
                'message' => 'Product not found',
                'data' => null
            ],404);
        }

        if(!$request->hasFile('gambar_product')) {
            return response([
                'message' => 'Upload Photo Product Failed',
                'data' => null,
            ],400);
        }
        $file = $request->file('gambar_product');

        if(!$file->isValid()) {
            return response([
                'message'=> 'Upload Photo Product Failed',
                'data'=> null,
            ],400);
        }
        // $imageName = $files->getClientOriginalName();
        // $request->gambar_product->move(public_path('images'),$imageName);

        $image = public_path().'/images/';
        $file -> move($image, $file->getClientOriginalName());
        $image = '/images/'.$file->getClientOriginalName();
        $updateData = $request->all();
        Validator::make($updateData, [
            'gambar_product' => $image
        ]);
        $product->gambar_product = $image;


        if($product->save()){
            return response([
                'message' => 'Upload Photo Product Success',
                'path' => $image,
            ],200);
        }

        return response([
            'messsage'=>'Upload Photo Product Failed',
            'data'=>null,
        ],400);

    }
}
