<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
class GoalsController extends Controller
{
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'task_name' => 'required|string',
            'start_date' => 'required|date',
            'points' => 'required|numeric',
            'end_date' => 'required|date|after_or_equal:start_date',

        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(),'status'=>'error'], 500);
        }

        foreach ($request->user_ids as $user_id) {
            Goal::create([
                'user_id' => $user_id,
                'task_name' => $request->task_name,
                'points' => $request->points,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'group_id' => auth('sanctum')->id(),
            ]);
        }
        //goals
        return response()->json(['message' => 'Goals created successfully','status'=>'success'], 201);
    }

    public function update(Request $request,Goal $goal) {
        $validator = Validator::make($request->all(), [
            'task_name' => 'sometimes|string',
            'start_date' => 'sometimes|date',
            'points' => 'sometimes|integer|min:0',
            'end_date' => 'sometimes|date|after_or_equal:start_date'

        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(),'status'=>'error'], 500);
        }

        $goal->update([
            'task_name' =>$request->task_name,
            'start_date' =>$request->start_date,
            'points' => $request->points,
            'end_date' => $request->end_date,

        ]);
        return response()->json(['message' => 'Goal updated','status'=>'success']);
    }

    public function changeStatus(Request $request,$goal) {
        $validator = Validator::make($request->all(), [
             'status' => 'required|in:pending,in_progress,completed'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(),'status'=>'error'], 500);
        }
         $goal=Goal::find($goal);
        if(!$goal){
              return response()->json(['message' => "Invalid Id",'status'=>'error'], 500);
        }
        NotificationController::pushNotification($goal->user_id, 'Goal Status Changed', 'Your goal status has been changed to '.$request->status);

        if ($request->status== 'completed') {
            User::where('id',$goal->user_id)->increment('points',$goal->points);
            if($goal->points>0){
                NotificationController::pushNotification($goal->user_id, 'You got '.$goal->points.' points', $goal->points.' points  has been added for completing task');
            }

        }


        $goal->update(['status' => $request->status]);
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
    public function groupList() {
        $goals = Goal::where('group_id',auth('sanctum')->id())->latest()->get();
        return response()->json(['data' => $goals,'status'=>'success'], 200);

    }
    public function agentGoals() {
        $goals = Goal::where('user_id',auth('sanctum')->id())->latest()->get();
        return response()->json(['data' => $goals,'status'=>'success'], 200);

    }

  public function markAsRead($id)
    {
        $goals = Goal::find($id);

        if (!$goals) {
            return response()->json(['message' => 'goal not found.'], 500);
        }

        $goals->status = 'completed';
        $goals->save();

        return response()->json(['message' => 'Marked as read successfully.','status'=>'success'], 200);
    }

}
