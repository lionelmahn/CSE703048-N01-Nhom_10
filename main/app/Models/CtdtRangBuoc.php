<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CtdtRangBuoc extends Model
{
    protected $table = 'ctdt_rang_buoc';
    protected $fillable = ['ctdt_id', 'hoc_phan_id', 'lien_quan_hp_id', 'kieu', 'logic_nhom', 'nhom', 'ghi_chu'];

    public function ctdt(): BelongsTo
    {
        return $this->belongsTo(ChuongTrinhDaoTao::class, 'ctdt_id');
    }

    public function hocPhan(): BelongsTo
    {
        return $this->belongsTo(HocPhan::class, 'hoc_phan_id');
    }

    public function lienQuanHocPhan(): BelongsTo
    {
        return $this->belongsTo(HocPhan::class, 'lien_quan_hp_id');
    }
}
