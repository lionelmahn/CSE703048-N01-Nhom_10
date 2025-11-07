<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ChuyenNganh;

class ChuyenNganhPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, ChuyenNganh $chuyenNganh): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, ChuyenNganh $chuyenNganh): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, ChuyenNganh $chuyenNganh): bool
    {
        return $user->role === 'admin';
    }
}
