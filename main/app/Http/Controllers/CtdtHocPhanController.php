<?php

namespace App\Http\Controllers;

use App\Models\ChuongTrinhDaoTao;
use App\Models\HocPhan;
use App\Models\CtdtHocPhan;
use App\Models\KhoiKienThuc;
use App\Models\Khoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CtdtHocPhanController extends Controller
{
    /**
     * Hiển thị màn hình quản lý học phần của CTĐT (UC14.6)
     */
    public function manage($ctdtId)
    {
        $ctdt = ChuongTrinhDaoTao::with([
            'ctdtKhois.khoi',
            'ctdtHocPhans.hocPhan',
            'ctdtHocPhans.khoi'
        ])->findOrFail($ctdtId);

        // Check permission - only draft or can_chinh_sua status
        if (!in_array($ctdt->trang_thai, ['draft', 'can_chinh_sua'])) {
            return redirect()->route('ctdt.show', $ctdtId)
                ->with('error', 'CTĐT này không thể chỉnh sửa.');
        }

        // Get all Khoa for filter dropdown
        $khoas = Khoa::orderBy('ten')->get();

        return view('ctdt.manage-hoc-phan', compact('ctdt', 'khoas'));
    }

    /**
     * API: Tìm kiếm và lọc học phần từ thư viện (Cột 1)
     */
    public function searchHocPhan(Request $request)
    {
        $query = HocPhan::with(['khoa', 'boMon'])
            ->where('active', true);

        // Search by name or code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ten_hp', 'like', "%{$search}%")
                    ->orWhere('ma_hp', 'like', "%{$search}%");
            });
        }

        // Filter by Khoa
        if ($request->filled('khoa_id')) {
            $query->where('khoa_id', $request->khoa_id);
        }

        // Filter by Bo Mon
        if ($request->filled('bo_mon_id')) {
            $query->where('bo_mon_id', $request->bo_mon_id);
        }

        $hocPhans = $query->orderBy('ma_hp')->paginate(20);

        return response()->json($hocPhans);
    }

    /**
     * API: Lấy cấu trúc cây khối kiến thức của CTĐT (Cột 2)
     */
    public function getCtdtStructure($ctdtId)
    {
        try {
            Log::info("Loading CTDT structure for ID: {$ctdtId}");

            $ctdt = ChuongTrinhDaoTao::with([
                'ctdtKhois.khoi',
                'ctdtHocPhans' => function ($query) {
                    $query->with('hocPhan')->orderBy('thu_tu');
                }
            ])->findOrFail($ctdtId);

            Log::info("CTDT loaded: {$ctdt->id}, Khois: " . $ctdt->ctdtKhois->count());

            // Build tree structure for frontend
            $structure = [];
            foreach ($ctdt->ctdtKhois as $ctdtKhoi) {
                if (!$ctdtKhoi->khoi) {
                    Log::warning("CtdtKhoi {$ctdtKhoi->id} has no khoi relationship");
                    continue;
                }

                $khoiData = [
                    'id' => $ctdtKhoi->id,
                    'khoi_id' => $ctdtKhoi->khoi_id,
                    'ten' => $ctdtKhoi->khoi->ten,
                    'ma' => $ctdtKhoi->khoi->ma,
                    'hoc_phans' => []
                ];

                // Get hoc phans belonging to this khoi
                $hocPhans = $ctdt->ctdtHocPhans->where('khoi_id', $ctdtKhoi->khoi_id);
                foreach ($hocPhans as $ctdtHp) {
                    if (!$ctdtHp->hocPhan) {
                        Log::warning("CtdtHocPhan {$ctdtHp->id} has no hocPhan relationship");
                        continue;
                    }

                    $khoiData['hoc_phans'][] = [
                        'id' => $ctdtHp->id,
                        'hoc_phan_id' => $ctdtHp->hoc_phan_id,
                        'ma_hp' => $ctdtHp->hocPhan->ma_hp,
                        'ten_hp' => $ctdtHp->hocPhan->ten_hp,
                        'so_tinchi' => $ctdtHp->hocPhan->so_tinchi,
                        'loai' => $ctdtHp->loai,
                        'thu_tu' => $ctdtHp->thu_tu
                    ];
                }

                $structure[] = $khoiData;
            }

            Log::info("Structure built with " . count($structure) . " khois");

            return response()->json([
                'ctdt_id' => $ctdt->id,
                'ten_ctdt' => $ctdt->ten,
                'structure' => $structure
            ]);
        } catch (\Exception $e) {
            Log::error("Error loading CTDT structure: " . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'error' => 'Không thể tải cấu trúc CTĐT',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Lưu tất cả thay đổi (thêm/xóa học phần) - UC14.6
     */
    public function saveChanges(Request $request, $ctdtId)
    {
        $request->validate([
            'additions' => 'array',
            'additions.*.hoc_phan_id' => 'required|exists:hoc_phan,id',
            'additions.*.khoi_id' => 'required|exists:khoi_kien_thuc,id',
            'additions.*.loai' => 'nullable|in:bat_buoc,tu_chon',
            'deletions' => 'array',
            'deletions.*' => 'exists:ctdt_hoc_phan,id',
            'new_khois' => 'array',
            'new_khois.*' => 'exists:khoi_kien_thuc,id'
        ]);

        $ctdt = ChuongTrinhDaoTao::findOrFail($ctdtId);

        // Check permission
        if (!in_array($ctdt->trang_thai, ['draft', 'can_chinh_sua'])) {
            return response()->json([
                'success' => false,
                'errors' => ['CTĐT này không thể chỉnh sửa.']
            ], 403);
        }

        DB::beginTransaction();
        try {
            $errors = [];

            $newKhoiIds = [];
            foreach ($request->additions ?? [] as $addition) {
                if (!in_array($addition['khoi_id'], $newKhoiIds)) {
                    // Check if this khoi is new (not in ctdt_khoi yet)
                    $exists = DB::table('ctdt_khoi')
                        ->where('ctdt_id', $ctdtId)
                        ->where('khoi_id', $addition['khoi_id'])
                        ->exists();

                    if (!$exists) {
                        $newKhoiIds[] = $addition['khoi_id'];
                    }
                }
            }

            // Add new khois to CTDT
            foreach ($newKhoiIds as $khoiId) {
                $maxThuTu = DB::table('ctdt_khoi')
                    ->where('ctdt_id', $ctdtId)
                    ->max('thu_tu') ?? 0;

                DB::table('ctdt_khoi')->insert([
                    'ctdt_id' => $ctdtId,
                    'khoi_id' => $khoiId,
                    'thu_tu' => $maxThuTu + 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Process additions
            if (!empty($request->additions)) {
                foreach ($request->additions as $addition) {
                    // BR1: Check duplicate in same khoi
                    $exists = CtdtHocPhan::where('ctdt_id', $ctdtId)
                        ->where('hoc_phan_id', $addition['hoc_phan_id'])
                        ->where('khoi_id', $addition['khoi_id'])
                        ->exists();

                    if ($exists) {
                        $hocPhan = HocPhan::find($addition['hoc_phan_id']);
                        $khoi = KhoiKienThuc::find($addition['khoi_id']);
                        $errors[] = "Học phần '{$hocPhan->ten_hp}' đã tồn tại trong khối '{$khoi->ten}'.";
                        continue;
                    }

                    // BR2: Check if hoc phan is active
                    $hocPhan = HocPhan::find($addition['hoc_phan_id']);
                    if (!$hocPhan->active) {
                        $errors[] = "Học phần '{$hocPhan->ten_hp}' không ở trạng thái Hoạt động.";
                        continue;
                    }

                    // Get max thu_tu for this khoi
                    $maxThuTu = CtdtHocPhan::where('ctdt_id', $ctdtId)
                        ->where('khoi_id', $addition['khoi_id'])
                        ->max('thu_tu') ?? 0;

                    // Save hoc phan to CTDT
                    CtdtHocPhan::create([
                        'ctdt_id' => $ctdtId,
                        'hoc_phan_id' => $addition['hoc_phan_id'],
                        'khoi_id' => $addition['khoi_id'],
                        'loai' => $addition['loai'] ?? 'bat_buoc',
                        'thu_tu' => $maxThuTu + 1
                    ]);
                }
            }

            // Process deletions
            if (!empty($request->deletions)) {
                foreach ($request->deletions as $ctdtHocPhanId) {
                    $ctdtHocPhan = CtdtHocPhan::where('id', $ctdtHocPhanId)
                        ->where('ctdt_id', $ctdtId)
                        ->first();

                    if ($ctdtHocPhan) {
                        $ctdtHocPhan->delete();
                    }
                }
            }

            // If validation errors, rollback and return errors
            if (!empty($errors)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'errors' => $errors
                ], 422);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Lưu thay đổi thành công!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving CTDT hoc phan changes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'errors' => ['Có lỗi xảy ra khi lưu thay đổi.']
            ], 500);
        }
    }

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

    /**
     * API: Lấy danh sách khối kiến thức có thể thêm vào CTĐT (chưa có trong CTĐT)
     */
    public function getAvailableKhoiKienThuc($ctdtId)
    {
        try {
            Log::info("Loading available khoi for CTDT: {$ctdtId}");

            $ctdt = ChuongTrinhDaoTao::with('ctdtKhois')->findOrFail($ctdtId);

            // Get khoi IDs already in CTDT
            $existingKhoiIds = $ctdt->ctdtKhois->pluck('khoi_id')->toArray();

            Log::info("Existing khoi IDs: " . json_encode($existingKhoiIds));

            // Get all khoi not in CTDT yet
            $availableKhois = KhoiKienThuc::whereNotIn('id', $existingKhoiIds)
                ->orderBy('ten')
                ->get(['id', 'ma', 'ten']);

            Log::info("Available khois count: " . $availableKhois->count());

            return response()->json($availableKhois);
        } catch (\Exception $e) {
            Log::error("Error loading available khoi: " . $e->getMessage());
            return response()->json([
                'error' => 'Không thể tải danh sách khối kiến thức',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
