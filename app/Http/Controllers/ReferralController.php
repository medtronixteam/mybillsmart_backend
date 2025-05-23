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
    public function refferedUsers()
    {
        return response()->json([
            'refferedUsers' =>User::where('referrer_id',auth('sanctum')->id())->latest()->get(),
            'status' => 'success',
        ],200);
    }

    public function commissionUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'commission' => 'required|numeric',
            'notify_user'=>'required|in:yes,no',
        ]);


        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(), 'status' => 'error',], 500);
        }

        $user = User::find($request->user_id);
        $user->commission = $request->commission;
        $user->save();
        return response()->json([
            'message' => "Commission updated successfully.",
            'status' => 'success',
        ], 200);

    }


    public function ReferalPoints()
    {

        $referralPoints = ReferralPoints::where('group_id', auth('sanctum')->id())->first();
        if ($referralPoints) {
            return response()->json([
                'data' => $referralPoints,
                'status' => 'success',
            ], 200);
        } else {
            return response()->json([
                'message' => "Referral points not found.",
                'status' => 'error',
            ], 404);
        }
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
