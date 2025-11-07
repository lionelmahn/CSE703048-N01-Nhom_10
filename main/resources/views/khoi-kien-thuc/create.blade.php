@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3">Thêm Khối kiến thức mới</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('khoi-kien-thuc.index') }}">Khối kiến thức</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('khoi-kien-thuc.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="ma" class="form-label">Mã khối kiến thức <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('ma') is-invalid @enderror" 
                           id="ma" 
                           name="ma" 
                           value="{{ old('ma') }}" 
                           required>
                    @error('ma')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Ví dụ: KHCB, KHCN, KHTN...</small>
                </div>

                <div class="mb-3">
                    <label for="ten" class="form-label">Tên khối kiến thức <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('ten') is-invalid @enderror" 
                           id="ten" 
                           name="ten" 
                           value="{{ old('ten') }}" 
                           required>
                    @error('ten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Ví dụ: Kiến thức cơ bản, Kiến thức chuyên ngành...</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu
                    </button>
                    <a href="{{ route('khoi-kien-thuc.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
