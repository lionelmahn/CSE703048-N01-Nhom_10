@extends('layouts.app')

@section('title', 'Chỉnh sửa Niên khóa')

@section('content')
<div class="mb-4">
    <h2>Chỉnh sửa Niên khóa</h2>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('nien-khoa.index') }}">Niên khóa</a></li>
            <li class="breadcrumb-item active">{{ $nienKhoa->ma }}</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('nien-khoa.update', $nienKhoa) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="ma" class="form-label">Mã niên khóa <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('ma') is-invalid @enderror" 
                               id="ma" 
                               name="ma" 
                               value="{{ old('ma', $nienKhoa->ma) }}"
                               required>
                        @error('ma')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nam_bat_dau" class="form-label">Năm bắt đầu <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('nam_bat_dau') is-invalid @enderror" 
                                   id="nam_bat_dau" 
                                   name="nam_bat_dau" 
                                   value="{{ old('nam_bat_dau', $nienKhoa->nam_bat_dau) }}"
                                   min="2000"
                                   max="2100"
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
                                   value="{{ old('nam_ket_thuc', $nienKhoa->nam_ket_thuc) }}"
                                   min="2000"
                                   max="2100"
                                   required>
                            @error('nam_ket_thuc')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Cập nhật
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
                <h6 class="card-title">Thông tin</h6>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2">
                        <strong>Số CTĐT sử dụng:</strong>
                        <span class="badge bg-info">{{ $nienKhoa->chuongTrinhDaoTaos->count() }}</span>
                    </li>
                    <li class="mb-2">
                        <strong>Tạo lúc:</strong> {{ $nienKhoa->created_at->format('d/m/Y H:i') }}
                    </li>
                    <li>
                        <strong>Cập nhật:</strong> {{ $nienKhoa->updated_at->format('d/m/Y H:i') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
