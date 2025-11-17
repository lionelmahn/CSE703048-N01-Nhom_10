<?php

namespace App\Http\Controllers;

use App\Models\Khoa;
use App\Models\User;
use App\Http\Requests\StoreKhoaRequest;
use App\Http\Requests\UpdateKhoaRequest;

class KhoaController extends Controller
{
    public function index()
    {
        $khoas = Khoa::with('boMons')->paginate(20);

        return view('khoa.index', compact('khoas'));
    }

    public function create()
    {
        $users = User::where('role', 'giang_vien')
            ->orWhere('role', 'admin')
            ->orderBy('name')
            ->get();

        return view('khoa.create', compact('users'));
    }

    public function store(StoreKhoaRequest $request)
    {
        $validated = $request->validated();

        $khoa = Khoa::create($validated);

        return redirect()->route('khoa.show', $khoa)->with('success', 'Tạo khoa thành công');
    }

    public function show(Khoa $khoa)
    {
        $khoa->load('boMons', 'nguoiPhuTrach');

        return view('khoa.show', compact('khoa'));
    }

    public function edit(Khoa $khoa)
    {
        $users = User::where('role', 'giang_vien')
            ->orWhere('role', 'admin')
            ->orderBy('name')
            ->get();

        return view('khoa.edit', compact('khoa', 'users'));
    }

    public function update(UpdateKhoaRequest $request, Khoa $khoa)
    {
        $validated = $request->validated();

        $khoa->update($validated);

        return redirect()->route('khoa.show', $khoa)->with('success', 'Cập nhật khoa thành công');
    }
    public function destroy(Khoa $khoa)
    {
        $khoa->delete();

        return redirect()->route('khoa.index')->with('success', 'Xóa khoa thành công');
    }
}
