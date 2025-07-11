<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Services\InvoiceZapierHook;

class InvoiceController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'bill type' => 'nullable',
            'address' => 'nullable|string',
            'cups' => 'nullable|string',
            'billing period' => 'nullable',
            'group_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 500);
        }
        $billType = $request->input('bill type') ? $request->input('bill type') : 'Gas';
        $bill_period = $request->input('billing period') ? $request->input('billing period') : 'n/a';
        $CUPS = $request->input('cups') ? $request->input('cups') : 'n/a';
        $address = $request->input('address') ? $request->input('address') : 'n/a';
        $billInfo = $request->except(['bill type', 'address', 'cups', 'billing period']);
        $invoice = Invoice::create(
            [
                'bill_type' => $billType,
                'billing_period' => $bill_period,
                'address' => $address,
                'CUPS' => $CUPS,
                'bill_info' => $billInfo,
                'group_id' => $request->group_id,
                'agent_id' => auth('sanctum')->id(), //self id who adding
            ]
        );
          //send to zapier hook to invoice data
        InvoiceZapierHook::invoiceLog($invoice);
        return response()->json([
            'message' => 'Invoice created successfully.',
            'status' => "success",
            'invoice' => $invoice->id,
        ], 201);
    }
    // Create a new invoice

    public function updateInvoice(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'bill type' => 'nullable',
            'address' => 'nullable|string',
            'cups' => 'nullable|string',
            'billing period' => 'nullable',
            'invoice_id' => 'required|exists:invoices,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 500);
        }
        $billType = $request->input('bill type') ? $request->input('bill type') : 'Gas';
        $bill_period = $request->input('billing period') ? $request->input('billing period') : 'n/a';
        $CUPS = $request->input('cups') ? $request->input('cups') : 'n/a';
        $address = $request->input('address') ? $request->input('address') : 'n/a';
        $billInfo = $request->except(['bill type', 'address', 'cups', 'billing period']);
        $invoice = Invoice::find('id', $request->input('invoice_id'))->update(
            [
                'bill_type' => $billType,
                'billing_period' => $bill_period,
                'address' => $address,
                'CUPS' => $CUPS,
                'bill_info' => $billInfo,

            ]
        );

        return response()->json([
            'message' => 'Invoice updated successfully.',
            'status' => "success",
        ], 200);
    }
    public function storeGroup(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'bill type' => 'nullable',
            'address' => 'nullable|string',
            'cups' => 'nullable|string',
            'billing period' => 'nullable',
            'group_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 500);
        }
        $billType = $request->input('bill type') ? $request->input('bill type') : 'Gas';
        $bill_period = $request->input('billing period') ? $request->input('billing period') : 'n/a';
        $CUPS = $request->input('cups') ? $request->input('cups') : 'n/a';
        $address = $request->input('address') ? $request->input('address') : 'n/a';
        $billInfo = $request->except(['bill type', 'address', 'cups', 'billing period']);
        $invoice = Invoice::create(
            [
                'bill_type' => $billType,
                'billing_period' => $bill_period,
                'address' => $address,
                'CUPS' => $CUPS,
                'bill_info' => $billInfo,
                'group_id' => $request->group_id,
                'agent_id' => auth('sanctum')->id(), //self id who adding
            ]
        );

        //send to zapier hook to invoice data
        InvoiceZapierHook::invoiceLog($invoice);

        return response()->json([
            'message' => 'Invoice created successfully.',
            'status' => "success",
            'invoice' => $invoice->id,
        ], 201);
    }
    public function storClient(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'bill type' => 'nullable',
            'address' => 'nullable|string',
            'cups' => 'nullable|string',
            'billing period' => 'nullable',
            'group_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 500);
        }
        $billType = $request->input('bill type') ? $request->input('bill type') : 'Gas';
        $bill_period = $request->input('billing period') ? $request->input('billing period') : 'n/a';
        $CUPS = $request->input('cups') ? $request->input('cups') : 'n/a';
        $address = $request->input('address') ? $request->input('address') : 'n/a';
        $billInfo = $request->except(['bill type', 'address', 'cups', 'billing period']);
        $invoice = Invoice::create(
            [
                'bill_type' => $billType,
                'billing_period' => $bill_period,
                'address' => $address,
                'CUPS' => $CUPS,
                'bill_info' => $billInfo,
                'group_id' => $request->group_id,
                'client_id' => auth('sanctum')->id(),
                'agent_id' => auth('sanctum')->id(),
            ]
        );

        return response()->json([
            'message' => 'Invoice created successfully.',
            'status' => "success",
            'invoice' => $invoice->id,
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
        return response()->json($invoices, 200);
    }
    public function groupInvoices()
    {
        $invoices = Invoice::where('group_id', auth('sanctum')->id())->latest()->get();
        return response()->json($invoices, 200);
    }

    public function agentList()
    {

        $invoiceData = Invoice::where('agent_id', auth('sanctum')->id())->latest()->get();
        $response = ['status' => "success", 'code' => 200, 'data' => $invoiceData];
        return response($response, $response['code']);
    }
    //client portal client can see its invoices
    public function clientInvoices_list()
    {

        $invoiceData = Invoice::where('client_id', auth('sanctum')->id())->latest()->get();

        return response(['status' => "success", 'code' => 200, 'data' => $invoiceData], 200);
    }



}
