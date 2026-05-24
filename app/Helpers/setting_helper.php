<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    function setting($key = null)
    {
        $setting = cache()->rememberForever('app_settings', function () {
            return Setting::first();
        });

        if (!$setting) {
            return null;
        }

        if ($key === null) {
            return $setting;
        }

        return $setting->$key ?? null;
    }
}


