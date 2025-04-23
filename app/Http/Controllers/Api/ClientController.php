<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Contract;
use App\Models\Document;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function clientData()
    {

        $totalContracts = Contract::where('client_id', auth('sanctum')->id())->count();
        $pendingContracts = Contract::where('status', 'pending')->where('client_id', auth('sanctum')->id())->count();
        $completedContracts = Contract::where('status', 'completed')->where('client_id', auth('sanctum')->id())->count();
        $rejectedContracts = Contract::where('status', 'rejected')->where('client_id', auth('sanctum')->id())->count();

        return response()->json([
            'status' => "success",
            'code' => 200,
            'tota_contracts' => $totalContracts,
            'pending_contracts' => $pendingContracts,
            'completed_contracts' => $completedContracts,
            'rejected_contracts' => $rejectedContracts,
        ]);

    }
    function userCreate(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:20',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required|in:client',
            'postal_code' => 'nullable|numeric',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',

        ]);
        if ($validator->fails()) {
            if ($validator->errors()->has('email')) {
                $response = [
                    'message' => 'Email already exists.',
                    'status' => 'error',
                    'code' => 500
                ];
            } else {
                $response = [
                    'message' => $validator->messages()->first(),
                    'status' => 'error',
                    'code' => 500
                ];
            }

     }else{
        $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->user()->id);
           $user= User::create([
               'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'group_id' => $adminOrGroupUserId,
                'added_by' => auth('sanctum')->user()->id,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'country' => $request->country,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'dob' => $request->dob,
            ]);
            $response = [
                'message'=>"User Created  Successfully.",
                'status'=>'success',
                'code'=>200,

            ];
        }
        return response($response, $response['code']);
    }
    public function userList()
    {
        $users= User::where('added_by',auth('sanctum')->user()->id)->latest()->get();
        $response=['status'=>"success",'code'=>200,'data'=>$users];
        return response($response,$response['code']);
    }

    public function update(Request $request, $id)
    {
        $profile = User::find($id);
        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 500);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:20',
            'role' => 'required|in:client,agent',
            'postal_code' => 'nullable|numeric',
            'address' => 'nullable|string',
            'dob' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->messages()->first(), 'status' => 'error', 'code' => 500]);
        }
        $profile->name = $request->name;
        $profile->city = $request->city;
        $profile->country = $request->country;
        $profile->address = $request->address;
        $profile->phone = $request->phone;
        $profile->postal_code = $request->postal_code;
        $profile->dob = $request->dob;
        $profile->save();

        return response(['message' => 'Profile has been updated', 'status' => 'success', 'code' => 200]);
    }

    public function enable($id)
    {
        $userEnable = User::find($id);
        if (!$userEnable) {
            return response()->json(['message' => 'User not found'], 500);
        }
        $userEnable->status = 1;
        $userEnable->save();
        return response()->json(['message' => ' User enabled successfully']);
    }

    public function disable($id)
    {
        $userDisable = User::find($id);
        if (!$userDisable) {
            return response()->json(['message' => 'User not found'], 500);
        }
        $userDisable->status = 0;
        $userDisable->save();
        return response()->json(['message' => 'User disabled successfully']);
    }

    public function delete($id)
    {
        $userDelete = User::find($id);
        if (!$userDelete) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $userDelete->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
