<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{

     public function store(Request $request){

        $validator= Validator::make($request->all(), [
            'name'=>'required',
            'description'=>'required',
            'price'=>'required',
            'status' => 'required|integer|in:0,1',
        ]);
        if($validator->fails()){
        $message=$validator->messages()->first();
        $response=[
            'message'=>$message,'status'=>'error','code'=>500
        ];
        } else{

            Product::create([
                'name'=>$request->name,
                'description'=>$request->description,
                'price'=>$request->price,
                'status'=>$request->status,
                'user_id' => auth('sanctum')->id(),
            ]);
            $response=[
                'message'=>'Product has been created',
                'status'=>'success',
                'code'=>200,
            ];
        }
        return response($response, $response['code']);
        }
                 function delete($id) {
                 $contact = Product::where('id', $id)->delete();
                    $response = [
                    'message' => 'Product has been deleted',
                    'status' => 'success',
                    'code' => 200,
                ];
                return response($response, $response['code']);
        }

        public function singleProduct($id)
        {
            $product = Product::find($id);

            if ($product) {
                $response = [
                    'message' => 'Product found',
                    'status' => 'success',
                    'code' => 200,
                    'data' => $product,
                ];
            } else {
                $response = [
                    'message' => 'Product not found',
                    'status' => 'error',
                    'code' => 404,
                ];
            }
            return response($response, $response['code']);
        }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            $response = [
                'message' => $message, 'status' => 'error', 'code' => 500
            ];
        } else {
            $product = Product::find($id);

            if ($product) {
                $product->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'status' => $request->status,
                ]);
                $response = [
                    'message' => 'Product has been updated',
                    'status' => 'success',
                    'code' => 200,
                ];
            } else {
                $response = [
                    'message' => 'Product not found',
                    'status' => 'error',
                    'code' => 404,
                ];
            }
        }
        return response($response, $response['code']);
    }
}
