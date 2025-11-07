<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        // Admin can view all users
        return $user->role === 'admin';
    }

    public function view(User $user, User $model): bool
    {
        // Admin can view any user
        // Users can view their own profile
        return $user->role === 'admin' || $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        // Only admin can create users
        return $user->role === 'admin';
    }

    public function update(User $user, User $model): bool
    {
        // Admin can update any user
        // Users can update their own profile
        return $user->role === 'admin' || $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        // Only admin can delete users
        // Cannot delete yourself
        return $user->role === 'admin' && $user->id !== $model->id;
    }
}
