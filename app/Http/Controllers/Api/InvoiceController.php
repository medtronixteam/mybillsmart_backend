<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Validator;
class InvoiceController extends Controller
{
     // Create a new invoice
     public function store(Request $request)
     {

         $validator = Validator::make($request->all(), [
             'bill type' => 'required',
             'address' => 'required|string',
             'CUPS' => 'required|string',
             'billing period' => 'required',
         ]);

         if ($validator->fails()) {
             return response()->json(['message' => $validator->errors()->first()], 500);
         }
         $billType = $request->input('bill type');
         $bill_period = $request->input('billing period');
         $CUPS = $request->input('CUPS');
         $address = $request->input('address');
         $billInfo = $request->except(['bill type', 'address','CUPS','billing period']);
         $invoice = Invoice::create(
            [
                'bill_type' => $billType,
                'billing_period' => $bill_period,
                'address' => $address,
                'CUPS' => $CUPS,
                'bill_info' => $billInfo,
                'agent_id' => auth('sanctum')->id(),
            ]
         );

         return response()->json([
             'message' => 'Invoice created successfully',
         ], 201);
     }
     public function show($id)
{
    $invoice = Invoice::find($id);

    if (!$invoice) {
        return response()->json(['message' => 'Invoice not found'], 404);
    }
    return response()->json(['data' => $invoice], 200);
}


public function index()
{
    $invoices = Invoice::where('agent_id', auth('sanctum')->id())->latest()->get();
    return response()->json($invoices,200);
}


}
