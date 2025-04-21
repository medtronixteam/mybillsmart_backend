<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function latestInvoices()
    {
        $invoices=Invoice::where('group_id', auth('sanctum')->id())->latest()->get();
        $response = [
            'status' => "success",
            'code' => 200,
            'data' => $invoices
        ];

        return response($response, $response['code']);
    }
}
