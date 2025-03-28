<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class ContractController extends Controller
{

    public function agentData()
    {
        $totalUsers = User::where('group_id', auth('sanctum')->id())->count();
        $pendingContracts = Contract::where('status', 'pending')->count();
        $completedContracts = Contract::where('status', 'completed')->count();
        $rejectedContracts = Contract::where('status', 'rejected')->count();
        $response = [
            'status' => "success",
            'code' => 200,
            'total_users' => $totalUsers,
            'pending_contracts' => $pendingContracts,
            'completed_contracts' => $completedContracts,
            'rejected_contracts' => $rejectedContracts,
        ];

        return response($response, $response['code']);
    }



    public function list(){

        $contracts= Contract::latest()->get();
        $response=['status'=>"success",'code'=>200,'data'=>$contracts];
        return response($response,$response['code']);
     }

     public function clientContracts(){

        $contracts= Contract::where('client_id',auth('sanctum')->id())->latest()->get();
        $response=['status'=>"success",'code'=>200,'data'=>$contracts];
        return response($response,$response['code']);
     }
     public function contractList(){

        $contracts= Contract::where('client_id',auth('sanctum')->id())->latest()->get();
        $response=['status'=>"success",'code'=>200,'data'=>$contracts];
        return response($response,$response['code']);
     }
     public function clientList(){


        $group= User::where('role','client')->get();
        $response=['status'=>"success",'code'=>200,'data'=>$group];
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
