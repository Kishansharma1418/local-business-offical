<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

if (!function_exists('hasPermission')) {
    function hasPermission($permission)
    {
        
        $user = Auth::user();
        if (!$user) return false;

        // app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = Cache::remember('user_permissions_' . $user->id, now()->addMinutes(5), function () use ($user) {
            return $user->getAllPermissions()->pluck('name')->toArray();
        });

        return in_array($permission, $permissions);
    }
}




