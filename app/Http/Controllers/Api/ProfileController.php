<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
class ProfileController extends Controller
{

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
    public function changePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(),'status'=>'success'], 500);
        }
        $user = $request->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect','status'=>'error'], 401);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully','status'=>'success'], 200);
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


}
