<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CtdtKhoi extends Model
{
    protected $table = 'ctdt_khoi';
    protected $fillable = ['ctdt_id', 'khoi_id', 'thu_tu', 'ghi_chu'];

    public function ctdt(): BelongsTo
    {
        return $this->belongsTo(ChuongTrinhDaoTao::class, 'ctdt_id');
    }

    public function khoi(): BelongsTo
    {
        return $this->belongsTo(KhoiKienThuc::class, 'khoi_id');
    }
}
