<?php

namespace App\Policies;

use App\Models\Khoa;
use App\Models\User;

class KhoaPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'khoa', 'giang_vien', 'sinh_vien']);
    }

    public function view(User $user, Khoa $khoa): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Khoa $khoa): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Khoa $khoa): bool
    {
        return $user->role === 'admin';
    }
}
