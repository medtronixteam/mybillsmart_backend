<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;
use App\Models\User;
use App\Models\Whatsapp;
use Validator;
class WhatsAppController extends Controller
{
    protected $twilioService;

    // public function __construct(TwilioService $twilioService)
    // {
    //     $this->twilioService = $twilioService;
    // }
    public function linkWhats(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'session_name' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->messages()->first(), 'status' => 'error', 'code' => 500]);
        }
        $session = Whatsapp::updateOrCreate(
            ['user_id' => auth()->user()->id],
            ['session_name' => $request->session_name]
        );

        return response(['message' => 'WhatsApp linked successfully', 'status' => 'success', 'code' => 200]);
    }


    public function unlinkWhats()
    {

        $deleted = Whatsapp::where('user_id', auth()->user()->id)->delete();

        if ($deleted) {
            return response(['message' => 'WhatsApp unlinked successfully', 'status' => 'success', 'code' => 200]);
        } else {
            return response(['message' => 'Something went wrong', 'status' => 'success', 'code' => 200]);
        }
    }

    public function sendPDF(Request $request)
    {


        $validator = Validator::make($request->all(), [
                'to' => 'required|string',
                'message' => 'required|string',

        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 500);
        }
        try {
            $file = $request->file('pdf');
            $filePath = $file->store('pdfs', 'public'); // Stores in storage/app/public/pdfs
          //  $mediaUrl = null // Generates public URL

            // Send WhatsApp message with PDF
            $response = $this->twilioService->sendWhatsAppMessage($request->to, $request->message);

            return response()->json([
                'status' => 'success',
                'message' => 'PDF sent successfully!',
                'response' => $response,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error',], 500);
        }

    }
}
