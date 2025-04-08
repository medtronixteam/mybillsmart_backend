<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ReferralController extends Controller
{
    public function getReferralUrl(Request $request)
    {
        $user = $request->user();

        if (!$user->referral_code) {
            $user->referral_code = User::generateReferralCode();
            $user->save();
        }
        $referralUrl =config("services.frontendUrl")."signup?ref=".$user->referral_code;
        return response()->json([
            'referral_url' => $referralUrl,
        ],200);
    }
}
