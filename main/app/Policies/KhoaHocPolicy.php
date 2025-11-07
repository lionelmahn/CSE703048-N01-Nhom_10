<?php

namespace App\Policies;

use App\Models\User;
use App\Models\KhoaHoc;

class KhoaHocPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, KhoaHoc $khoaHoc): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, KhoaHoc $khoaHoc): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, KhoaHoc $khoaHoc): bool
    {
        return $user->role === 'admin';
    }
}
