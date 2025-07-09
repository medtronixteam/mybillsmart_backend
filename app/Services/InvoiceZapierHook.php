<?

namespace App\Services;

use App\Models\HookLog;
use App\Models\ZapierHook;
use App\Models\User;
use Carbon\Carbon;

class InvoiceZapierHook
{

    public static function log($event, $data, $zapierId)
    {
        HookLog::create([
            'payload' => json_encode($data),
            'event' => $event,
            'user_id' => auth('sanctum')->id(),
            'zapier_hook_id' => $zapierId,
        ]);
    }
    public static function invoiceLog($invoiceData)
    {
        try {
        $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());
        $userGet = User::find($adminOrGroupUserId);
        $currentPlan=$userGet->activeSubscriptions()->value('plan_name');
        if ($currentPlan == "enterprise" || $currentPlan == "pro") {


            ZapierHook::where('type', 'invoice')->each(function ($hook) use ($invoiceData) {

                $invoiceData = json_decode(json_encode($invoiceData), true);
                $filterData = $this->prepareInvoiceData($invoiceData);
                if ($filterData) {
                    if ($this->testHook($filterData, $hook->url)) {
                        $hook->logs()->create([
                            'payload' => json_encode($invoiceData),
                            'event' => 'invoice',
                            'user_id' => auth('sanctum')->id(),
                        ]);
                    } else {
                        $hook->logs()->create([
                            'payload' => json_encode($invoiceData),
                            'event' => 'invoice',
                            'status' => 'failed',
                            'user_id' => auth('sanctum')->id(),
                        ]);
                    }
                } else {
                    //status failded
                    $hook->logs()->create([
                        'payload' => json_encode($invoiceData),
                        'event' => 'invoice',
                        'status' => 'failed',
                        'user_id' => auth('sanctum')->id(),
                    ]);
                }
            });
                    }
        } catch (\Throwable $th) {
             //status failded
            $hook->logs()->create([
                'payload' => json_encode($invoiceData),
                'event' => 'invoice',
                'status' => 'failed',
                'user_id' => auth('sanctum')->id(),
            ]);
        }
    }
    function prepareInvoiceData(array $invoice)
    {

        $agentName = User::find($invoice['agent_id']);
        $groupName = User::find($invoice['group_id']);
        $data = [
            'id' => $invoice['id'] ?? '',
            'bill_type' => $invoice['bill_type'] ?? '',
            'address' => $invoice['address'] ?? '',
            'CUPS' => $invoice['CUPS'] ?? '',
            'billing_period' => $invoice['billing_period'] ?? '',
            'agent' => $agentName ?? '',
            'group' => $groupName ?? '',
            'is_offer_selected' => $invoice['is_offer_selected'] ?? '',
            'cif_nif' => $invoice['cif_nif'] ?? '',
            'created_at' => $invoice['created_at'] ?? now(),
        ];

        $mappingForTaxes = [
            'IGIC General' => '0.32',
            'IGIC Reducido' => '1.21',
            'Impuesto electricidad' => '1.96',
            'iva' => '16.68',
            'impuesto sobre hidrocarburos' => '2.99',
        ];
        foreach ($mappingForTaxes as $originalKey => $finalKey) {
            $keyValue = $invoice['bill_info']['taxes'][$originalKey] ?? '';

            $data[$finalKey] = !empty($keyValue) ? 'taxes ' . $keyValue : '';
        }

        // Other bill_info fields (excluding taxes)
        $mapping = [
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

        foreach ($mapping as $originalKey => $finalKey) {
            $data[$finalKey] = $invoice['bill_info'][$originalKey] ?? '';
        }

        return $data;
    }
    public function testHook($filterData, $hookUrl)
    {
        try {
            \Illuminate\Support\Facades\Http::post($hookUrl, $filterData);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
