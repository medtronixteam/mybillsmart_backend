<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Contract;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\Url;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
class ProfileController extends Controller
{
    public function totalUsers()
    {
        $totalUsers = User::where('group_id', auth('sanctum')->id())->count();
        $pendingContracts = Contract::where('status', 'pending')->count();
        $completedContracts = Contract::where('status', 'completed')->count();
        $rejectedContracts = Contract::where('status', 'rejected')->count();
        $totalInvoices = Invoice::where('agent_id', auth('sanctum')->id())->count();
        $response = [
            'status' => "success",
            'code' => 200,
            'total_users' => $totalUsers,
            'pending_contracts' => $pendingContracts,
            'completed_contracts' => $completedContracts,
            'rejected_contracts' => $rejectedContracts,
            'total_invoices' => $totalInvoices,
        ];

        return response($response, $response['code']);
    }


    public function update(Request $request)
    {
        $profile = User::find(auth('sanctum')->id());
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

        public function list()
        {
            $users= User::latest()->get();
            $response=['status'=>"success",'code'=>200,'data'=>$users];
            return response($response,$response['code']);
        }



        public function detail($id)
        {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 500);
            }

            return response()->json(['status'=>"success",'code'=>200,'data'=>$user]);
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
        public function changePassword(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'current_password' => ['required', 'string'],
                'new_password' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors(), 'status' => 'error'], 500);
            }

            $user = $request->user();
            if (!$user) {
                return response()->json(['message' => 'Unauthenticated', 'status' => 'error'], 401);
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Current password is incorrect', 'status' => 'error'], 401);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json(['message' => 'Password changed successfully', 'status' => 'success'], 200);
        }


    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'status' => 'error'], 500);
        }

        $email = $request->email;
        $otp = rand(100000, 999999);


        Cache::put('otp_', $otp, now()->addMinutes(5));


        Mail::raw("Your OTP code is: $otp", function ($message) use ($email) {
            $message->to($email)->subject('Password Reset OTP');
        });

        return response()->json(['message' => 'OTP sent to your email. It will expire in 5 minute.']);
    }


    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'status' => 'error'], 500);
        }
        // $email = $request->email;
        $enteredOtp = $request->otp;


        $cachedOtp = Cache::get('otp_');

        // if (!$cachedOtp) {
        //     return response()->json(['message' => 'OTP expired, please request a new one.'], 400);
        // }

        if ($cachedOtp != $enteredOtp) {
            return response()->json(['message' => 'Invalid OTP.'], 400);
        }


        Cache::forget('otp_');

        return response()->json(['message' => 'OTP verified successfully.']);
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'status' => 'error'], 500);
        }

        $email = $request->email;
        $otp = rand(100000, 999999);


        Cache::put('otp_' . $email, $otp, now()->addMinutes(2));


        Mail::raw("Your new OTP code is: $otp", function ($message) use ($email) {
            $message->to($email)->subject('Resend OTP');
        });

        return response()->json(['message' => 'New OTP sent to your email.']);
    }


    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'status' => 'error'], 500);
        }
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password reset successfully.']);
    }
//document uploaded



public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'phone' => 'required|string',
        'address' => 'required|string',
        'date_of_birth' => 'required|date',
        'id_card_front' => 'nullable|file|mimes:jpg,jpeg,png',
        'id_card_back' => 'nullable|file|mimes:jpg,jpeg,png',
        'individual_or_company' => 'nullable|in:individual,company',
        'bank_receipt' => 'nullable|file|mimes:jpg,jpeg,png',
        'last_service_invoice' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        'lease_agreement' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        'bank_account_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        'expiration_date' => 'required|date',
        'contract_id' => 'required|integer',
    ]);

    if ($validator->fails()) {
        return response(['message' => $validator->messages()->first(), 'status' => 'error', 'code' => 500]);
    }

    $validatedData = $validator->validated();

    $validatedData['client_id'] = auth('sanctum')->id();

    foreach (['id_card_front', 'id_card_back', 'bank_receipt', 'last_service_invoice', 'lease_agreement', 'bank_account_certificate'] as $field) {
        if ($request->hasFile($field)) {
            $validatedData[$field] = $request->file($field)->store('uploads', 'public');
        }
    }

    $documents = Document::create($validatedData);

    return response()->json([
        'message' => 'Documents uploaded successfully',
        'status' => 'success',
        'code' => 200,
        'data' => $documents
    ], 200);
}
public function listDocuments($id)
{
    $documents = Document::find($id);
    $response=['status'=>"success",'code'=>200,'data'=>$documents];
    return response($response,$response['code']);
}
public function listClients()
{
    $clients= User::where('group_id',auth('sanctum')->id())->get();
    $response=['status'=>"success",'code'=>200,'data'=>$clients];
    return response($response,$response['code']);
}
public function generateUrl(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|unique:urls,email',
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => $validator->errors(), 'status' => 'error'], 500);
    }

    $randomId = Str::random(6);

    $generatedUrl = Url::create([
        'email' => $request->email,
        'random_id' => $randomId,
        'user_id' => auth('sanctum')->id(),
    ]);

    $url =config("services.frontendUrl").$randomId;

    return response()->json([
        'url' => $url,
        'random_id' => $randomId,
        'user_id' => auth('sanctum')->id(),
    ], 200);
}

public function verifyUrl($randomId)
{
    try {
        $urlData = Url::where('random_id', $randomId)->firstOrFail();

        $url = url("/access/{$randomId}");

        return response()->json([
            'url' => $url,
            'email' => $urlData->email,
            'is_valid' => true,
            'status' => 'success'
        ], 200);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'message' => 'Invalid URL or random ID not found',
            'is_valid' => false,
            'status' => 'error'
        ], 404);
    }
}
}
