<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentIntent;
use App\Models\Subscription;

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
    public function subscriptionHistory()
    {

        $Subscription = Subscription::where('user_id', auth('sanctum')->user()->id)
        ->latest()->get();
        return response()->json([
            'message' => 'Subscriptions fetched successfully',
            'data' => $Subscription,
            'status' => 'success',
            'code' => 200
        ], 200);


    }
}
