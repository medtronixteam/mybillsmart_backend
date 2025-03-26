<?php

namespace App\Http\Controllers\Api;
use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{

        public function allProductsData()
        {
            $products = Product::latest()->get();
            return response()->json($products,200);
        }

        public function providerProducts(Request $request)
        {

            $validator = Validator::make($request->all(), [
                'group_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 500);
            }

            $adminOrGroupUserId = User::getGroupAdminOrFindByGroup($request->group_id);
            $products = Product::where('provider_id',$adminOrGroupUserId)->latest()->get();
            return response()->json($products,200);
        }
        public function index()
        {
            $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());
            $products = Product::where('provider_id', $adminOrGroupUserId)->latest()->get();
            return response()->json($products,200);
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
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first()], 500);
        }

        $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());

        $product = Product::create(array_merge(
            $request->all(),
            ['provider_id' =>$adminOrGroupUserId]
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
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->messages()->first()], 500);
            }

            $product->update(array_merge(
                $request->all(),
                ['provider_id' => auth('sanctum')->id()]
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
}
