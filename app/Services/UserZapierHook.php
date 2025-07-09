<?

namespace App\Services;

use App\Models\HookLog;
use App\Models\ZapierHook;
use App\Models\User;
use Carbon\Carbon;

class UserZapierHook
{

    public static function userLog($invoiceData)
    {
        try {
        $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());
        $userGet = User::find($adminOrGroupUserId);
        $currentPlan=$userGet->activeSubscriptions()->value('plan_name');
        if ($currentPlan == "enterprise" || $currentPlan == "pro") {


            ZapierHook::where('type', 'agent')->each(function ($hook) use ($invoiceData) {

                $invoiceData = json_decode(json_encode($invoiceData), true);
                $filterData = $this->prepareInvoiceData($invoiceData);
                if ($filterData) {
                    if ($this->testHook($filterData, $hook->url)) {
                        $hook->logs()->create([
                            'payload' => json_encode($invoiceData),
                            'event' => 'agent',
                            'user_id' => auth('sanctum')->id(),
                        ]);
                    } else {
                        $hook->logs()->create([
                            'payload' => json_encode($invoiceData),
                            'event' => 'agent',
                            'status' => 'failed',
                            'user_id' => auth('sanctum')->id(),
                        ]);
                    }
                } else {
                    //status failded
                    $hook->logs()->create([
                        'payload' => json_encode($invoiceData),
                        'event' => 'agent',
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
    function prepareInvoiceData(array $userData)
    {

         $data = [
            'name' => $userData['name'] ?? '',
            'email' => $userData['email'] ?? '',
            'phone' => $userData['phone'] ?? '',
            'country' => $userData['country'] ?? '',
            'state' => $userData['state'] ?? '',
            'city' => $userData['city'] ?? '',
            'postal_code' => $userData['postal_code'] ?? '',
            'user_type' => $userData['role'] ?? '',
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
