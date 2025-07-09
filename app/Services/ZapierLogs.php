<?
namespace App\Services;

use App\Models\HookLog;
use Carbon\Carbon;

class ZapierLogs {

    public static function log($event,$data,$zapierId) {
        HookLog::create([
            'payload' => json_encode($data),
            'event' => $event,
            'user_id' => auth('sanctum')->id(),
            'zapier_hook_id' => $zapierId,
        ]);
    }
}
