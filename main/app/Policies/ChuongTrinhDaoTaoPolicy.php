<?php

namespace App\Policies;

use App\Models\ChuongTrinhDaoTao;
use App\Models\User;

class ChuongTrinhDaoTaoPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'khoa', 'giang_vien', 'sinh_vien']);
    }

    public function view(User $user, ChuongTrinhDaoTao $ctdt): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->khoa_id == $ctdt->khoa_id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'khoa']);
    }

    public function update(User $user, ChuongTrinhDaoTao $ctdt): bool
    {
        if (!in_array($user->role, ['admin', 'khoa'])) {
            return false;
        }

        if ($user->role === 'khoa' && $user->khoa_id != $ctdt->khoa_id) {
            return false;
        }

        return in_array($ctdt->trang_thai, ['draft', 'pending', 'approved']);
    }

    public function delete(User $user, ChuongTrinhDaoTao $ctdt): bool
    {
        if (!in_array($user->role, ['admin', 'khoa'])) {
            return false;
        }

        if ($user->role === 'khoa' && $user->khoa_id != $ctdt->khoa_id) {
            return false;
        }

        return $ctdt->trang_thai === 'draft';
    }

    public function sendForApproval(User $user, ChuongTrinhDaoTao $ctdt): bool
    {
        if ($user->role !== 'khoa' || $user->khoa_id != $ctdt->khoa_id) {
            return false;
        }

        return $ctdt->trang_thai === 'draft';
    }

    public function approve(User $user, ChuongTrinhDaoTao $ctdt): bool
    {
        if ($user->role !== 'admin') {
            return false;
        }

        return $ctdt->trang_thai === 'pending';
    }

    public function publish(User $user, ChuongTrinhDaoTao $ctdt): bool
    {
        if ($user->role !== 'admin') {
            return false;
        }

        return $ctdt->trang_thai === 'approved';
    }

    public function rejectForRevision(User $user, ChuongTrinhDaoTao $ctdt): bool
    {
        if ($user->role !== 'admin') {
            return false;
        }

        return $ctdt->trang_thai === 'pending';
    }
}
