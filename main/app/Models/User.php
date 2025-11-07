<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'khoa_id',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function khoa(): BelongsTo
    {
        return $this->belongsTo(Khoa::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKhoa(): bool
    {
        return $this->role === 'khoa';
    }

    public function isGiangVien(): bool
    {
        return $this->role === 'giang_vien';
    }

    public function isSinhVien(): bool
    {
        return $this->role === 'sinh_vien';
    }
}
