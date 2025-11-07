@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Chỉnh sửa Khoa</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('khoa.index') }}">Khoa</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('khoa.show', $khoa) }}">{{ $khoa->ten }}</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin khoa</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('khoa.update', $khoa) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="ma" class="form-label">Mã khoa <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('ma') is-invalid @enderror" 
                                id="ma" 
                                name="ma" 
                                value="{{ old('ma', $khoa->ma) }}"
                                required
                            >
                            @error('ma')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Mã khoa phải là duy nhất (VD: KHMT, CNTT)</small>
                        </div>

                        <div class="mb-3">
                            <label for="ten" class="form-label">Tên khoa <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('ten') is-invalid @enderror" 
                                id="ten" 
                                name="ten" 
                                value="{{ old('ten', $khoa->ten) }}"
                                required
                            >
                            @error('ten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mo_ta" class="form-label">Mô tả</label>
                            <textarea 
                                class="form-control @error('mo_ta') is-invalid @enderror" 
                                id="mo_ta" 
                                name="mo_ta" 
                                rows="4"
                            >{{ old('mo_ta', $khoa->mo_ta) }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('khoa.show', $khoa) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin bổ sung</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2">
                        <strong>Ngày tạo:</strong><br>
                        {{ $khoa->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-muted small mb-2">
                        <strong>Cập nhật lần cuối:</strong><br>
                        {{ $khoa->updated_at->format('d/m/Y H:i') }}
                    </p>
                    <hr>
                    <p class="text-muted small mb-0">
                        <strong>Số bộ môn:</strong> {{ $khoa->boMons->count() }}<br>
                        <strong>Số CTĐT:</strong> {{ $khoa->chuongTrinhDaoTaos->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
