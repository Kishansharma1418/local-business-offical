<?php

namespace App\Policies;

use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PurchaseOrderPolicy
{
    public function approve(User $user, PurchaseOrder $po)
    {
        return $user->hasRole('admin') && $po->status === 'draft';
    }

    public function issue(User $user, PurchaseOrder $po)
    {
        return $user->hasRole('purchase') && $po->status === 'approved';
    }

    public function accept(User $user, PurchaseOrder $po)
    {           
        return $user->hasRole('accounted') && $po->status === 'sent';
    }

    public function receive(User $user, PurchaseOrder $po)
    {
        return $user->hasRole('Store') 
            && in_array($po->status, ['accepted','partialreceived']);
    }

    public function reject(User $user, PurchaseOrder $po)
    {
        return $user->hasRole('admin') && $po->status === 'draft';
    }

}
