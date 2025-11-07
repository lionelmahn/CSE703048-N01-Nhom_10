<?php

namespace App\Http\Controllers;

use App\Models\KhoiKienThuc;
use Illuminate\Http\Request;

class KhoiKienThucController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', KhoiKienThuc::class);

        $khoiKienThucs = KhoiKienThuc::withCount('ctdtKhois')
            ->orderBy('ma')
            ->paginate(15);

        return view('khoi-kien-thuc.index', compact('khoiKienThucs'));
    }

    public function create()
    {
        $this->authorize('create', KhoiKienThuc::class);

        return view('khoi-kien-thuc.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', KhoiKienThuc::class);

        $validated = $request->validate([
            'ma' => 'required|string|max:20|unique:khoi_kien_thuc,ma',
            'ten' => 'required|string|max:255',
        ]);

        KhoiKienThuc::create($validated);

        return redirect()->route('khoi-kien-thuc.index')
            ->with('success', 'Thêm khối kiến thức thành công!');
    }

    public function edit(KhoiKienThuc $khoiKienThuc)
    {
        $this->authorize('update', $khoiKienThuc);

        return view('khoi-kien-thuc.edit', compact('khoiKienThuc'));
    }

    public function update(Request $request, KhoiKienThuc $khoiKienThuc)
    {
        $this->authorize('update', $khoiKienThuc);

        $validated = $request->validate([
            'ma' => 'required|string|max:20|unique:khoi_kien_thuc,ma,' . $khoiKienThuc->id,
            'ten' => 'required|string|max:255',
        ]);

        $khoiKienThuc->update($validated);

        return redirect()->route('khoi-kien-thuc.index')
            ->with('success', 'Cập nhật khối kiến thức thành công!');
    }

    public function destroy(KhoiKienThuc $khoiKienThuc)
    {
        $this->authorize('delete', $khoiKienThuc);

        if ($khoiKienThuc->ctdtKhois()->count() > 0) {
            return back()->with('error', 'Không thể xóa khối kiến thức đã được sử dụng trong CTĐT!');
        }

        $khoiKienThuc->delete();

        return redirect()->route('khoi-kien-thuc.index')
            ->with('success', 'Xóa khối kiến thức thành công!');
    }
}
