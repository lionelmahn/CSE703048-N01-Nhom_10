@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3">Sửa Khối kiến thức: {{ $khoiKienThuc->ten }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('khoi-kien-thuc.index') }}">Khối kiến thức</a></li>
                <li class="breadcrumb-item active">Sửa</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('khoi-kien-thuc.update', $khoiKienThuc) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="ma" class="form-label">Mã khối kiến thức <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('ma') is-invalid @enderror" 
                           id="ma" 
                           name="ma" 
                           value="{{ old('ma', $khoiKienThuc->ma) }}" 
                           required>
                    @error('ma')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="ten" class="form-label">Tên khối kiến thức <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('ten') is-invalid @enderror" 
                           id="ten" 
                           name="ten" 
                           value="{{ old('ten', $khoiKienThuc->ten) }}" 
                           required>
                    @error('ten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Khối kiến thức này đang được sử dụng trong <strong>{{ $khoiKienThuc->ctdtKhois()->count() }}</strong> CTĐT.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật
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
