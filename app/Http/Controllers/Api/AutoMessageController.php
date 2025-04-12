<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AutoMessage;
use Illuminate\Support\Facades\Validator;
class AutoMessageController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'List of auto messages',
            'data' => AutoMessage::where('user_id', auth('sanctum')->id())->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'to_number' => 'required|string|max:25',
            'message' => 'required|string',
            'time_send' => 'required|date_format:H:i:s',
            'date_send' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages()->first(),
                'status' => 'error'
            ], 500);
        }

        $message = AutoMessage::create(
            [
                'user_id' => auth('sanctum')->id(),
                'type' => "manual",
                'to_number' => $request->to_number,
                'message' => $request->message,
                'time_send' => $request->time_send,
                'date_send' => $request->date_send,
                'status' => 0,
                'reason' => null,
            ]
        );

        return response()->json([
            'message' => 'Message created',
            'status' => 'success',
            'data' => $message
        ]);
    }

    public function show(AutoMessage $autoMessage)
    {
        return response()->json([
            'message' => 'Message details',
            'status' => 'success',
            'data' => $autoMessage
        ]);
    }

    public function update(Request $request, AutoMessage $autoMessage)
    {
        $validator = Validator::make($request->all(), [
            'to_number' => 'required|string|max:25',
            'message' => 'required|string',
            'time_send' => 'required|date_format:H:i:s',
            'date_send' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages()->first(),
                'status' => 'error'
            ], 500);
        }

        $autoMessage->update(
            [
                'user_id' => auth('sanctum')->id(),
                'type' => "manual",
                'to_number' => $request->to_number,
                'message' => $request->message,
                'time_send' => $request->time_send,
                'date_send' => $request->date_send,
                'status' => 0,
                'reason' => null,
            ]
        );

        return response()->json([
            'message' => 'Message updated',
            'status' => 'success',
            'data' => $autoMessage
        ]);
    }

    public function destroy(AutoMessage $autoMessage)
    {
        $autoMessage->delete();

        return response()->json([
            'message' => 'Message deleted',
            'status' => 'success'
        ]);
    }
}
