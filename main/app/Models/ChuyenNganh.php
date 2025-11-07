<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChuyenNganh extends Model
{
    protected $table = 'chuyen_nganh';
    protected $fillable = ['ma', 'ten', 'nganh_id'];

    public function nganh(): BelongsTo
    {
        return $this->belongsTo(Nganh::class);
    }

    public function chuongTrinhDaoTaos(): HasMany
    {
        return $this->hasMany(ChuongTrinhDaoTao::class);
    }
}
