<?php

namespace App\Services;

use App\Models\HocPhan;
use Illuminate\Support\Collection;

class HocPhanService
{
    /**
     * Import học phần từ file Excel
     */
    public function importFromExcel($file, $khoaId): Collection
    {
        // TODO: Implement Excel import
        return collect();
    }

    /**
     * Lấy danh sách học phần theo khoa
     */
    public function getByKhoa($khoaId)
    {
        return HocPhan::where('khoa_id', $khoaId)
            ->where('active', true)
            ->orderBy('ma_hp')
            ->get();
    }
}
