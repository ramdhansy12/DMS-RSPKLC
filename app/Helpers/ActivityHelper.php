<?php

namespace App\Helpers;

use App\Models\ActivityLog;

class ActivityHelper
{
    public static function log($activity, $module = null, $description = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => $activity,
            'module' => $module,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }
}