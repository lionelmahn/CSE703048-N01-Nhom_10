@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Chỉnh sửa Khóa học</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('khoa-hoc.update', $khoaHoc) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Mã Khóa học <span class="text-danger">*</span></label>
                    <input type="text" name="ma" class="form-control @error('ma') is-invalid @enderror" value="{{ old('ma', $khoaHoc->ma) }}" required>
                    @error('ma')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tên Khóa học <span class="text-danger">*</span></label>
                    <input type="text" name="ten_khoa_hoc" class="form-control @error('ten_khoa_hoc') is-invalid @enderror" value="{{ old('ten_khoa_hoc', $khoaHoc->ten_khoa_hoc) }}" required>
                    @error('ten_khoa_hoc')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Năm bắt đầu <span class="text-danger">*</span></label>
                        <input type="number" name="nam_bat_dau" class="form-control @error('nam_bat_dau') is-invalid @enderror" value="{{ old('nam_bat_dau', $khoaHoc->nam_bat_dau) }}" min="2000" max="2100" required>
                        @error('nam_bat_dau')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Năm kết thúc <span class="text-danger">*</span></label>
                        <input type="number" name="nam_ket_thuc" class="form-control @error('nam_ket_thuc') is-invalid @enderror" value="{{ old('nam_ket_thuc', $khoaHoc->nam_ket_thuc) }}" min="2000" max="2100" required>
                        @error('nam_ket_thuc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Niên khóa <span class="text-danger">*</span></label>
                    <select name="nien_khoa_id" class="form-select @error('nien_khoa_id') is-invalid @enderror" required>
                        <option value="">-- Chọn Niên khóa --</option>
                        @foreach($nienKhoas as $nienKhoa)
                            <option value="{{ $nienKhoa->id }}" {{ old('nien_khoa_id', $khoaHoc->nien_khoa_id) == $nienKhoa->id ? 'selected' : '' }}>
                                {{ $nienKhoa->ma }} ({{ $nienKhoa->nam_bat_dau }} - {{ $nienKhoa->nam_ket_thuc }})
                            </option>
                        @endforeach
                    </select>
                    @error('nien_khoa_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('khoa-hoc.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
