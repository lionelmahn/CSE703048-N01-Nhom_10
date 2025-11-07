<?php

namespace App\Http\Controllers;

use App\Models\NienKhoa;
use Illuminate\Http\Request;

class NienKhoaController extends Controller
{
    public function index()
    {
        $nienKhoas = NienKhoa::paginate(20);
        return view('nien-khoa.index', compact('nienKhoas'));
    }

    public function create()
    {
        return view('nien-khoa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma' => 'required|string|unique:nien_khoa|max:50',
            'nam_bat_dau' => 'required|integer|min:2000|max:2100',
            'nam_ket_thuc' => 'required|integer|min:2000|max:2100|gt:nam_bat_dau',
        ]);

        $nienKhoa = NienKhoa::create($validated);

        return redirect()->route('nien-khoa.show', $nienKhoa)->with('success', 'Tạo niên khóa thành công');
    }

    public function show(NienKhoa $nienKhoa)
    {
        return view('nien-khoa.show', compact('nienKhoa'));
    }

    public function edit(NienKhoa $nienKhoa)
    {
        return view('nien-khoa.edit', compact('nienKhoa'));
    }

    public function update(Request $request, NienKhoa $nienKhoa)
    {
        $validated = $request->validate([
            'ma' => 'required|string|max:50|unique:nien_khoa,ma,' . $nienKhoa->id,
            'nam_bat_dau' => 'required|integer|min:2000|max:2100',
            'nam_ket_thuc' => 'required|integer|min:2000|max:2100|gt:nam_bat_dau',
        ]);

        $nienKhoa->update($validated);

        return redirect()->route('nien-khoa.show', $nienKhoa)->with('success', 'Cập nhật niên khóa thành công');
    }

    public function destroy(NienKhoa $nienKhoa)
    {
        $nienKhoa->delete();

        return redirect()->route('nien-khoa.index')->with('success', 'Xóa niên khóa thành công');
    }
}
