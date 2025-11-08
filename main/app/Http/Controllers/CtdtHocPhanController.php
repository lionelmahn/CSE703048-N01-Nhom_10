<?php

namespace App\Http\Controllers;

use App\Models\ChuongTrinhDaoTao;
use App\Models\HocPhan;
use App\Models\CtdtHocPhan;
use App\Models\KhoiKienThuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CtdtHocPhanController extends Controller
{
    /**
     * API: Lấy danh sách học phần của một khối kiến thức trong CTĐT
     */
    public function getHocPhansByKhoi(ChuongTrinhDaoTao $ctdt, $khoiId)
    {
        $hocPhans = CtdtHocPhan::where('ctdt_id', $ctdt->id)
            ->where('khoi_id', $khoiId)
            ->with(['hocPhan', 'khoiKienThuc'])
            ->orderBy('thu_tu')
            ->get()
            ->map(function ($ctdtHocPhan) {
                return [
                    'id' => $ctdtHocPhan->id,
                    'hoc_phan_id' => $ctdtHocPhan->hoc_phan_id,
                    'ma_hp' => $ctdtHocPhan->hocPhan->ma_hp,
                    'ten_hp' => $ctdtHocPhan->hocPhan->ten_hp,
                    'so_tinchi' => $ctdtHocPhan->hocPhan->so_tinchi,
                    'loai' => $ctdtHocPhan->loai,
                    'thu_tu' => $ctdtHocPhan->thu_tu,
                    'khoi_ten' => $ctdtHocPhan->khoiKienThuc->ten ?? 'N/A',
                ];
            });

        return response()->json($hocPhans);
    }

    /**
     * API: Lấy danh sách học phần có thể thêm (chưa thuộc CTĐT hoặc khối cụ thể)
     */
    public function getAvailableHocPhans(Request $request, ChuongTrinhDaoTao $ctdt)
    {
        $khoiId = $request->query('khoi_id');
        $search = $request->query('search', '');
        $khoaId = $request->query('khoa_id');
        $boMonId = $request->query('bo_mon_id');
        $perPage = $request->query('per_page', 15);

        // Lấy danh sách học phần đã có trong CTĐT và khối này
        $existingHocPhanIds = CtdtHocPhan::where('ctdt_id', $ctdt->id)
            ->where('khoi_id', $khoiId)
            ->pluck('hoc_phan_id')
            ->toArray();

        $query = HocPhan::query()
            ->with(['khoa', 'boMon'])
            ->where('trang_thai', 'active') // BR2: Chỉ học phần có trạng thái "Hoạt động"
            ->whereNotIn('id', $existingHocPhanIds); // BR1: Không trùng lặp

        // Tìm kiếm theo tên hoặc mã
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ma_hp', 'like', "%{$search}%")
                    ->orWhere('ten_hp', 'like', "%{$search}%");
            });
        }

        // Lọc theo khoa
        if ($khoaId) {
            $query->where('khoa_id', $khoaId);
        }

        // Lọc theo bộ môn
        if ($boMonId) {
            $query->where('bo_mon_id', $boMonId);
        }

        $hocPhans = $query->orderBy('ma_hp')->paginate($perPage);

        return response()->json([
            'data' => $hocPhans->items(),
            'current_page' => $hocPhans->currentPage(),
            'last_page' => $hocPhans->lastPage(),
            'total' => $hocPhans->total(),
        ]);
    }

    /**
     * Lưu học phần vào CTĐT (batch)
     */
    public function store(Request $request, ChuongTrinhDaoTao $ctdt)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.hoc_phan_id' => 'required|exists:hoc_phan,id',
            'items.*.khoi_id' => 'required|exists:khoi_kien_thuc,id',
            'items.*.loai' => 'required|in:bat_buoc,tu_chon',
            'items.*.thu_tu' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $errors = [];
            $successCount = 0;

            foreach ($validated['items'] as $index => $item) {
                // BR1: Kiểm tra trùng lặp
                $exists = CtdtHocPhan::where('ctdt_id', $ctdt->id)
                    ->where('hoc_phan_id', $item['hoc_phan_id'])
                    ->where('khoi_id', $item['khoi_id'])
                    ->exists();

                if ($exists) {
                    $hocPhan = HocPhan::find($item['hoc_phan_id']);
                    $khoi = KhoiKienThuc::find($item['khoi_id']);
                    $errors[] = "Học phần '{$hocPhan->ten_hp}' đã tồn tại trong khối '{$khoi->ten}'";
                    continue;
                }

                // BR2: Kiểm tra trạng thái học phần
                $hocPhan = HocPhan::find($item['hoc_phan_id']);
                if ($hocPhan->trang_thai !== 'active') {
                    $errors[] = "Học phần '{$hocPhan->ten_hp}' không có trạng thái 'Hoạt động'";
                    continue;
                }

                // Tạo liên kết
                CtdtHocPhan::create([
                    'ctdt_id' => $ctdt->id,
                    'hoc_phan_id' => $item['hoc_phan_id'],
                    'khoi_id' => $item['khoi_id'],
                    'loai' => $item['loai'],
                    'thu_tu' => $item['thu_tu'] ?? 0,
                ]);

                $successCount++;
            }

            DB::commit();

            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Một số học phần không thể thêm',
                    'errors' => $errors,
                    'success_count' => $successCount,
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => "Đã thêm thành công {$successCount} học phần vào CTĐT",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Xóa học phần khỏi CTĐT
     */
    public function destroy(ChuongTrinhDaoTao $ctdt, HocPhan $hocPhan)
    {
        $ctdtHocPhan = CtdtHocPhan::where('ctdt_id', $ctdt->id)
            ->where('hoc_phan_id', $hocPhan->id)
            ->firstOrFail();

        $ctdtHocPhan->delete();

        return redirect()->back()->with('success', 'Đã xóa học phần khỏi CTĐT');
    }
}
