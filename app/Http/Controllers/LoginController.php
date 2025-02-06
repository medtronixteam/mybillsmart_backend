<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages()->first();
            return response()->json([
                'message' => $messages,
                'status' => 'error',
                'code' => 400
            ], 400);
        }
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('auth-token')->plainTextToken;
            unset($user->id);
            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => "Login successfully.",
                'status' => 'success',
                'code' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => "Invalid email or password.",
                'status' => 'error',
                'code' => 401
            ], 401);
        }
    }
    function register(Request $request) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:20',
                'email' => 'required|email|unique:users',
                'password' => 'required',

            ]);
         if ($validator->fails()) {
            $messages = $validator->messages()->first();
            $response = ['message' => $messages,
                'status' => 'error', 'code' => 500];

         }else{

               $user= User::create([
                   'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);


                $response = [
                    'message'=>"Register  Successfully.",
                    'status'=>'success',
                    'code'=>200,

                ];
            }
            return response($response, $response['code']);
        }

        public function providerSignup(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:20',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'country' => 'required',
                'city' => 'required',
                'postal_code' => 'required',

            ]);
            if ($validator->fails()) {
                $messages = $validator->messages()->first();
                $response = ['message' => $messages,
                    'status' => 'error', 'code' => 500];
            }else{
                $user= User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'country' => $request->country,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'role' => 'provider',
                ]);
                $response = [
                    'message'=>"Provider Register  Successfully.",
                    'status'=>'success',
                    'code'=>200,
                ];
            }
            return response($response, $response['code']);

        }
        public function agentSignup(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:20',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'country' => 'required',
                'city' => 'required',
                'postal_code' => 'required',

            ]);
            if ($validator->fails()) {
                $messages = $validator->messages()->first();
                $response = ['message' => $messages,
                    'status' => 'error', 'code' => 500];
            }else{
                $user= User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'country' => $request->country,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'role' => 'agent',
                ]);
                $response = [
                    'message'=>"Agent Register  Successfully.",
                    'status'=>'success',
                    'code'=>200,
                ];
            }
            return response($response, $response['code']);

        }
    }
