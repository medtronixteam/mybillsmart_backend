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
            '*.suppliername' => 'required|string',
            '*.fixedmonthlycharges' => 'required|numeric',
            '*.priceperkwh' => 'required|numeric',
            '*.meterrental' => 'required|numeric',
            '*.taxperkwh' => 'required|numeric',
            '*.invoice_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(),'status'=>"error"], 500);
        }
        $transformedData = array_map(function ($item) {
            return [
                'supplier_name' => $item['suppliername'],
                'fixed_monthly_charges' => $item['fixedmonthlycharges'],
                'price_per_kwh' => $item['priceperkwh'],
                'meter_rental' => $item['meterrental'],
                'tax_per_kwh' => $item['taxperkwh'],
                'user_id' => auth('sanctum')->id(),
                'invoice_id' => $item['invoice_id'],
            ];
        }, $cleanData);
        Offer::insert($transformedData);

        return response()->json(['message' => 'Offer stored successfully','status'=>"success"], 201);
        } catch (\Throwable $th) {

            return response()->json(['message' => $th->getMessage(),'status'=>"error"], 500);
        }
    }

}
