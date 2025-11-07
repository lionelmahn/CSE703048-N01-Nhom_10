<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoMon extends Model
{
    protected $table = 'bo_mon';
    protected $fillable = ['ma', 'ten', 'khoa_id'];

    public function khoa(): BelongsTo
    {
        return $this->belongsTo(Khoa::class);
    }

    public function hocPhans(): HasMany
    {
        return $this->hasMany(HocPhan::class);
    }
}
