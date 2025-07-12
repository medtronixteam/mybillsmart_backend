<?

namespace App\Services;

use App\Models\HookLog;
use App\Models\ZapierHook;
use App\Models\User;
use Carbon\Carbon;

class OfferZapierHook
{

    public static function offerLog($invoiceData)
    {
        try {
        $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());
        $userGet = User::find($adminOrGroupUserId);
        $currentPlan=$userGet->activeSubscriptions()->value('plan_name');
        if ($currentPlan == "enterprise" || $currentPlan == "pro") {


               $ZapierHook=ZapierHook::where('type', 'offer')->get();
            foreach($ZapierHook as $hook) {

                $invoiceData = json_decode(json_encode($invoiceData), true);
                $filterData = $this->prepareData($invoiceData);
                if ($filterData) {
                    if ($this->testHook($filterData, $hook->url)) {
                        $hook->logs()->create([
                            'payload' => json_encode($invoiceData),
                            'event' => 'offer',
                            'user_id' => auth('sanctum')->id(),
                        ]);
                    } else {
                        $hook->logs()->create([
                            'payload' => json_encode($invoiceData),
                            'event' => 'offer',
                            'status' => 'failed',
                            'user_id' => auth('sanctum')->id(),
                        ]);
                    }
                } else {
                    //status failded
                    $hook->logs()->create([
                        'payload' => json_encode($invoiceData),
                        'event' => 'offer',
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
                'event' => 'agent',
                'status' => 'failed',
                'user_id' => auth('sanctum')->id(),
            ]);
        }
    }
    function prepareData(array $offerData)
    {

         $data = [
           'invoice_id'=>$offerData['invoice_id'],
            'provider_name' => $offerData['provider_name'],
            'sales_commission' => $offerData['sales_commission'],
            'product_name' => $offerData['product_name'],
            'monthly_saving_amount' => $offerData['monthly_saving_amount'],
            'yearly_saving_amount' => $offerData['yearly_saving_amount'],
            'yearly_saving_percentage' => $offerData['yearly_saving_percentage'],

          ];


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
