<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChuongTrinhDaoTao extends Model
{

    protected $table = 'chuong_trinh_dao_tao';
    protected $fillable = [
        'ma_ctdt',
        'ten',
        'khoa_id',
        'nganh_id',
        'chuyen_nganh_id',
        'he_dao_tao_id',
        'nien_khoa_id',
        'trang_thai',
        'hieu_luc_tu',
        'hieu_luc_den',
        'mo_ta',
        'created_by',
        'ly_do_tra_ve'
    ];

    protected $casts = [
        'hieu_luc_tu' => 'date',
        'hieu_luc_den' => 'date',
    ];


    public function khoa(): BelongsTo
    {
        return $this->belongsTo(Khoa::class);
    }

    public function nganh(): BelongsTo
    {
        return $this->belongsTo(Nganh::class);
    }

    public function chuyenNganh(): BelongsTo
    {
        return $this->belongsTo(ChuyenNganh::class);
    }

    public function heDaoTao(): BelongsTo
    {
        return $this->belongsTo(HeDaoTao::class);
    }

    public function nienKhoa(): BelongsTo
    {
        return $this->belongsTo(NienKhoa::class);
    }

    public function nguoiTao(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function ctdtKhois(): HasMany
    {
        return $this->hasMany(CtdtKhoi::class, 'ctdt_id');
    }

    public function ctdtHocPhans(): HasMany
    {
        return $this->hasMany(CtdtHocPhan::class, 'ctdt_id');
    }

    public function ctdtRangBuocs(): HasMany
    {
        return $this->hasMany(CtdtRangBuoc::class, 'ctdt_id');
    }

    public function ctdtTuongDuongs(): HasMany
    {
        return $this->hasMany(CtdtTuongDuong::class, 'ctdt_id');
    }
}
