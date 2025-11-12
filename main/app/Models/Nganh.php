<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nganh extends Model
{
    protected $table = 'nganh';
    protected $fillable = ['ma', 'ten', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function chuyenNganhs(): HasMany
    {
        return $this->hasMany(ChuyenNganh::class);
    }

    public function chuongTrinhDaoTaos(): HasMany
    {
        return $this->hasMany(ChuongTrinhDaoTao::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function canBeDeactivated(): bool
    {
        // Cannot deactivate if there are active CTDTs
        return !$this->chuongTrinhDaoTaos()
            ->whereIn('trang_thai', ['cho_phe_duyet', 'da_phe_duyet'])
            ->exists();
    }
}
