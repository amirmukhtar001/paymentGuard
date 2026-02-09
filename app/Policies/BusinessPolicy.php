<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;

class BusinessPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->business_id !== null;
    }

    public function view(User $user, Business $business): bool
    {
        return $user->business_id === $business->id || $user->ownedBusinesses()->where('id', $business->id)->exists();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Business $business): bool
    {
        return $user->isOwner() && $user->business_id === $business->id;
    }

    public function delete(User $user, Business $business): bool
    {
        return $user->isOwner() && $user->business_id === $business->id;
    }
}
