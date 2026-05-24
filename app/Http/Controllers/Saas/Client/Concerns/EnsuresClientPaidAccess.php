<?php

namespace App\Http\Controllers\Saas\Client\Concerns;

use Illuminate\Support\Facades\Auth;

trait EnsuresClientPaidAccess
{
    protected function ensurePaidAccess(): void
    {
        $tenant = Auth::user()?->tenant;
        abort_unless(
            $tenant && $tenant->canManageContent(),
            403,
            'Complete and verify your subscription payment to use this feature.'
        );
    }
}
