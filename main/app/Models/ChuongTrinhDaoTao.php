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
        'bac_hoc_id',
        'loai_hinh_dao_tao_id',
        'khoa_id',
        'nganh_id',
        'chuyen_nganh_id',
        'he_dao_tao_id',
        'nien_khoa_id',
        'khoa_hoc_id',
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

    public function bacHoc(): BelongsTo
    {
        return $this->belongsTo(BacHoc::class);
    }

    public function loaiHinhDaoTao(): BelongsTo
    {
        return $this->belongsTo(LoaiHinhDaoTao::class);
    }

    public function khoaHoc(): BelongsTo
    {
        return $this->belongsTo(KhoaHoc::class);
    }

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

    /**
     * Generate CTDT code automatically
     * Format: [BacHoc]-[LoaiHinh]-[MaNganhBo]-[MaHuongChuyenNganh]-K[Khoa]
     * Example: DH-CQ-7480201-CNTT-K25
     */
    public static function generateMaCtdt($bacHocMa, $loaiHinhMa, $nganhMa, $chuyenNganhMa, $khoaHocMa): string
    {
        $parts = [
            strtoupper($bacHocMa),
            strtoupper($loaiHinhMa),
            $nganhMa,
            $chuyenNganhMa ? strtoupper($chuyenNganhMa) : 'DH', // Default to DH if no chuyen nganh
            'K' . $khoaHocMa
        ];

        return implode('-', array_filter($parts));
    }

    /**
     * Check if CTDT code is unique
     */
    public static function isMaCtdtUnique($maCtdt, $excludeId = null): bool
    {
        $query = self::where('ma_ctdt', $maCtdt);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return !$query->exists();
    }

    /**
     * Validate CTDT code format
     * Only allow uppercase letters, numbers, and dash
     */
    public static function isValidMaCtdtFormat($maCtdt): bool
    {
        return preg_match('/^[A-Z0-9\-]+$/', $maCtdt) === 1;
    }
}
