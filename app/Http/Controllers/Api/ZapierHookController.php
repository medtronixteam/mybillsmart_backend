<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ZapierHook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZapierHookController extends Controller
{
    public function testHook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hook_id' => 'required|exists:zapier_hooks,id',

        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->messages()->first(), 'status' => 'error', 'code' => 500]);
        }

         return response()->json(['message' => 'Test hook sent'], 200);
    }
    public function index()
    {
        return response()->json(ZapierHook::where('user_id', auth('sanctum')->id())->latest()->get(), 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'url' => 'required|url',
            'type' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->messages()->first(), 'status' => 'error', 'code' => 500]);
        }


        $hook = ZapierHook::create([
            'name' => $request->name,
            'url' => $request->url,
            'type' => $request->type,
            'user_id' => auth('sanctum')->id(),
        ]);
        return response()->json($hook, 201);
    }

    public function show($id)
    {
        $hook = ZapierHook::find($id);
        if (!$hook) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($hook);
    }

    public function update(Request $request, $id)
    {
        $hook = ZapierHook::find($id);
        if (!$hook) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'url' => 'required|url',
            'type' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->messages()->first(), 'status' => 'error', 'code' => 500]);
        }

        $hook->update([
            'name' => $request->name,
            'url' => $request->url,
            'type' => $request->type,
        ]);
        return response()->json($hook);
    }

    public function destroy($id)
    {
        $hook = ZapierHook::find($id);
        if (!$hook) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $hook->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
