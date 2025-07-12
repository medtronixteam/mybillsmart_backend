<?php

namespace App\Services;

use App\Models\HookLog;
use App\Models\ZapierHook;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserZapierHook
{
    public static function userLog($invoiceData)
    {
        try {
            $adminOrGroupUserId = User::getGroupAdminOrFindByGroup(auth('sanctum')->id());
            $user = User::find($adminOrGroupUserId);
            $currentPlan = $user->activeSubscriptions()->value('plan_name');

            if ($currentPlan) {
                $zapierHooks = ZapierHook::where('type', 'agent')->get();

                foreach ($zapierHooks as $hook) {
                    $invoiceArray = json_decode(json_encode($invoiceData), true);
                    $filterData = (new self)->prepareData($invoiceArray);

                    $status = 'failed';
                    if ($filterData && (new self)->testHook($filterData, $hook->url)) {
                        $status = 'success';
                    }

                    $hook->logs()->create([
                        'payload' => json_encode($invoiceArray),
                        'event' => 'agent',
                        'status' => $status,
                        'user_id' => auth('sanctum')->id(),
                    ]);
                }
            }

        } catch (\Throwable $th) {
            Log::error('Zapier Hook Exception: ' . $th->getMessage());

            // Optionally log to a fallback if you still want to store something
            HookLog::create([
                'payload' => json_encode($invoiceData),
                'event' => 'agent',
                'status' => 'failed',
                'user_id' => auth('sanctum')->id(),
            ]);
        }
    }

    public function prepareData(array $userData): array
    {
        return [
            'name'        => $userData['name'] ?? '',
            'email'       => $userData['email'] ?? '',
            'phone'       => $userData['phone'] ?? '',
            'country'     => $userData['country'] ?? '',
            'state'       => $userData['state'] ?? '',
            'city'        => $userData['city'] ?? '',
            'postal_code' => $userData['postal_code'] ?? '',
            'user_type'   => $userData['role'] ?? '',
        ];
    }

    public function testHook(array $filterData, string $hookUrl): bool
    {
        try {
            Http::post($hookUrl, $filterData);
            return true;
        } catch (\Exception $e) {
            Log::warning("Webhook failed for URL: $hookUrl", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
