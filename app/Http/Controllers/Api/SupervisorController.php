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
        $products = Product::count();
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
}
