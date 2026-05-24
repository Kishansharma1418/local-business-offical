<?php

namespace App\Helpers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request; 

class Logger
{
    public static function log(
        string $module,
        string $action,
        $recordId = null,
        ?array $oldData = null,
        ?array $newData = null,
        string $status = '1'
    ) {
        $user = Auth::user();

        Log::create([
            'module_name' => $module,
            'action' => $action,
            'record_id' => $recordId,
            'old_data' => $oldData ? json_encode($oldData) : null,
            'new_data' => $newData ? json_encode($newData) : null,
            'perform_ip' => Request::ip(),
            'perform_device' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'unknown', 0, 255),
            'status' => $status,
            'user_id' => $user ? $user->id : null,
        ]);
    }
}
