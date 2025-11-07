<?php

namespace App\Policies;

use App\Models\KhoiKienThuc;
use App\Models\User;

class KhoiKienThucPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'khoa', 'giang_vien']);
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, KhoiKienThuc $khoiKienThuc): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, KhoiKienThuc $khoiKienThuc): bool
    {
        return $user->role === 'admin';
    }
}
