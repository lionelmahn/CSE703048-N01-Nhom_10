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

        $ctdts = $query->with(['khoa', 'nganh', 'chuyenNganh', 'heDaoTao', 'nienKhoa', 'nguoiTao', 'bacHoc', 'loaiHinhDaoTao', 'khoaHoc'])
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
        $heDaoTaos = HeDaoTao::all();
        $nienKhoas = NienKhoa::orderBy('nam_bat_dau', 'desc')->get();
        $khoaHocs = KhoaHoc::with('nienKhoa')->get();
        $bacHocs = BacHoc::all();
        $loaiHinhDaoTaos = LoaiHinhDaoTao::all();

        // If copy mode, get list of CTDTs to copy from
        $ctdtsForCopy = [];
        if ($mode === 'copy') {
            $ctdtsForCopy = ChuongTrinhDaoTao::whereIn('trang_thai', ['approved', 'published'])
                ->with(['bacHoc', 'loaiHinhDaoTao', 'nganh', 'chuyenNganh', 'khoaHoc'])
                ->get();
        }

        return view('ctdt.create', compact(
            'mode',
            'khoas',
            'nganhs',
            'chuyenNganhs',
            'heDaoTaos',
            'nienKhoas',
            'khoaHocs',
            'bacHocs',
            'loaiHinhDaoTaos',
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
                    'khoi_kien_thuc_id' => $ctdtKhoi->khoi_kien_thuc_id,
                    'so_tc_bat_buoc' => $ctdtKhoi->so_tc_bat_buoc,
                    'so_tc_tu_chon' => $ctdtKhoi->so_tc_tu_chon,
                    'ghi_chu' => $ctdtKhoi->ghi_chu,
                ]);
            }

            // Copy hoc phan structure
            foreach ($sourceCtdt->ctdtHocPhans as $ctdtHocPhan) {
                $ctdt->ctdtHocPhans()->create([
                    'hoc_phan_id' => $ctdtHocPhan->hoc_phan_id,
                    'khoi_kien_thuc_id' => $ctdtHocPhan->khoi_kien_thuc_id,
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
            'ctdtKhois.khoiKienThuc',
            'ctdtHocPhans.hocPhan',
            'ctdtRangBuocs',
            'ctdtTuongDuongs',
            'khoa',
            'nganh',
            'chuyenNganh',
            'heDaoTao',
            'nienKhoa',
            'khoaHoc',
            'bacHoc',
            'loaiHinhDaoTao',
            'nguoiTao'
        ]);

        return view('ctdt.show', compact('ctdt'));
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
        $heDaoTaos = HeDaoTao::all();
        $nienKhoas = NienKhoa::orderBy('nam_bat_dau', 'desc')->get();
        $khoaHocs = KhoaHoc::with('nienKhoa')->get();
        $bacHocs = BacHoc::all();
        $loaiHinhDaoTaos = LoaiHinhDaoTao::all();
        $nganhs = Nganh::all();
        $chuyenNganhs = $ctdt->nganh_id ? ChuyenNganh::where('nganh_id', $ctdt->nganh_id)->get() : [];

        return view('ctdt.edit', compact('ctdt', 'khoas', 'heDaoTaos', 'nienKhoas', 'khoaHocs', 'bacHocs', 'loaiHinhDaoTaos', 'nganhs', 'chuyenNganhs'));
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

        if ($ctdt->trang_thai !== 'draft') {
            return back()->with('error', 'Chỉ có thể gửi duyệt CTĐT ở trạng thái nháp');
        }

        $ctdt->update(['trang_thai' => 'pending']);


        return back()->with('success', 'Gửi phê duyệt thành công');
    }
}
