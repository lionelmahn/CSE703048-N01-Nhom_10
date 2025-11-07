@extends('layouts.app')

@section('title', 'Thêm Niên khóa')

@section('content')
<div class="mb-4">
    <h2>Thêm Niên khóa mới</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('nien-khoa.index') }}">Niên khóa</a></li>
            <li class="breadcrumb-item active">Thêm mới</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('nien-khoa.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="ma" class="form-label">Mã niên khóa <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('ma') is-invalid @enderror" 
                               id="ma" 
                               name="ma" 
                               value="{{ old('ma') }}"
                               placeholder="VD: NK2023-2024"
                               required>
                        @error('ma')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Mã duy nhất để định danh niên khóa</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nam_bat_dau" class="form-label">Năm bắt đầu <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('nam_bat_dau') is-invalid @enderror" 
                                   id="nam_bat_dau" 
                                   name="nam_bat_dau" 
                                   value="{{ old('nam_bat_dau') }}"
                                   min="2000"
                                   max="2100"
                                   placeholder="2023"
                                   required>
                            @error('nam_bat_dau')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nam_ket_thuc" class="form-label">Năm kết thúc <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('nam_ket_thuc') is-invalid @enderror" 
                                   id="nam_ket_thuc" 
                                   name="nam_ket_thuc" 
                                   value="{{ old('nam_ket_thuc') }}"
                                   min="2000"
                                   max="2100"
                                   placeholder="2024"
                                   required>
                            @error('nam_ket_thuc')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Lưu
                        </button>
                        <a href="{{ route('nien-khoa.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">Hướng dẫn</h6>
                <ul class="small mb-0">
                    <li>Mã niên khóa phải là duy nhất trong hệ thống</li>
                    <li>Năm kết thúc phải lớn hơn năm bắt đầu</li>
                    <li>Tên niên khóa sẽ được tự động tạo từ năm bắt đầu và kết thúc</li>
                    <li>VD: 2023-2024 sẽ tạo tên "Niên khóa 2023-2024"</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
