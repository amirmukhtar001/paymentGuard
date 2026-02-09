<?php

namespace App\Policies;

use App\Models\Shift;
use App\Models\User;

class ShiftPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->business_id !== null;
    }

    public function view(User $user, Shift $shift): bool
    {
        if ($user->business_id !== $shift->business_id) {
            return false;
        }
        if ($user->isOwner() || $user->isManager()) {
            return true;
        }

        return $user->id === $shift->cashier_id;
    }

    public function create(User $user): bool
    {
        return $user->isManager() || $user->isOwner();
    }

    public function update(User $user, Shift $shift): bool
    {
        return ($user->isManager() || $user->isOwner()) && $user->business_id === $shift->business_id;
    }

    public function delete(User $user, Shift $shift): bool
    {
        return $user->isOwner() && $user->business_id === $shift->business_id;
    }
}
