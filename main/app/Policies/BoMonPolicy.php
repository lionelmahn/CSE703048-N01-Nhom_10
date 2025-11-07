<?php

namespace App\Policies;

use App\Models\BoMon;
use App\Models\User;

class BoMonPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, BoMon $boMon): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, BoMon $boMon): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, BoMon $boMon): bool
    {
        return $user->role === 'admin';
    }
}
