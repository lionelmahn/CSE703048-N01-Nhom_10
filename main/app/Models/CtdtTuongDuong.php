<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CtdtTuongDuong extends Model
{
    protected $table = 'ctdt_tuong_duong';
    protected $fillable = ['ctdt_id', 'hoc_phan_id', 'tuong_duong_hp_id', 'pham_vi', 'ghi_chu'];

    public function ctdt(): BelongsTo
    {
        return $this->belongsTo(ChuongTrinhDaoTao::class, 'ctdt_id');
    }

    public function hocPhan(): BelongsTo
    {
        return $this->belongsTo(HocPhan::class, 'hoc_phan_id');
    }

    public function tuongDuongHocPhan(): BelongsTo
    {
        return $this->belongsTo(HocPhan::class, 'tuong_duong_hp_id');
    }
}
