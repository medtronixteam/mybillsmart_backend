<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
class SupervisorController extends Controller
{
    public function supervisorData()
    {
        $totalUsers = User::where('added_by', auth('sanctum')->id())->count();
        $products = Product::where('addedby_id',auth('sanctum')->id())->count();
        $response = [
            'status' => "success",
            'code' => 200,
            'total_users' => $totalUsers,
            'total_products' => $products,
        ];

        return response($response, $response['code']);
    }
    function userCreate(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:20',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required|in:client,agent',
            'postal_code' => 'nullable|numeric',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',

        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->messages()->first(), 'status' => 'error', 'code' => 500]);
        }
else{
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
            'name' => 'required',
            'city' => 'required',
            'country' => 'required',
            'address' => 'required',
            'postal_code' => 'required|numeric',
            'phone' => 'required',
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
