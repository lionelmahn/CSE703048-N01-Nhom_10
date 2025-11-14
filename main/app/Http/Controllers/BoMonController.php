<?php

namespace App\Http\Controllers;

use App\Models\BoMon;
use App\Models\Khoa;
use App\Http\Requests\StoreBoMonRequest;
use App\Http\Requests\UpdateBoMonRequest;

class BoMonController extends Controller
{
    public function index()
    {
        $boMons = BoMon::with('khoa')->paginate(20);
        return view('bo-mon.index', compact('boMons'));
    }

    public function create()
    {
        $khoas = Khoa::all();
        return view('bo-mon.create', compact('khoas'));
    }

    public function store(StoreBoMonRequest $request)
    {
        $boMon = BoMon::create($request->validated());


        return redirect()->route('bo-mon.show', $boMon)->with('success', 'Tạo bộ môn thành công');
    }

    public function show(BoMon $boMon)
    {
        $boMon->load('khoa');
        return view('bo-mon.show', compact('boMon'));
    }

    public function edit(BoMon $boMon)
    {
        $khoas = Khoa::all();
        return view('bo-mon.edit', compact('boMon', 'khoas'));
    }

    public function update(UpdateBoMonRequest $request, BoMon $boMon)
    {
        $boMon->update($request->validated());

        return redirect()->route('bo-mon.show', $boMon)->with('success', 'Cập nhật bộ môn thành công');
    }

    public function destroy(BoMon $boMon)
    {
        $boMon->delete();

        return redirect()->route('bo-mon.index')->with('success', 'Xóa bộ môn thành công');
    }
}
