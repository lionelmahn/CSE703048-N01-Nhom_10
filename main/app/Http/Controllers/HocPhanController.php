<?php

namespace App\Http\Controllers;

use App\Models\HocPhan;
use App\Models\Khoa;
use App\Models\BoMon;
use App\Http\Requests\StoreHocPhanRequest;
use App\Http\Requests\UpdateHocPhanRequest;
use Illuminate\Support\Facades\Auth;

class HocPhanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = HocPhan::query();

        if ($user->role !== 'admin') {
            $query->where('khoa_id', $user->khoa_id);
        }

        $hocPhans = $query->with(['khoa', 'boMon'])
            ->paginate(20);

        return view('hoc-phan.index', compact('hocPhans'));
    }

    public function create()
    {
        $user = Auth::user();
        $khoasQuery = Khoa::query();

        if ($user->role !== 'admin') {
            $khoasQuery->where('id', $user->khoa_id);
        }

        $khoas = $khoasQuery->get();

        return view('hoc-phan.create', compact('khoas'));
    }

    public function store(StoreHocPhanRequest $request)
    {
        $validated = $request->validated();

        $hocPhan = HocPhan::create($validated);


        return redirect()->route('hoc-phan.show', $hocPhan)->with('success', 'Tạo học phần thành công');
    }

    public function show(HocPhan $hocPhan)
    {
        $this->authorize('view', $hocPhan);

        $hocPhan->load(['khoa', 'boMon']);

        return view('hoc-phan.show', compact('hocPhan'));
    }

    public function edit(HocPhan $hocPhan)
    {
        $this->authorize('update', $hocPhan);

        $user = Auth::user();
        $khoasQuery = Khoa::query();

        if ($user->role !== 'admin') {
            $khoasQuery->where('id', $user->khoa_id);
        }

        $khoas = $khoasQuery->get();
        $boMons = $hocPhan->khoa_id ? BoMon::where('khoa_id', $hocPhan->khoa_id)->get() : [];

        return view('hoc-phan.edit', compact('hocPhan', 'khoas', 'boMons'));
    }

    public function update(UpdateHocPhanRequest $request, HocPhan $hocPhan)
    {
        $this->authorize('update', $hocPhan);

        $validated = $request->validated();
        $hocPhan->update($validated);


        return redirect()->route('hoc-phan.show', $hocPhan)->with('success', 'Cập nhật học phần thành công');
    }

    public function destroy(HocPhan $hocPhan)
    {
        $this->authorize('delete', $hocPhan);

        $hocPhan->delete();


        return redirect()->route('hoc-phan.index')->with('success', 'Xóa học phần thành công');
    }
}
