<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 500);
        }

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return response()->json(['message' => 'Notification sent successfully','status'=>'success', 'data' => $notification], 201);
    }
    public static function pushNotification($user_id,$title,$message)
    {


        if ($user_id == null || $title == null || $message == null) {
            return false;
        }
         Notification::create([
            'user_id' => $user_id,
            'title' => $title,
            'message' => $message,
        ]);
        return true;
    }


    public function getUserNotifications()
    {
        $notifications = Notification::where('user_id', auth('sanctum')->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['notifications' => $notifications],200);
    }


    public function getSingleNotification($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found','status'=>'error'], 404);
        }

        return response()->json($notification);
    }


    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found','status'=>'error'], 404);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['message' => 'Notification marked as read','status'=>'success'],200);
    }
    public function markAsReadAll()
    {
        $notification = Notification::where('user_id',auth('sanctum')->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);


        return response()->json(['message' => 'Notifications marked as read','status'=>'success'],200);
    }
}

