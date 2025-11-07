<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KhoaHoc extends Model
{
    protected $table = 'khoa_hoc';
    protected $fillable = ['ma', 'nien_khoa_id'];

    public function nienKhoa(): BelongsTo
    {
        return $this->belongsTo(NienKhoa::class);
    }
}
