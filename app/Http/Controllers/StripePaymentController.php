<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\PaymentIntent;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Validator;
class StripePaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(),'status'=>"error"], 500);
        }
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $amount = $request->amount * 100;

        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
            'status'=>"success",
            'paymentIntentId' => $paymentIntent->id,
        ],200);
    }
    public function storeSubscription(Request $request)
{

    $validator = Validator::make($request->all(), [
     'amount' => 'required|numeric',
        'payment_intent_id' => 'required|string',
    ]);
    if ($validator->fails()) {
        return response()->json(['message' => $validator->messages()->first(),'status'=>"error"], 500);
    }

    $subscription = Subscription::create([
        'user_id' =>auth('sanctum')->id(),
        'amount' => $request->amount,
        'payment_intent_id' => $request->payment_intent_id,
        'start_date' => Carbon::now(),
        'status' => 'active',
    ]);

    return response()->json([
        'status'=>"success",
        'message' => 'Subscription created successfully',
        'subscription' => $subscription
    ],200);
}
public function handle(Request $request)
{
    Log::info('Stripe Webhook Received', $request->all());
}

}
