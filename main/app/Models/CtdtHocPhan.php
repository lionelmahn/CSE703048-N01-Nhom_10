<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CtdtHocPhan extends Model
{
    protected $table = 'ctdt_hoc_phan';
    protected $fillable = ['ctdt_id', 'hoc_phan_id', 'khoi_id', 'hoc_ky', 'loai', 'thu_tu', 'ghi_chu'];

    public function ctdt(): BelongsTo
    {
        return $this->belongsTo(ChuongTrinhDaoTao::class, 'ctdt_id');
    }

    public function hocPhan(): BelongsTo
    {
        return $this->belongsTo(HocPhan::class, 'hoc_phan_id');
    }

    public function khoi(): BelongsTo
    {
        return $this->belongsTo(KhoiKienThuc::class, 'khoi_id');
    }
}
