<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\PaymentIntent;
use App\Models\PaymentIntent as PaymentIntentModel;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class StripePaymentController extends Controller
{
    public function paymentReceipt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:payment_intents,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(), 'status' => "error"], 500);
        }
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $paymentIntentModel = PaymentIntentModel::find($request->order_id);
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentModel->stripe_payment_intent_id);
            $chargeId = $paymentIntent->latest_charge;

            $charge = \Stripe\Charge::retrieve($chargeId);
            $receiptUrl = $charge->receipt_url;
           // $receiptUrl = $paymentIntent->charges->data[0]->receipt_url;
            return response()->json(['message' => $receiptUrl, 'status' => "success"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage().'Error retrieving payment intent', 'status' => "error"], 500);
        }
    }
    public function createPaymentIntent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(), 'status' => "error"], 500);
        }
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $plan = Plan::where('name', strtolower($request->plan_id))->first();
        if (!$plan) {
            return response()->json(['message' => 'Plan not found', 'status' => "error"], 500);
        }
        $amount = $plan->price * 100; // Convert to cents 000
        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'eur',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);
        PaymentIntentModel::create([
            'user_id' => auth('sanctum')->id(),
            'amount' => $request->amount,
            'plan_name' => strtolower($request->plan_id),
            'currency' => 'eur',
            'stripe_payment_intent_id' => $paymentIntent->id,
            'status' => 'pending',
        ]);
        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
            'status' => "success",
            'paymentIntentId' => $paymentIntent->id,
        ], 200);
    }

    public function planInfo()
    {
        if(!auth('sanctum')->user()->plan_name){
            return response()->json([ 'status' => "error",'message' => 'You have not purchased any plan.'], 403);
        }
        $userService = app(UserService::class);

        $allowed = $userService->getTotalInvoiceLimit();
        $used = auth('sanctum')->user()->invoices()
             ->whereBetween('created_at', [
                 Carbon::now()->startOfMonth(),
                 Carbon::now()->endOfMonth()
             ])
             ->count();
             if ($used >= $allowed) {
                return response()->json([ 'status' => "error",'message' => 'Monthly invoice limit reached.'], 403);
            }
       $starter= Plan::where('name','starter')->first();
       $pro= Plan::where('name','pro')->first();
       $enterprise= Plan::where('name','enterprise')->first();

        if(auth('sanctum')->user()->plan_name == 'free' && auth('sanctum')->user()->invoices()->count() > $starter->invoices){
            return response()->json([ 'status' => "error",'message' => 'You have reached the limit  for the Starter plan.'], 403);
        }
        if(auth('sanctum')->user()->plan_name == 'pro' && auth('sanctum')->user()->invoices()->count() > $pro->invoices){
            return response()->json([ 'status' => "error",'message' => 'You have reached the limit  for the Pro plan.'], 403);
        }
        if(auth('sanctum')->user()->plan_name == 'enterprise' && auth('sanctum')->user()->invoices()->count() > $enterprise->invoices){
            return response()->json([ 'status' => "error",'message' => 'You have reached the limit  for the Enterprise plan.'], 403);
        }

        return response()->json([
            'status' => "success",
            'code' => 200,
            'current_plan' => auth('sanctum')->user()->plan_name,
        ]);

    }
    public function storeSubscription(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'payment_intent_id' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(), 'status' => "error"], 500);
        }

        $subscription = Subscription::create([
            'user_id' => auth('sanctum')->id(),
            'amount' => $request->amount,
            'payment_intent_id' => $request->payment_intent_id,
            'start_date' => Carbon::now(),
            'status' => 'active',
        ]);

        return response()->json([
            'status' => "success",
            'message' => 'Subscription created successfully',
            'subscription' => $subscription
        ], 200);
    }
    public function handle(Request $request)
    {
        Log::info('Request!' . json_encode($request->all()));
        $payload = $request->getContent();

        Log::info('Request!' . json_encode($payload));
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;
            $paymentIntentId = $intent->id;
            $amountReceived = $intent->amount_received;
            $PaymentIntent =  PaymentIntentModel::where('stripe_payment_intent_id', $paymentIntentId);
            if ($PaymentIntent->exists()) {
                $PaymentIntent->update([
                    'status' => 'succeeded',
                ]);

                $PaymentIntentData = $PaymentIntent->first();
                $subsc = Subscription::create([
                    'user_id' => $PaymentIntentData->user_id,
                    'amount' => $PaymentIntentData->amount,
                    'payment_intent_id' => $paymentIntentId,
                    'start_date' => Carbon::now(),
                    'end_date' =>Carbon::now()->addMonth(),
                    'status' => 'active',

                    'plan_name' =>  $PaymentIntentData->plan_name,
                ]);
                if ($PaymentIntentData->plan_name == "starter" or $PaymentIntentData->plan_name == "pro" or $PaymentIntentData->plan_name == "enterprise") {
                    User::find($PaymentIntentData->user_id)->update([
                        'plan_name' => $PaymentIntentData->plan_name,
                        'subscription_id' => $subsc->id,
                    ]);
                    $subsc->update([
                        'type' => "plan",
                    ]);
                } else {
                    User::find($PaymentIntentData->user_id)->update([
                        'plan_growth_name' => $PaymentIntentData->plan_name,
                        'growth_subscription_id' => $subsc->id,
                    ]);
                    $subsc->update([
                        'type' => "expansion_pack",
                    ]);
                }
            }
            // Example: update order/payment status in DB


            Log::info('Payment succeeded for intent: ' . $paymentIntentId);
            Log::info('Amount for intent: ' . $amountReceived);
        }

        return response()->json(['status' => 'success']);
    }
}
