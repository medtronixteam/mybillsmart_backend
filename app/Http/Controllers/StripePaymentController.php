<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\PaymentIntent;
use App\Models\PaymentIntent as PaymentIntentModel;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Product;
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
            return response()->json(['message' => $e->getMessage() . 'Error retrieving payment intent', 'status' => "error"], 500);
        }
    }
    public function createPaymentIntent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required',
            'duration' => 'required:in:monthly,annual',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(), 'status' => "error"], 500);
        }

        try {


            Stripe::setApiKey(env('STRIPE_SECRET'));
            $plan = Plan::where('name', strtolower($request->plan_id))->first();
            if (!$plan) {
                return response()->json(['message' => 'Plan not found', 'status' => "error"], 500);
            }
            $packages = array("starter", "pro", "enterprise");
            //if plan is expension or volume then annual not allowed
            if (!in_array(strtolower($request->plan_id), $packages) && strtolower($request->duration) != "monthly") {
                return response()->json(['message' => 'Invalid plan', 'status' => "error"], 500);
            }
            //check either plan subscribed or not
            if (auth('sanctum')->user()->activeSubscriptions()->count() == 0 && !in_array(strtolower($request->plan_id), $packages)) {
                return response()->json(['message' => 'You have not subscribed any Plan (Starter,Pro,Enterprise) yet', 'status' => "error"], 500);
            }

            if ($request->duration == "annual") {
                $amount = $plan->annual_price * 100;
            } else {
                $amount = $plan->monthly_price * 100;
            }
            Log::info('Payment Intent Amount---.-->: ' . $amount);

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'eur',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
            PaymentIntentModel::create([
                'user_id' => auth('sanctum')->id(),
                'amount' => $amount,
                'plan_name' => strtolower($request->plan_id),
                'plan_duration' => strtolower($request->duration),
                'currency' => 'eur',
                'stripe_payment_intent_id' => $paymentIntent->id,
                'status' => 'pending',
            ]);
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'status' => "success",
                'paymentIntentId' => $paymentIntent->id,
            ], 200);
        } catch (\Exception $e) {
            Log::info('Payment Intent Creation----->: ' . $e->getMessage());
            return response()->json(['message' => 'Unable to process Request of Payment Please try later', 'status' => "error"], 500);
        }
    }

    public function planInfo()
    {

        //checker
        $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());
        $limitCheck = app(\App\Services\LimitService::class);

        $limitChecked = $limitCheck->useLimit($adminOrGroupUserId, 'invoices', false, false);
        $productsCheck = Product::where('group_id', $adminOrGroupUserId)->orWhere('product_type', 'global')->count();
        if ($productsCheck == 0) {
            if (auth('sanctum')->user()->role == 'group_admin') {
                return response()->json([

                    "message" => "Please add product agreements first",
                ], 404);
            }
            return response()->json([

                "message" => "There is no product agreements ",
            ], 404);
        }

        if (!$limitChecked) {
            return response()->json([
                'status' => "error",
                "message" => "Plan limit exceeded or Expired",
            ], 403);
        }

        return response()->json([
            'status' => "success",
            "message" => "Everything is fine",
        ]);
    }
    public function agentInfo()
    {

        //checker
        $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());
        $limitCheck = app(\App\Services\LimitService::class);

        $limitChecked = $limitCheck->useLimit($adminOrGroupUserId, 'agents', false, false);


        if (!$limitChecked) {
            return response()->json([
                'status' => "error",
                "message" => "Plan limit exceeded or Expired",
            ], 403);
        }

        return response()->json([
            'status' => "success",
            "message" => "Everything is fine",
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
                if ($PaymentIntentData->plan_duration == "annual") {
                    $end_date = Carbon::now()->addYear();
                } else {
                    $end_date = Carbon::now()->addMonth();
                }
                if (in_array($PaymentIntentData->plan_name, ['starter', 'pro', 'enterprise'])) {
                    $type = "plan";
                } elseif (in_array($PaymentIntentData->plan_name, ['growth_pack', 'scale_pack', 'max_pack'])) {
                    $type = "expansion";
                } else {
                    $type = "volume";
                }
                $subsc = Subscription::create([
                    'user_id' => $PaymentIntentData->user_id,
                    'amount' => $PaymentIntentData->amount,
                    'payment_intent_id' => $paymentIntentId,
                    'start_date' => Carbon::now(),
                    'end_date' => $end_date,
                    'status' => 'active',
                    'type' => $type,
                    'plan_name' =>  $PaymentIntentData->plan_name,
                    'plan_duration' =>  $PaymentIntentData->plan_duration,
                ]);

                if ($PaymentIntentData->plan_name == "starter" or $PaymentIntentData->plan_name == "pro" or $PaymentIntentData->plan_name == "enterprise") {
                    User::find($PaymentIntentData->user_id)->update([
                        'plan_name' => $PaymentIntentData->plan_name,
                        'subscription_id' => $subsc->id,
                    ]);
                    $subsc->update([
                        'type' => "plan",
                    ]);
                }
            } //end of intent exists
            // Example: update order/payment status in DB


            Log::info('Payment succeeded for intent: ' . $paymentIntentId);
            Log::info('Amount for intent: ' . $amountReceived);
        }

        return response()->json(['status' => 'success']);
    }
    public function tester()
    {
        $limitCheck = app(\App\Services\LimitService::class);

        $limitcheck = $limitCheck->useLimit(2);
        return response()->json(['status' => $limitcheck]);
        //   $idPayment=  PaymentIntentModel::create([
        //             'user_id' => 2,
        //             'amount' => 100,
        //             'plan_name' => 'growth_pack',
        //             'plan_duration' => 'monthly',
        //             'currency' => 'eur',
        //             'stripe_payment_intent_id' =>4,
        //             'status' => 'pending',
        //         ]);
        //         $end_date= Carbon::now()->addMonth();
        //           $subsc = Subscription::create([
        //                 'user_id' => $idPayment->user_id,
        //                 'amount' => $idPayment->amount,
        //                 'payment_intent_id' => $idPayment->id,
        //                 'start_date' => Carbon::now(),
        //                 'end_date' =>$end_date,
        //                 'status' => 'active',
        //                 'type' => 'plan',
        //                 'plan_name' =>  $idPayment->plan_name,
        //                 'plan_duration' =>  $idPayment->plan_duration,
        //             ]);

    }
}
