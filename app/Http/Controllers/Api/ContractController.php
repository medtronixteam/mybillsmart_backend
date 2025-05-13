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
use App\Models\Offer;
use Illuminate\Support\Facades\Log;

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
            'closure_date' => 'required|date_format:Y-m-d',
            'offer_id' => 'required',
            'note' => 'nullable',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->first();
            return response(['message' => $message, 'status' => 'error', 'code' => 500], 500);
        }

        try {
        $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());
        Contract::create([
            'client_id' => $request->client_id,
            'contracted_provider' => $request->contracted_provider? $request->contracted_provider : 'n/a',
            'contracted_rate' => $request->contracted_rate? $request->contracted_rate :0,
            'closure_date' => date('Y-m-d', strtotime($request->closure_date)),
            'start_date' => date('Y-m-d', strtotime($request->start_date)),
            'status' => $request->status? $request->status : 'pending',
            'offer_id' => $request->offer_id,
            'note' => $request->note,
            'agent_id' => auth('sanctum')->id(),
            'group_id' => $adminOrGroupUserId,
        ]);
        Offer::find($request->offer_id)->invoice()->

        NotificationController::pushNotification($request->client_id, 'New Agreement Request', 'You have received a new Agreement.Please upload the required documents.');
        return response(['message' => 'Agreement has been added, Waiting for client approval', 'status' => 'success', 'code' => 200], 200);

        } catch (\Throwable $th) {
            Log::info('group admin /contracts'.$th->getMessage());
             return response(['message' => 'Something missing Try Again', 'status' => 'error', 'code' => 500], 500);

        }
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
            return response()->json(['message' => "Contract not assigned to any client"], 500);
         }
         $offers=Offer::find($contract->offer_id);

         if($request->status == 'confirmed'){
            $userAgent=User::find($contract->agent_id);
            $groupAdmin= User::getGroupAdminOrFindByGroup($userAgent->id);
            $userAgent->increment('points', 10);

            Profit::create([
                'user_id' => $contract->agent_id,
                'contract_id' => $contract->id,
                'amount' => $offers->sales_commission,
                'group_id' => $groupAdmin,
            ]);


           if($groupAdmin){
            NotificationController::pushNotification($groupAdmin, 'Agreement Confirmed', 'Agreement has been confirmed by '.$userAgent->name);
           }
            NotificationController::pushNotification($contract->client_id, 'Agreement Confirmed', 'Your Agreement has been confirmed by '.$userAgent->name);
         }

         $contract->update(['status' => $request->status]);

         return response()->json(['message' => 'Contract status updated successfully'], 200);
     }

}
