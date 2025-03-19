<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\OffersEmail;
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

    public function sendOffersEmail(Request $request)
    {
        try {
            $request->validate([
                'invoice_id' => 'required|exists:offers,invoice_id',
                'email' => 'required|email',
            ]);

            $offers = Offer::where('invoice_id', $request->invoice_id)->get();

            if ($offers->isEmpty()) {
                return response()->json(['message' => 'No offers found for the given invoice ID', 'status' => 'error'], 404);
            }

            Mail::to($request->email)->send(new OffersEmail($offers));

            return response()->json(['message' => 'Offers sent successfully via email', 'status' => 'success'], 200);

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'status' => 'error'], 500);
        }
    }
}
