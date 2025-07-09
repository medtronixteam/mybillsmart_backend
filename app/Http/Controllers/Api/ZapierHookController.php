<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HookLog;
use App\Models\ZapierHook;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\InvoiceZapierHook;

use Illuminate\Support\Facades\Http;

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
         try {
           $zapierHook= ZapierHook::where('id', $request->hook_id)->first();

           //get data which to be send
           $dataToBeSend=$this->DummyHooks($zapierHook->type);
            Http::post($zapierHook->url, $this->DummyHooks($zapierHook->type));
            //store as logs
            InvoiceZapierHook::log($zapierHook->type, $dataToBeSend, $request->hook_id);

           return response()->json(['message' => 'Dummy data has been sent to your hook please check it.'], 200);
        } catch (\Exception $e) {
           return response()->json(['message' => 'Invalid hook url or failed to send test hook'], 500);
        }


    }
    public function hookLogs($id){
        return response()->json(HookLog::where('user_id',auth('sanctum')->id())->where('zapier_hook_id',$id)->latest()->get(), 200);

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
    public function DummyHooks($type)
    {
        $data=[];
        if($type=="invoice"){
            //invoice dummy data to send at zapier hook...

          $data = [
            'id' => 'test data',
            'bill_type' => 'Electricity',
            'address' => '123 Main Street',
            'CUPS' => 'ES1234567890123456',
            'agent' => 'dummy',
            'group' => 'n.a',
            'is_offer_selected' => 1,
            'cif_nif' => 'dsad',
            'created_at' => now(),
            'billing_period' => '2025-07',
            'taxes_IGIC_General' => '0.32',
            'taxes_IGIC_Reducido' => '1.21',
            'taxes_Impuesto_electricidad' => '1.96',
            'taxes_iva' => '16.68',
            'taxes_impuesto_sobre_hidrocarburos' => '2.99',
            'tariff' => 'tariff',
            'fixed term' => 'fixed_term',
            'total bill' => 'total_bill',
            'energy term' => 'energy_term',
            'meter rental' => 'meter_rental',
            'peak power(kW)' => 'peak_power_kw',
            'price per unit' => 'price_per_unit',
            'valley power(kW)' => 'valley_power_kw',
            'peak price(€/kWh)' => 'peak_price_per_kwh',
            'peak consumption(kWh)' => 'peak_consumption_kwh',
            'valley price(€/kWh)' => 'valley_price_per_kwh',
            'total consumption(kWh)' => 'total_consumption_kwh',
            'off-peak price(€/kWh)' => 'off_peak_price_per_kwh',
            'valley consumption(kWh)' => 'valley_consumption_kwh',
            'off-peak consumption(kWh)' => 'off_peak_consumption_kwh',
        ];
        }else if($type=="agent"){

          $data = [
            'name' => 'hook test',
            'email' => 'test@gmail.com',
            'phone' => '',
            'country' => '',
            'state' => '',
            'city' => '',
            'postal_code' => '',
            'user_type' => 'agent',

          ];
        }else if($type=="offer"){

          $data = [
            'invoice_id'=>1,
            'provider_name' => ' test',
            'sales_commission' => '10',
            'product_name' => '',
            'monthly_saving_amount' => '',
            'yearly_saving_amount' => '',
            'yearly_saving_percentage' => '',


          ];

        }
        return $data;
    }
    public function inv()
    {

        $invoiceData = Invoice::where('id', 1)->latest()->first()->toArray();


       // $flattened = $this->prepareInvoiceData($invoiceData);
        //return response($flattened, 200);
    }

}
