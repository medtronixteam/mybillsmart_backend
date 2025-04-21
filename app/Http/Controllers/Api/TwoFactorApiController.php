<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Google2FA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use App\Models\SessionHistory;

use App\Mail\TwoFactorCodeMail;
use Illuminate\Support\Facades\Validator;

class TwoFactorApiController extends Controller
{
    protected $google2fa;

    public function __construct(Google2FA $google2fa)
    {
        $this->google2fa = $google2fa;

    }
    public function setup()
    {
        try {
            $user = auth('sanctum')->user();
            $secret = $this->google2fa->generateSecretKey();
            $user->google2fa_secret = $secret;
            $user->save();

            // Generate a QR code URL
            $qrCodeUrl = $this->google2fa->getQRCodeInline(
                'MyBillSmart',
                $user->email,
                $secret
            );
            $response = ['message' => "QR Code Gernerated Successfully", 'qrCodeUrl' => $qrCodeUrl, 'secret' => $secret,
                'status' => 'success', 'code' => 200];

        } catch (\Throwable $th) {
            $response = ['message' => "Something went wrong .".$th->getMessage(),
                'status' => 'error', 'code' => 500];

        }
        return response($response, $response['code']);

    }
    public function disable()
    {
        $user = auth('sanctum')->user();
        $user->twoFA_enable = false;
        $user->google2fa_secret = null;
        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->save();

        $response = ['message' => "2FA has been disabled.",
                'status' => 'success', 'code' => 200];
        return response($response, $response['code']);
    }

    public function validateToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',

        ]);
        if ($validator->fails()) {
            $messages = $validator->messages()->first();
            $response = ['message' => $messages,
                'status' => 'error', 'code' => 500];
            return response($response, $response['code']);
        }
        try {

            $user = auth('sanctum')->user();

            $valid = $this->google2fa->verifyKey($user->google2fa_secret, $request->code);

            if ($valid) {

                $response = ['message' => "2FA verified successfully.",
                'status' => 'success', 'code' => 200];
            } else {

                $response = ['message' => "Invalid 2FA code.",
                'status' => 'error', 'code' => 500];
            }
        } catch (\Throwable $th) {
            $response = ['message' => "Something went wrong .",
                'status' => 'error', 'code' => 500];
        }
        return response($response, $response['code']);
    }
//2fa_enable
    public function enable2Fa() {
        $twoFactorCode = Str::random(6);
        $user=auth('sanctum')->user();
                $user->update([
                    'two_factor_code' => $twoFactorCode,
                    'two_factor_expires_at' => now()->addMinutes(10),
                ]);

                // Send 2FA code via email
                Mail::to($user->email)->queue(new TwoFactorCodeMail($twoFactorCode));
        return response()->json(['message' => 'Please check your email to verify code.','status'=>'success']);
    }
     // Verify 2FA code
     public function verify2FA(Request $request)
     {
         $request->validate([
             'two_factor_code' => 'required|string',
         ]);

         $user =auth('sanctum')->user();

         if ($user->two_factor_code !== $request->two_factor_code || now()->gt($user->two_factor_expires_at)) {
             return response()->json(['message' => 'Invalid or expired 2FA code','status'=>'error'], 500);
         }

         // Clear the 2FA code after successful verification
         $user->update([
            'twoFA_enable'=>true,
             'two_factor_code' => null,
             'two_factor_expires_at' => null,
         ]);

         return response()->json(['message' => '2FA verified successfully','status'=>'success']);
     }
     public function login2FA(Request $request)
     {
         $request->validate([
             'two_factor_code' => 'required|string',
             'email' => 'required|email',
            'password' => 'required|min:2',
         ]);

         if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

         if ($user->two_factor_code !== $request->two_factor_code || now()->gt($user->two_factor_expires_at)) {
            Auth::logout();
             return response()->json(['message' => 'Invalid or expired 2FA code','status'=>'error'], 500);
         }

         // Clear the 2FA code after successful verification
            $user->update([
                'twoFA_enable'=>true,
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);
            $token = $user->createToken('auth-token')->plainTextToken;
            unset($user->id);
            auth('sanctum')->user()->update([
                'last_login_at' => now(),
            ]);
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

         return response()->json(['message' => '2FA verified successfully','status'=>'success']);
     }

}
