<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NienKhoa;

class NienKhoaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'khoa', 'giang_vien']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, NienKhoa $nienKhoa): bool
    {
        return in_array($user->role, ['admin', 'khoa', 'giang_vien']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, NienKhoa $nienKhoa): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, NienKhoa $nienKhoa): bool
    {
        // Admin can delete if not used in any CTDT
        return $user->role === 'admin' && $nienKhoa->chuongTrinhDaoTaos()->count() === 0;
    }
}
