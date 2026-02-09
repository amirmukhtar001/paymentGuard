<?php

namespace App\Policies;

use App\Models\Reconciliation;
use App\Models\User;

class ReconciliationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->business_id !== null;
    }

    public function view(User $user, Reconciliation $reconciliation): bool
    {
        return $user->business_id === $reconciliation->business_id;
    }

    public function update(User $user, Reconciliation $reconciliation): bool
    {
        return ($user->isOwner() || $user->isManager()) && $user->business_id === $reconciliation->business_id;
    }
}
