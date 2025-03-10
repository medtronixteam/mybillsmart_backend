<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Validator;

class OffersController extends Controller
{



    public function list(){

        $offers= Offer::where('user_id',auth('sanctum')->id())->latest()->get();
        $response=['status'=>"success",'code'=>200,'data'=>$offers];
        return response($response,$response['code']);
     }





    public function store(Request $request)
    {

        try {

        $cleanData = array_map(function($item) {
            return collect($item)->mapWithKeys(function($value, $key) {
                return [strtolower(str_replace(' ', '', $key)) => $value];
            })->all();
        }, $request->all());
        $validator = Validator::make($cleanData, [
            '*.provider_name' => 'required|string',
            '*.sales_commission' => 'required|numeric',
            '*.product_name' => 'required|string',
            '*.saving%' => 'required|numeric',
            '*.invoice_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(),'status'=>"error"], 500);
        }
        $transformedData = array_map(function ($item) {
            return [
                'provider_name' => $item['provider_name'],
                'sales_commission' => $item['sales_commission'],
                'product_name' => $item['product_name'],
                'saving' => $item['saving%'],
                'user_id' => auth('sanctum')->id(),
                'invoice_id' => $item['invoice_id'],
            ];
        }, $cleanData);
        Offer::insert($transformedData);
        $offers=Offer::where('invoice_id', $cleanData[0]['invoice_id'])->get();
        return response()->json(['message' => 'Offer stored successfully','status'=>"success",'offers'=>$offers], 201);
        } catch (\Throwable $th) {

            return response()->json(['message' => $th->getMessage(),'status'=>"error"], 500);
        }
    }

}
