<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentIntent;

class PaymentIntentController extends Controller
{
    public function orderHistory()
    {
        // Fetch all payment intents excluding hidden fields
        $paymentIntents = PaymentIntent::where('user_id', auth('sanctum')->user()->id)->where('status', 'succeeded')
            ->latest()->get();

        return response()->json([
            'message' => 'Payment Intents fetched successfully',
            'data' => $paymentIntents,
            'status' => 'success',
            'code' => 200
        ],200);
    }
}
