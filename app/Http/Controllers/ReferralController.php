<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ReferralPoints;
use Illuminate\Support\Facades\Validator;
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
            'status' => 'success',
        ],200);
    }
    public function updateReferalPoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level_1_points' => 'required|numeric',
            'level_2_points' => 'required|numeric',
            'level_3_points' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => 'error',], 500);
        }
        ReferralPoints::updateOrCreate(
            ['group_id' => auth('sanctum')->id()],
            [
                'level_1_points' => $request->level_1_points,
                'level_2_points' => $request->level_2_points,
                'level_3_points' => $request->level_3_points,
            ]
        );
        return response()->json([
            'message' => "Referral points updated successfully.",
            'status' => 'success',
        ],200);
    }
}
