<?php

namespace App\Http\Controllers;

use App\Models\BacHoc;
use App\Helpers\CodeGenerator;
use Illuminate\Http\Request;

class BacHocController extends Controller
{
    public function index()
    {
        $bacHocs = BacHoc::orderBy('ma')->paginate(15);
        return view('bac-hoc.index', compact('bacHocs'));
    }

    public function create()
    {
        $suggestedCode = CodeGenerator::generateCode('bac_hoc', 'ma', 'BH', 2);
        return view('bac-hoc.create', compact('suggestedCode'));
    }

    public function store(Request $request)
    {
        if ($request->has('auto_generate_code') && $request->auto_generate_code == '1') {
            $code = CodeGenerator::generateCode('bac_hoc', 'ma', 'BH', 2);
            $request->merge(['ma' => $code]);
        }

        $validated = $request->validate([
            'ma' => 'required|string|max:10|unique:bac_hoc',
            'ten' => 'required|string|max:255',
        ]);

        BacHoc::create($validated);

        return redirect()->route('bac-hoc.index')->with('success', 'Thêm bậc học thành công');
    }

    public function edit(BacHoc $bacHoc)
    {
        return view('bac-hoc.edit', compact('bacHoc'));
    }

    public function update(Request $request, BacHoc $bacHoc)
    {
        $validated = $request->validate([
            'ma' => 'required|string|max:10|unique:bac_hoc,ma,' . $bacHoc->id,
            'ten' => 'required|string|max:255',
        ]);

        $bacHoc->update($validated);

        return redirect()->route('bac-hoc.index')->with('success', 'Cập nhật bậc học thành công');
    }

    public function destroy(BacHoc $bacHoc)
    {
        if ($bacHoc->chuongTrinhDaoTaos()->count() > 0) {
            return back()->with('error', 'Không thể xóa bậc học đang được sử dụng');
        }

        $bacHoc->delete();

        return redirect()->route('bac-hoc.index')->with('success', 'Xóa bậc học thành công');
    }
}
