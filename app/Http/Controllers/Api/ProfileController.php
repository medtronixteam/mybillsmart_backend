<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
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
}
