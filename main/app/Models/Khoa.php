<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Khoa extends Model
{
    protected $table = 'khoa';
    protected $fillable = ['ma', 'ten', 'mo_ta', 'nguoi_phu_trach'];

    public function boMons(): HasMany
    {
        return $this->hasMany(BoMon::class);
    }

    public function hocPhans(): HasMany
    {
        return $this->hasMany(HocPhan::class);
    }

    public function chuongTrinhDaoTaos(): HasMany
    {
        return $this->hasMany(ChuongTrinhDaoTao::class);
    }

    public function nguoiPhuTrach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_phu_trach');
    }
}
