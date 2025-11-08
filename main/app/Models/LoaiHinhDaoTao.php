<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoaiHinhDaoTao extends Model
{
    protected $table = 'loai_hinh_dao_tao';
    protected $fillable = ['ma', 'ten'];

    public function chuongTrinhDaoTaos(): HasMany
    {
        return $this->hasMany(ChuongTrinhDaoTao::class);
    }
}
