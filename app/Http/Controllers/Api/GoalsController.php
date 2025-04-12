<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Goal;
use Illuminate\Support\Facades\Validator;
class GoalsController extends Controller
{
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'task_name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',

        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(),'status'=>'error'], 500);
        }

        foreach ($validator['user_ids'] as $user_id) {
            Goal::create([
                'user_id' => $user_id,
                'task_name' => $validator['task_name'],
                'start_date' => $validator['start_date'],
                'end_date' => $validator['end_date'],
                'group_id' => auth('sanctum')->id(),
            ]);
        }
        //goals
        return response()->json(['message' => 'Goals created successfully','status'=>'success'], 201);
    }

    public function update(Request $request, Goal $goal) {
        $validator = Validator::make($request->all(), [
            'task_name' => 'sometimes|string',
            'start_date' => 'sometimes|date',
            'points' => 'sometimes|integer|min:0',
            'end_date' => 'sometimes|date|after_or_equal:start_date'

        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(),'status'=>'error'], 500);
        }

        $goal->update($validator);
        return response()->json(['message' => 'Goal updated','status'=>'success']);
    }

    public function changeStatus(Request $request, Goal $goal) {
        $validator = Validator::make($request->all(), [
             'status' => 'required|in:pending,in_progress,completed'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(),'status'=>'error'], 500);
        }

        $goal->update(['status' => $validator['status']]);
        return response()->json(['message' => 'Status updated','status'=>'success']);
    }

    public function delete(Goal $goal) {
        $goal->delete();
        return response()->json(['message' => 'Goal deleted','status'=>'success']);
    }


    public function list() {
        $goals = Goal::where('user_id',auth('sanctum')->id())->latest()->get();
        return response()->json(['data' => $goals,'status'=>'success'], 200);

    }

}
