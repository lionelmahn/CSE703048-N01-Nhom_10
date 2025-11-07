<?php

namespace App\Policies;

use App\Models\HeDaoTao;
use App\Models\User;

class HeDaoTaoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, HeDaoTao $heDaoTao): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, HeDaoTao $heDaoTao): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, HeDaoTao $heDaoTao): bool
    {
        return $user->role === 'admin';
    }
}
