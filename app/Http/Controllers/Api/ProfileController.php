<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Document;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;
class ProfileController extends Controller
{

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
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->messages()->first(), 'status' => 'error', 'code' => 500]);
        }
        $profile->name = $request->name;
        $profile->city = $request->city;
        $profile->country = $request->country;
        $profile->address = $request->address;
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
        $request->validate(['email' => 'required|email|exists:users,email']);

        $email = $request->email;
        $otp = rand(100000, 999999);


        Cache::put('otp_' . $email, $otp, now()->addMinutes(2));


        Mail::raw("Your OTP code is: $otp", function ($message) use ($email) {
            $message->to($email)->subject('Password Reset OTP');
        });

        return response()->json(['message' => 'OTP sent to your email. It will expire in 2 minute.']);
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);

        $email = $request->email;
        $enteredOtp = $request->otp;


        $cachedOtp = Cache::get('otp_' . $email);

        if (!$cachedOtp) {
            return response()->json(['message' => 'OTP expired, please request a new one.'], 400);
        }

        if ($cachedOtp != $enteredOtp) {
            return response()->json(['message' => 'Invalid OTP.'], 400);
        }


        Cache::forget('otp_' . $email);

        return response()->json(['message' => 'OTP verified successfully.']);
    }

    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

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
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

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
        'client_id' => 'required|exists:users,id',
        'contract_id' => 'required|integer',
    ]);

    if ($validator->fails()) {
        return response(['message' => $validator->messages()->first(), 'status' => 'error', 'code' => 500]);
    }

    $validatedData = $validator->validated();
    // $validatedData['client_id'] = auth('sanctum')->id();

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

}
