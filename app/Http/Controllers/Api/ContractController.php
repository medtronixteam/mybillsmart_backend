<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\User;
use App\Models\Profit;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\NotificationController;
class ContractController extends Controller
{

    public function agentData()
    {
        $pendingContracts = Contract::where('status', 'pending')->where('agent_id', auth('sanctum')->id())->count();
        $completedContracts = Contract::where('status', 'completed')->where('agent_id', auth('sanctum')->id())->count();
        $rejectedContracts = Contract::where('status', 'rejected')->where('agent_id', auth('sanctum')->id())->count();
        $totalInvoices = Invoice::where('agent_id', auth('sanctum')->id())->count();
        $response = [
            'status' => "success",
            'code' => 200,

            'pending_contracts' => $pendingContracts,
            'completed_contracts' => $completedContracts,
            'rejected_contracts' => $rejectedContracts,
            'total_invoices' => $totalInvoices,
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

     public function agentContractList(){

        $contracts= Contract::where('agent_id',auth('sanctum')->id())->latest()->get();
        $response=['status'=>"success",'code'=>200,'data'=>$contracts];
        return response($response,$response['code']);
     }

     public function groupContractsList(){

        $contracts= Contract::where('group_id',auth('sanctum')->id())->latest()->get();
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

        $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());

        Contract::create([
            'client_id' => $request->client_id,
            'contracted_provider' => $request->contracted_provider,
            'contracted_rate' => $request->contracted_rate,
            'closure_date' => date('Y-m-d', strtotime($request->closure_date)),
            'status' => $request->status,
            'offer_id' => $request->offer_id,
            'agent_id' => auth('sanctum')->id(),
            'group_id' => $adminOrGroupUserId,
        ]);


        NotificationController::pushNotification($request->client_id, 'New Contract', 'You have received a new contract.');

        return response(['message' => 'Contract has been created', 'status' => 'success', 'code' => 200], 200);

     }
     public function contractStatus(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'contract_id' => 'required|exists:contracts,id',
             'status' => 'required|in:pending,confirmed,rejected',
         ]);

         if ($validator->fails()) {
             return response()->json(['message' => $validator->messages()->first()], 500);
         }

         $contract = Contract::find($request->contract_id);
         if($contract->client_id!=0 && $contract->client_id!=null){

         }
         if($request->status == 'confirmed'){
            Profit::create([
                'user_id' => $contract->client_id,
                'points' => 10,
                'description' => 'Contract Confirmed',
            ]);

            NotificationController::pushNotification($contract->client_id, 'Contract Confirmed', 'Your contract has been confirmed.');
         }

         $contract->update(['status' => $request->status]);

         return response()->json(['message' => 'Contract status updated successfully'], 200);
     }

}
