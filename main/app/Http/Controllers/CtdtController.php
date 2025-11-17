<?php

namespace App\Http\Controllers;

use App\Models\ChuongTrinhDaoTao;
use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\ChuyenNganh;
use App\Models\HeDaoTao;
use App\Models\NienKhoa;
use App\Models\KhoaHoc;
use App\Models\BacHoc;
use App\Models\LoaiHinhDaoTao;
use App\Models\HocPhan;
use App\Http\Requests\StoreCTDTRequest;
use App\Http\Requests\UpdateCTDTRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CtdtController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = ChuongTrinhDaoTao::query();

        if ($user->role !== 'admin') {
            $query->where('khoa_id', $user->khoa_id);
        }

        $ctdts = $query->with(['khoa', 'nganh', 'chuyenNganh', 'nienKhoa', 'nguoiTao', 'bacHoc', 'loaiHinhDaoTao', 'khoaHoc', 'heDaoTao'])
            ->paginate(15);

        return view('ctdt.index', compact('ctdts'));
    }

    public function create(Request $request)
    {
        $mode = $request->get('mode', 'new'); // 'new' or 'copy'

        $user = Auth::user();
        $khoasQuery = Khoa::query();

        if ($user->role !== 'admin') {
            $khoasQuery->where('id', $user->khoa_id);
        }

        $khoas = $khoasQuery->get();
        $nganhs = Nganh::all();
        $chuyenNganhs = ChuyenNganh::all();
        $nienKhoas = NienKhoa::orderBy('nam_bat_dau', 'desc')->get();
        $khoaHocs = KhoaHoc::with('nienKhoa')->get();
        $bacHocs = BacHoc::all();
        $loaiHinhDaoTaos = LoaiHinhDaoTao::all();
        $heDaoTaos = HeDaoTao::all();

        // If copy mode, get list of CTDTs to copy from (only approved or published)
        $ctdtsForCopy = [];
        if ($mode === 'copy') {
            $ctdtsForCopy = ChuongTrinhDaoTao::whereIn('trang_thai', ['da_phe_duyet', 'da_cong_bo'])
                ->with(['bacHoc', 'loaiHinhDaoTao', 'nganh', 'chuyenNganh', 'khoaHoc'])
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        return view('ctdt.create', compact(
            'mode',
            'khoas',
            'nganhs',
            'chuyenNganhs',
            'nienKhoas',
            'khoaHocs',
            'bacHocs',
            'loaiHinhDaoTaos',
            'heDaoTaos',
            'ctdtsForCopy'
        ));
    }

    public function store(StoreCTDTRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = Auth::id();
        $validated['trang_thai'] = 'draft';

        // If copying, duplicate structure
        if ($request->has('source_ctdt_id') && $request->source_ctdt_id) {
            $sourceCtdt = ChuongTrinhDaoTao::findOrFail($request->source_ctdt_id);

            $ctdt = ChuongTrinhDaoTao::create($validated);

            // Copy khoi kien thuc structure
            foreach ($sourceCtdt->ctdtKhois as $ctdtKhoi) {
                $ctdt->ctdtKhois()->create([
                    'khoi_id' => $ctdtKhoi->khoi_id,
                    'ghi_chu' => $ctdtKhoi->ghi_chu,
                ]);
            }

            // Copy hoc phan structure
            foreach ($sourceCtdt->ctdtHocPhans as $ctdtHocPhan) {
                $ctdt->ctdtHocPhans()->create([
                    'hoc_phan_id' => $ctdtHocPhan->hoc_phan_id,
                    'khoi_id' => $ctdtHocPhan->khoi_id,
                    'hoc_ky' => $ctdtHocPhan->hoc_ky,
                    'loai' => $ctdtHocPhan->loai,
                    'thu_tu' => $ctdtHocPhan->thu_tu,
                    'ghi_chu' => $ctdtHocPhan->ghi_chu,
                ]);
            }

            // Copy rang buoc
            foreach ($sourceCtdt->ctdtRangBuocs as $rangBuoc) {
                $ctdt->ctdtRangBuocs()->create([
                    'hoc_phan_truoc_id' => $rangBuoc->hoc_phan_truoc_id,
                    'hoc_phan_sau_id' => $rangBuoc->hoc_phan_sau_id,
                    'loai_rang_buoc' => $rangBuoc->loai_rang_buoc,
                    'ghi_chu' => $rangBuoc->ghi_chu,
                ]);
            }

            // Copy tuong duong
            foreach ($sourceCtdt->ctdtTuongDuongs as $tuongDuong) {
                $ctdt->ctdtTuongDuongs()->create([
                    'hoc_phan_1_id' => $tuongDuong->hoc_phan_1_id,
                    'hoc_phan_2_id' => $tuongDuong->hoc_phan_2_id,
                    'ghi_chu' => $tuongDuong->ghi_chu,
                ]);
            }

            return redirect()->route('ctdt.show', $ctdt)->with('success', 'Sao chép CTĐT thành công');
        }

        // Normal create
        $ctdt = ChuongTrinhDaoTao::create($validated);

        return redirect()->route('ctdt.show', $ctdt)->with('success', 'Tạo CTĐT thành công');
    }

    public function generateCode(Request $request)
    {
        $request->validate([
            'bac_hoc_id' => 'required|exists:bac_hoc,id',
            'loai_hinh_dao_tao_id' => 'required|exists:loai_hinh_dao_tao,id',
            'nganh_id' => 'required|exists:nganh,id',
            'khoa_hoc_id' => 'required|exists:khoa_hoc,id',
        ]);

        $bacHoc = BacHoc::find($request->bac_hoc_id);
        $loaiHinh = LoaiHinhDaoTao::find($request->loai_hinh_dao_tao_id);
        $nganh = Nganh::find($request->nganh_id);
        $chuyenNganh = $request->chuyen_nganh_id ? ChuyenNganh::find($request->chuyen_nganh_id) : null;
        $khoaHoc = KhoaHoc::find($request->khoa_hoc_id);

        $maCtdt = ChuongTrinhDaoTao::generateMaCtdt(
            $bacHoc->ma,
            $loaiHinh->ma,
            $nganh->ma,
            $chuyenNganh?->ma,
            $khoaHoc->ma
        );

        $isUnique = ChuongTrinhDaoTao::isMaCtdtUnique($maCtdt, $request->ctdt_id);

        return response()->json([
            'ma_ctdt' => $maCtdt,
            'is_unique' => $isUnique,
            'message' => $isUnique ? 'Mã CTĐT hợp lệ' : 'Mã CTĐT đã tồn tại trong hệ thống'
        ]);
    }

    public function show(ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('view', $ctdt);

        $ctdt->load([
            'ctdtKhois.khoi',
            'ctdtHocPhans.hocPhan',
            'ctdtHocPhans.khoi',
            'ctdtRangBuocs',
            'ctdtTuongDuongs',
            'khoa',
            'nganh',
            'chuyenNganh',
            'nienKhoa',
            'khoaHoc',
            'bacHoc',
            'loaiHinhDaoTao',
            'heDaoTao',
            'nguoiTao'
        ]);

        $allKhoiKienThuc = \App\Models\KhoiKienThuc::orderBy('ma')->get();

        return view('ctdt.show', compact('ctdt', 'allKhoiKienThuc'));
    }

    public function edit(ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('update', $ctdt);

        $user = Auth::user();
        $khoasQuery = Khoa::query();

        if ($user->role !== 'admin') {
            $khoasQuery->where('id', $user->khoa_id);
        }

        $khoas = $khoasQuery->get();
        $nienKhoas = NienKhoa::orderBy('nam_bat_dau', 'desc')->get();
        $khoaHocs = KhoaHoc::with('nienKhoa')->get();
        $bacHocs = BacHoc::all();
        $loaiHinhDaoTaos = LoaiHinhDaoTao::all();
        $heDaoTaos = HeDaoTao::all();
        $nganhs = Nganh::all();
        $chuyenNganhs = $ctdt->nganh_id ? ChuyenNganh::where('nganh_id', $ctdt->nganh_id)->get() : [];

        return view('ctdt.edit', compact('ctdt', 'khoas', 'nienKhoas', 'khoaHocs', 'bacHocs', 'loaiHinhDaoTaos', 'heDaoTaos', 'nganhs', 'chuyenNganhs'));
    }

    public function update(UpdateCTDTRequest $request, ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('update', $ctdt);

        $validated = $request->validated();
        $ctdt->update($validated);


        return redirect()->route('ctdt.show', $ctdt)->with('success', 'Cập nhật CTĐT thành công');
    }

    public function destroy(ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('delete', $ctdt);

        $ctdt->delete();


        return redirect()->route('ctdt.index')->with('success', 'Xóa CTĐT thành công');
    }

    public function sendForApproval(ChuongTrinhDaoTao $ctdt)
    {
        $this->authorize('update', $ctdt);

        // BR3: Chỉ cho phép gửi nếu ở trạng thái "draft" hoặc "can_chinh_sua"
        if (!in_array($ctdt->trang_thai, ['draft', 'can_chinh_sua'])) {
            return back()->with('error', 'Chỉ có thể gửi phê duyệt CTĐT ở trạng thái "Bản nháp" hoặc "Cần chỉnh sửa".');
        }

        // BR1: Validation - Tổng tín chỉ tối thiểu (ví dụ: 120 tín chỉ)
        $tongTinChi = $ctdt->ctdtHocPhans()->with('hocPhan')->get()->sum(function ($item) {
            return $item->hocPhan->so_tinchi;
        });

        $minTinChi = 20; // Có thể config theo bậc học
        if ($tongTinChi < $minTinChi) {
            return back()->with('error', "Không thể gửi: Tổng tín chỉ ($tongTinChi) chưa đạt mức tối thiểu ($minTinChi).");
        }

        // BR2: Validation - Phải có đầy đủ khối kiến thức
        $soKhoiCoHocPhan = $ctdt->ctdtHocPhans()->distinct('khoi_id')->count('khoi_id');
        if ($soKhoiCoHocPhan < 1) {
            return back()->with('error', 'Không thể gửi: Chưa có học phần nào được thêm vào CTĐT.');
        }

        // BR4: Chuyển trạng thái sang "Chờ phê duyệt"
        $ctdt->update([
            'trang_thai' => 'cho_phe_duyet',
            'ly_do_tra_ve' => null // Xóa lý do từ chối cũ nếu có
        ]);

        // TODO: Gửi notification đến người phê duyệt (BR6)

        return back()->with('success', 'Đã gửi phê duyệt thành công. CTĐT đã bị khóa và chờ xét duyệt.');
    }
}
