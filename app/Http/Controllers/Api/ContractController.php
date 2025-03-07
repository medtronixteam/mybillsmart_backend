<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;
use Validator;

class ContractController extends Controller
{



    public function list(){

        $contracts= Contract::latest()->get();
        $response=['status'=>"success",'code'=>200,'data'=>$contracts];
        return response($response,$response['code']);
     }

     public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'client_id' => 'required',
            'contracted_provider' => 'required',
            'contracted_rate' => 'required',
            'closure_date' => 'required|date_format:Y-m-d',
            'status' => 'required|in:pending,Confirmed,Rejected',
            'offer_id' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return response(['message' => $message, 'status' => 'error', 'code' => 500], 500);
        }

        Contract::create([
            'client_id' => $request->client_id,
            'contracted_provider' => $request->contracted_provider,
            'contracted_rate' => $request->contracted_rate,
            'closure_date' => date('Y-m-d', strtotime($request->closure_date)),
            'status' => $request->status,
            'offer_id' => $request->offer_id,
        ]);

        return response(['message' => 'Contract has been created', 'status' => 'success', 'code' => 200], 200);

     }
}
