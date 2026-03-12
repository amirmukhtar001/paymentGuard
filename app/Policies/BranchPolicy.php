<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->business_id !== null;
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->business_id === $branch->business_id;
    }

    public function create(User $user): bool
    {
        // Allow any user that belongs to a business to create branches
        return $user->business_id !== null;
    }

    public function update(User $user, Branch $branch): bool
    {
        return ($user->isOwner() || $user->isManager()) && $user->business_id === $branch->business_id;
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->isOwner() && $user->business_id === $branch->business_id;
    }
}
