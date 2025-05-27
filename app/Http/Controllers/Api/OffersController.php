<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\User;
use App\Models\Invoice;
use App\Models\CompanyDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\OffersEmail;
use App\Mail\InvoiceOffersMail;
use PDF;
class OffersController extends Controller
{



    public function sendClientPortal(Request $request) {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|integer|exists:users,id',
            'invoice_id' => 'required|integer|exists:invoices,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(), 'status' => "error"], 500);
        }

        $invoice = Invoice::find($request->invoice_id);

        $invoice->update([
            'client_id' => $request->client_id,
        ]);

        $offer = Offer::where('invoice_id', $request->invoice_id)->first();

        $offer->update([
            'client_id' => $request->client_id,
        ]);

        NotificationController::pushNotification($request->client_id, 'New Offers', 'You have received a new offers.');

        $response = ['status' => "success", 'code' => 200, 'message' => "Offers sent to Client portal successfully"];
        return response($response, $response['code']);
    }

    public function list(){

        $offers= Offer::where('user_id',auth('sanctum')->id())->latest()->get();
        $response=['status'=>"success",'code'=>200,'data'=>$offers];
        return response($response,$response['code']);
     }
     public function view(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|integer|exists:invoices,id'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(),'status'=>"error"], 500);
        }

        $offers = Offer::where('invoice_id', $request->invoice_id)->get();

        $response=['status'=>"success",'code'=>200,'data'=>$offers];
        return response($response,$response['code']);
    }
     public function viewOffers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|integer|exists:offers,invoice_id'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(),'status'=>"error"], 500);
        }

        $invoiceOffers = Offer::where('invoice_id', $request->invoice_id)->get();

        $response=['status'=>"success",'code'=>200,'data'=>$invoiceOffers];
        return response($response,$response['code']);
    }





    public function store(Request $request)
    {

        try {


        $cleanData = array_map(function($item) {
            return collect($item)->mapWithKeys(function($value, $key) {
                return [strtolower(preg_replace('/[^a-zA-Z%]/', '', $key)) => $value];
            })->all();
        }, $request->all());
        $validator = Validator::make($cleanData, [
            '*.providername' => 'required|string',
            '*.salescommission' => 'required|numeric',
            '*.productname' => 'required|string',
            '*.monthlysaving' => 'required|numeric',
            '*.yearlysaving' => 'required|numeric',
            '*.yearlysaving%' => 'required|numeric',
            '*.invoiceid' => 'required',
            '*.id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(),'status'=>"error"], 500);
        }
        $transformedData = array_map(function ($item) {
            return [
                'provider_name' => $item['providername'],
                'monthly_saving_amount' => $item['monthlysaving'],
                'yearly_saving_amount' => $item['yearlysaving'],
                'yearly_saving_percentage' => $item['yearlysaving%'],
                'sales_commission' => $item['salescommission'],
                'product_name' => $item['productname'],
                'user_id' => auth('sanctum')->id(),
                'invoice_id' => $item['invoiceid'],
                'product_id' => $item['id'],
            ];
        }, $cleanData);
        Offer::insert($transformedData);
        $offers=Offer::where('invoice_id', $cleanData[0]['invoiceid'])->get();
        return response()->json(['message' => 'Offer stored successfully','status'=>"success",'offers'=>$offers], 201);
        } catch (\Throwable $th) {
            info("Agent offer api------->".$th->getMessage());
            return response()->json(['message' => 'Something missing in your request','status'=>"error"], 500);
        }
    }

    public function sendOffersEmail(Request $request)
    {
        try {
            $request->validate([
                'invoice_id' => 'required|exists:offers,invoice_id',
                'client_id' => 'required|numeric',
            ]);

            $offers = Offer::where('invoice_id', $request->invoice_id)
                        ->get();

            if ($offers->isEmpty()) {
                return response()->json(['message' => 'No offers found for the given invoice ID', 'status' => 'error'], 404);
            }
            $user = User::where('id', $offers->first()->user_id)->first();
            if (!$user) {
                return response()->json(['message' => 'User not found for this offer', 'status' => 'error'], 404);
            }
            Mail::to($user->email)->send(new OffersEmail($offers));

            return response()->json(['message' => 'Offers sent successfully via email', 'status' => 'success'], 200);

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'status' => 'error'], 500);
        }
    }
public function sendInvoiceOffers(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|exists:offers,invoice_id',
            'client_id' => 'required|numeric|',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $offers = Offer::where('invoice_id', $request->invoice_id)->get();

        if ($offers->isEmpty()) {
            return response()->json(['message' => 'No offers found for this invoice', 'status' => 'error'], 404);
        }

        $user = User::where('id', $offers->first()->user_id)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found for this offer', 'status' => 'error'], 404);
        }

        $company = CompanyDetail::first();
        $pdf = PDF::loadView('invoice_offers_email', ['offers' => $offers, 'company' => $company,]);


        Mail::to($user->email)->send(new InvoiceOffersMail($offers, $pdf, $company));

        return response()->json([
            'message' => 'Offers sent successfully via email',
            'status' => 'success'
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'message' => $th->getMessage(),
            'status' => 'error'
        ], 500);
    }
}


    public function selectedOffer(Request $request){
        $validator = Validator::make($request->all(), [
            'offer_id' => 'required|integer|exists:offers,id'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()->first(),'status'=>"error"], 500);
        }
        Offer::find($request->offer_id)->update([
            'is_offer_selected' => 1,
        ]);
        return response()->json(['message' => "Offer has been selected",'status'=>"success"], 200);

    }
}
