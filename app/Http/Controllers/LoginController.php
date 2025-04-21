<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;
use App\Models\SessionHistory;

use Illuminate\Support\Facades\Validator;

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
            // Generate 2FA code
            if($user->twoFA_enable):

                $twoFactorCode = Str::random(6);
                $user->update([
                    'two_factor_code' => $twoFactorCode,
                    'two_factor_expires_at' => now()->addMinutes(10),
                ]);

                // Send 2FA code via email
                Mail::to($user->email)->queue(new TwoFactorCodeMail($twoFactorCode));
                return response()->json([

                    'message' => "Code has been sent to your email for verification.",
                    'status' => '2fa',
                    'code' => 200
                ], 200);
            endif;


            // Save session history
            $agent = new Agent();
            SessionHistory::create([
                'user_id'     => $user->id,
                'ip_address'  => $request->ip(),
                'device'      => $agent->device(),
                'platform'    => $agent->platform(),
                'browser'     => $agent->browser(),
                'session_id'  => session()->getId(),
                'logged_in_at'=> now(),
            ]);
            $token = $user->createToken('auth-token')->plainTextToken;
            unset($user->id);
            auth('sanctum')->user()->update([
                'last_login_at' => now(),
            ]);
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
                    'dob' => $request->dob,
                ]);


                $response = [
                    'message'=>"Signup   Successfully.",
                    'status'=>'success',
                    'code'=>200,

                ];
            }
            return response($response, $response['code']);
        }
        function sessionHistory() {
            return response([
                'message'=>SessionHistory::findOrFail(auth('sanctum')->user()->id)->latest()->get(),
                'status'=>'success',
                'code'=>200,
                ], 200);
        }
        function sessionHistoryOther(Request $request) {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {

                return response()->json([
                    'message' =>$validator->messages()->first(),
                    'status' => 'error',
                    'code' => 500
                ], 500);
            }
            return response([
                'message'=>SessionHistory::findOrFail($request->user_id)->latest()->get(),
                'status'=>'success',
                'code'=>200,
                ], 200);
        }



    }
