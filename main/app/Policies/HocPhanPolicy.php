<?php

namespace App\Policies;

use App\Models\HocPhan;
use App\Models\User;

class HocPhanPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'khoa', 'giang_vien', 'sinh_vien']);
    }

    public function view(User $user, HocPhan $hocPhan): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->khoa_id == $hocPhan->khoa_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, HocPhan $hocPhan): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'khoa') {
            return $user->khoa_id == $hocPhan->khoa_id;
        }

        return false;
    }

    public function delete(User $user, HocPhan $hocPhan): bool
    {
        return $user->role === 'admin';
    }
}
