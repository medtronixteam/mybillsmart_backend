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
                'code' => 500
            ], 500);
        }
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            // if ($user->google2fa_enable) {

            //     return response()->json([

            //     'message' => "2FA Required.",
            //     'status' => 'error',
            //     'code' => 500,
            //     ]);
            // }
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
                'role' => 'required|in:client,supervisor,agent',
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


               $user= User::create([
                   'name' => $request->name,
                    'email' => $request->email,
                    'role' => $request->role,
                    'group_id' => auth('sanctum')->user()->id,
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

        function referalRegister(Request $request) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:20',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'postal_code' => 'nullable|numeric',
                'address' => 'nullable|string',
                'city' => 'nullable|string',
                'country' => 'nullable|string',
                'referal_code' => 'string|required',

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

            $referal_code=User::where('referral_code',$request->referal_code);
            if($referal_code->count() == 0){
                $response = [
                    'message'=>"Invalid Referal Code.",
                    'status'=>'error',
                    'code'=>500,
                ];
                return response($response, $response['code']);
            }
          $useReferal=  $referal_code->first();

            $adminOrGroupUserId = User::getGroupAdminOrFindByGroup($useReferal->id);

               $user= User::create([
                   'name' => $request->name,
                    'email' => $request->email,
                    'role' => "agent",
                    'group_id' => $adminOrGroupUserId,
                    'added_by' => $useReferal->id,
                    'referrer_id' => $useReferal->id,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'country' => $request->country,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                ]);


                $response = [
                    'message'=>"Signup   Successfully.",
                    'status'=>'success',
                    'code'=>200,

                ];
            }
            return response($response, $response['code']);
        }
    }
