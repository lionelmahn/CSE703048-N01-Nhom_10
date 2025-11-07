<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KhoiKienThuc extends Model
{
    protected $table = 'khoi_kien_thuc';
    protected $fillable = ['ma', 'ten'];

    public function ctdtKhois(): HasMany
    {
        return $this->hasMany(CtdtKhoi::class, 'khoi_id');
    }
}
