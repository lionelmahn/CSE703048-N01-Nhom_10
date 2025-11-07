<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeDaoTao extends Model
{
    protected $table = 'he_dao_tao';
    protected $fillable = ['ma', 'ten'];

    public function nganhs(): HasMany
    {
        return $this->hasMany(Nganh::class);
    }

    public function chuongTrinhDaoTaos(): HasMany
    {
        return $this->hasMany(ChuongTrinhDaoTao::class);
    }
}
