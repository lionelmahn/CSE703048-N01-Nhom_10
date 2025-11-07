@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Thêm Khóa học mới</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('khoa-hoc.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Mã Khóa học <span class="text-danger">*</span></label>
                    <input type="text" name="ma" class="form-control @error('ma') is-invalid @enderror" value="{{ old('ma') }}" required>
                    <small class="form-text text-muted">Ví dụ: K66, K67</small>
                    @error('ma')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Niên khóa <span class="text-danger">*</span></label>
                    <select name="nien_khoa_id" class="form-select @error('nien_khoa_id') is-invalid @enderror" required>
                        <option value="">-- Chọn Niên khóa --</option>
                        @foreach($nienKhoas as $nienKhoa)
                            <option value="{{ $nienKhoa->id }}" {{ old('nien_khoa_id') == $nienKhoa->id ? 'selected' : '' }}>
                                {{ $nienKhoa->ma }} ({{ $nienKhoa->nam_bat_dau }} - {{ $nienKhoa->nam_ket_thuc }})
                            </option>
                        @endforeach
                    </select>
                    @error('nien_khoa_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                    <a href="{{ route('khoa-hoc.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
