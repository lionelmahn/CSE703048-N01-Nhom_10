<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NienKhoa extends Model
{
    protected $table = 'nien_khoa';
    protected $fillable = ['ma', 'nam_bat_dau', 'nam_ket_thuc'];

    public function khoaHocs(): HasMany
    {
        return $this->hasMany(KhoaHoc::class);
    }

    public function chuongTrinhDaoTaos(): HasMany
    {
        return $this->hasMany(ChuongTrinhDaoTao::class);
    }
}
