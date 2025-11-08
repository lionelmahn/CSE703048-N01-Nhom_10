@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Chỉnh sửa ngành: {{ $nganh->ten }}</h1>
        <a href="{{ route('nganh.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('nganh.update', $nganh) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="ma" class="form-label">Mã ngành <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('ma') is-invalid @enderror" 
                           id="ma" name="ma" value="{{ old('ma', $nganh->ma) }}" required>
                    @error('ma')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="ten" class="form-label">Tên ngành <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('ten') is-invalid @enderror" 
                           id="ten" name="ten" value="{{ old('ten', $nganh->ten) }}" required>
                    @error('ten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Ngành này có <strong>{{ $nganh->chuongTrinhDaoTaos->count() }}</strong> chương trình đào tạo liên quan.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Cập nhật
                    </button>
                    <a href="{{ route('nganh.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
