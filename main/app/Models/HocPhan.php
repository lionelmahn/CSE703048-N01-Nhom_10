<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HocPhan extends Model
{
    protected $table = 'hoc_phan';
    protected $fillable = ['ma_hp', 'ten_hp', 'so_tinchi', 'khoa_id', 'bo_mon_id', 'mo_ta', 'active'];

    public function khoa(): BelongsTo
    {
        return $this->belongsTo(Khoa::class);
    }

    public function boMon(): BelongsTo
    {
        return $this->belongsTo(BoMon::class);
    }

    public function ctdtHocPhans(): HasMany
    {
        return $this->hasMany(CtdtHocPhan::class);
    }
}
