<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nganh extends Model
{
    protected $table = 'nganh';
    protected $fillable = ['ma', 'ten'];


    public function chuyenNganhs(): HasMany
    {
        return $this->hasMany(ChuyenNganh::class);
    }

    public function chuongTrinhDaoTaos(): HasMany
    {
        return $this->hasMany(ChuongTrinhDaoTao::class);
    }
}
