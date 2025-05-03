<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;
use App\Models\User;
use Validator;
class WhatsAppController extends Controller
{
    protected $twilioService;

    // public function __construct(TwilioService $twilioService)
    // {
    //     $this->twilioService = $twilioService;
    // }
    public function linkWhats($id)
    {
        User::where('user_id', $id)->update(['whatsapp_link' => 1]);
        return response()->json(['message' => 'WhatsApp linked successfully', 'status' => 'success']);
    }

    public function unlinkWhats($id)
    {
        User::where('user_id', $id)->update(['whatsapp_link' => 0]);
        return response()->json(['message' => 'WhatsApp unlinked successfully', 'status' => 'success']);
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
