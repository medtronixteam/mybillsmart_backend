<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Whatsapp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function allProductsData()
    {
       $datasupervisor = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());
        $products = Product::where('group_id',$datasupervisor)->latest()->get();
        return response()->json($products, 200);
    }
    public function identifier(Request $request, $sessionName)
    {

        try {
            $baseUrl = $request->getHost();
            if ($sessionName == "default") {

                Whatsapp::updateOrCreate(['session_name' => 'default'], [
                    'user_id' => 3,
                ]);
                return response()->json(['id' => 3, 'app_mode' => 0], 200);
            }
            $whatsapp = Whatsapp::where('session_name', $sessionName)->first();
            if (!$whatsapp) {

                return response()->json(['message' => "Invalid Session name"], 404);
            }
            $limitCheck = app(\App\Services\LimitService::class);
             $limitCheck->useLimit($whatsapp->user_id,'invoices',true);
            $adminOrGroupUserId = User::getGroupAdminOrFindByGroup($whatsapp->user_id);
            if (!$adminOrGroupUserId) {
                return response()->json(['message' => "Invalid Id"], 404);
            }
            if (!$limitCheck) {
                return response()->json(['message' => "Package has been expired or limit exceeded "], 403);
            }
            if ($baseUrl == "admin.mybillsmart.com") {
                return response()->json(['id' => $adminOrGroupUserId, 'app_mode' => 1], 200);
            } else {
                return response()->json(['id' => $adminOrGroupUserId, 'app_mode' => 0], 200);
            }
        } catch (\Throwable $th) {
            info('from identifier------------------>' . $th);
            return response()->json(['message' => "Something went wrong"], 500);
        }
    }
    public function providerProducts($groupId)
    {

        $adminOrGroupUserId = User::getGroupAdminOrFindByGroup($groupId);
        if (!$adminOrGroupUserId) {
            return response()->json(['message' => "Invalid Id Group id"], 404);
        }
        //

        //$products = Product::where('provider_id',$groupId)->latest()->get();
        $products = Product::latest()->get();
        return response()->json($products, 200);
    }
    public function index()
    {
        $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());
        $products = Product::where('group_id', $adminOrGroupUserId)->latest()->get();
        return response()->json($products, 200);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'product_name' => 'required|string',
            'provider_name' => 'required|string',
            'light_category' => 'required|string',
            'fixed_rate' => 'required|numeric',
            'rl1' => 'nullable|numeric',
            'rl2' => 'nullable|numeric',
            'rl3' => 'nullable|numeric',
            'p1' => 'nullable|numeric',
            'p2' => 'nullable|numeric',
            'p3' => 'nullable|numeric',
            'p4' => 'nullable|numeric',
            'p5' => 'nullable|numeric',
            'p6' => 'nullable|numeric',
            'discount_period_start' => 'nullable|date',
            'discount_period_end' => 'nullable|date',
            'meter_rental' => 'required|numeric',
            'sales_commission' => 'required|numeric',
            'points_per_deal' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first()], 500);
        }

        $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());

        $product = Product::create(array_merge(
            $request->all(),
            ['group_id' => $adminOrGroupUserId, 'addedby_id' => auth('sanctum')->id()]
        ));
        return response()->json(['message' => 'Product has been created'], 201);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(['data' => $product], 200);
    }

    public function update(Request $request, $id)
    {

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [

            'product_name' => 'required|string',
            'light_category' => 'required|string',
            'fixed_rate' => 'required|numeric',
            'rl1' => 'nullable|numeric',
            'rl2' => 'nullable|numeric',
            'rl3' => 'nullable|numeric',
            'p1' => 'nullable|numeric',
            'p2' => 'nullable|numeric',
            'p3' => 'nullable|numeric',
            'p4' => 'nullable|numeric',
            'p5' => 'nullable|numeric',
            'p6' => 'nullable|numeric',
            'discount_period_start' => 'nullable|date',
            'discount_period_end' => 'nullable|date',
            'meter_rental' => 'required|numeric',
            'sales_commission' => 'required|numeric',
            'points_per_deal' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first()], 500);
        }

        $product->update(array_merge(
            $request->all(),
            // ['gr' => auth('sanctum')->id()]
        ));
        return response()->json(['message' => 'Product has been updated.'], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }


    //electricity products

    public function electricityProducts(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'product_name' => 'required|string',
            'provider_name' => 'required|string',
            'light_category' => 'required|string',
            'fixed_rate' => 'required|numeric',
            'rl1' => 'nullable|numeric',
            'rl2' => 'nullable|numeric',
            'rl3' => 'nullable|numeric',
            'p1' => 'nullable|numeric',
            'p2' => 'nullable|numeric',
            'p3' => 'nullable|numeric',
            'p4' => 'nullable|numeric',
            'p5' => 'nullable|numeric',
            'p6' => 'nullable|numeric',
            'discount_period_start' => 'nullable|date',
            'discount_period_end' => 'nullable|date',
            'meter_rental' => 'required|numeric',
            'sales_commission' => 'required|numeric',
            'commision_type' => 'required|in:percentage,fixed',
            'points_per_deal' => 'numeric',
            'validity_period_from' => 'required|date',
            'validity_period_to' => 'required|date',
            'contact_terms' => 'required|string',
            'contract_duration' => 'required|string',
            'power_term' => 'required|numeric',
            'peak' => 'required|string',
            'off_peak' => 'required|string',
            'energy_term_by_time' => 'required|string',
            'variable_term_by_tariff' => 'required|string',
            'customer_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first()], 500);
        }

        $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());

        $product = Product::create(array_merge(
            $request->all(),
            ['group_id' => $adminOrGroupUserId, 'addedby_id' => auth('sanctum')->id(), 'agreement_type' => 'electricity']
        ));
        return response()->json(['message' => 'Product has been created'], 201);
    }
    //gas products

    public function gassProducts(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'product_name' => 'required|string',
            'provider_name' => 'required|string',
            'light_category' => 'required|string',
            'fixed_rate' => 'required|numeric',
            'rl1' => 'nullable|numeric',
            'rl2' => 'nullable|numeric',
            'rl3' => 'nullable|numeric',
            'p1' => 'nullable|numeric',
            'p2' => 'nullable|numeric',
            'p3' => 'nullable|numeric',
            'p4' => 'nullable|numeric',
            'p5' => 'nullable|numeric',
            'p6' => 'nullable|numeric',
            'discount_period_start' => 'nullable|date',
            'discount_period_end' => 'nullable|date',
            'meter_rental' => 'required|numeric',
            'sales_commission' => 'required|numeric',
            'commision_type' => 'required|in:percentage,fixed',
            'points_per_deal' => 'numeric',
            'validity_period_from' => 'required|date',
            'validity_period_to' => 'required|date',
            'contact_terms' => 'required|string',
            'contract_duration' => 'required|string',
            'power_term' => 'required|numeric',
            'peak' => 'required|string',
            'off_peak' => 'required|string',
            'energy_term_by_time' => 'required|string',
            'variable_term_by_tariff' => 'required|string',
            'customer_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first()], 500);
        }

        $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());

        $product = Product::create(array_merge(
            $request->all(),
            ['group_id' => $adminOrGroupUserId, 'addedby_id' => auth('sanctum')->id(), 'agreement_type' => 'gas']
        ));
        return response()->json(['message' => 'Product has been created'], 201);
    }
    //both products

    public function bothProducts(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'product_name' => 'required|string',
            'provider_name' => 'required|string',
            'light_category' => 'required|string',
            'fixed_rate' => 'required|numeric',
            'rl1' => 'nullable|numeric',
            'rl2' => 'nullable|numeric',
            'rl3' => 'nullable|numeric',
            'p1' => 'nullable|numeric',
            'p2' => 'nullable|numeric',
            'p3' => 'nullable|numeric',
            'p4' => 'nullable|numeric',
            'p5' => 'nullable|numeric',
            'p6' => 'nullable|numeric',
            'discount_period_start' => 'nullable|date',
            'discount_period_end' => 'nullable|date',
            'meter_rental' => 'required|numeric',
            'sales_commission' => 'required|numeric',
            'commision_type' => 'required|in:percentage,fixed',
            'points_per_deal' => 'numeric',
            'validity_period_from' => 'required|date',
            'validity_period_to' => 'required|date',
            'contact_terms' => 'required|string',
            'contract_duration' => 'required|string',
            'power_term' => 'required|numeric',
            'peak' => 'required|string',
            'off_peak' => 'required|string',
            'energy_term_by_time' => 'required|string',
            'variable_term_by_tariff' => 'required|string',
            'customer_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first()], 500);
        }

        $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());

        $product = Product::create(array_merge(
            $request->all(),
            ['group_id' => $adminOrGroupUserId, 'addedby_id' => auth('sanctum')->id(), 'agreement_type' => 'both',]
        ));
        return response()->json(['message' => 'Product has been created'], 201);
    }
}
