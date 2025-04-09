<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Google2FA;
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
        $user->google2fa_enable = false;
        $user->google2fa_secret = null;
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

}
