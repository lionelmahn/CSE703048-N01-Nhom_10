<?php

namespace App\Http\Controllers;

use App\Models\KhoaHoc;
use App\Models\NienKhoa;
use Illuminate\Http\Request;

class KhoaHocController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', KhoaHoc::class);

        $khoaHocs = KhoaHoc::with('nienKhoa')->paginate(20);

        return view('khoa-hoc.index', compact('khoaHocs'));
    }

    public function create()
    {
        $this->authorize('create', KhoaHoc::class);

        $nienKhoas = NienKhoa::orderBy('nam_bat_dau', 'desc')->get();

        return view('khoa-hoc.create', compact('nienKhoas'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', KhoaHoc::class);

        $validated = $request->validate([
            'ma' => 'required|string|max:20|unique:khoa_hoc,ma',
            'nien_khoa_id' => 'required|exists:nien_khoa,id',
        ]);

        KhoaHoc::create($validated);

        return redirect()->route('khoa-hoc.index')
            ->with('success', 'Khóa học đã được tạo thành công.');
    }

    public function show(KhoaHoc $khoaHoc)
    {
        $this->authorize('view', $khoaHoc);

        $khoaHoc->load('nienKhoa');

        return view('khoa-hoc.show', compact('khoaHoc'));
    }

    public function edit(KhoaHoc $khoaHoc)
    {
        $this->authorize('update', $khoaHoc);

        $nienKhoas = NienKhoa::orderBy('nam_bat_dau', 'desc')->get();

        return view('khoa-hoc.edit', compact('khoaHoc', 'nienKhoas'));
    }

    public function update(Request $request, KhoaHoc $khoaHoc)
    {
        $this->authorize('update', $khoaHoc);

        $validated = $request->validate([
            'ma' => 'required|string|max:20|unique:khoa_hoc,ma,' . $khoaHoc->id,
            'nien_khoa_id' => 'required|exists:nien_khoa,id',
        ]);

        $khoaHoc->update($validated);

        return redirect()->route('khoa-hoc.index')
            ->with('success', 'Khóa học đã được cập nhật thành công.');
    }

    public function destroy(KhoaHoc $khoaHoc)
    {
        $this->authorize('delete', $khoaHoc);

        $khoaHoc->delete();

        return redirect()->route('khoa-hoc.index')
            ->with('success', 'Khóa học đã được xóa thành công.');
    }
}
