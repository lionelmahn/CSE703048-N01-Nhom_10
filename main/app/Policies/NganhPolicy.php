<?php

namespace App\Policies;

use App\Models\Nganh;
use App\Models\User;

class NganhPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Nganh $nganh): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Nganh $nganh): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Nganh $nganh): bool
    {
        return $user->role === 'admin';
    }
}
