<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

class PlanContoller extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return response()->json(['plans' => $plans, 'status' => 'success']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
        ]);

        $plan = Plan::create($request->all());
        return response()->json(['plan' => $plan, 'status' => 'success'], 201);
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'duration' => 'sometimes|integer',
        ]);

        $plan->update($request->all());
        return response()->json(['plan' => $plan, 'status' => 'success']);
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return response()->json(['message' => 'Plan deleted successfully', 'status' => 'success']);
    }
}
