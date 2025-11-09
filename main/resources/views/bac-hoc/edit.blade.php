@extends('layouts.app')

@section('title', 'Chỉnh sửa bậc học')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('bac-hoc.index') }}">Bậc học</a></li>
            <li class="breadcrumb-item active">Chỉnh sửa: {{ $bacHoc->ten }}</li>
        </ol>
    </nav>
    <h1 class="h3">Chỉnh sửa bậc học</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('bac-hoc.update', $bacHoc) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="ma" class="form-label">Mã bậc học <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('ma') is-invalid @enderror" 
                            id="ma" 
                            name="ma" 
                            value="{{ old('ma', $bacHoc->ma) }}"
                            maxlength="10"
                            required>
                        @error('ma')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="ten" class="form-label">Tên bậc học <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('ten') is-invalid @enderror" 
                            id="ten" 
                            name="ten" 
                            value="{{ old('ten', $bacHoc->ten) }}"
                            required>
                        @error('ten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập nhật
                        </button>
                        <a href="{{ route('bac-hoc.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-warning bg-opacity-10">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i> Lưu ý
                </h5>
                <p class="card-text small mb-0">
                    Bậc học này đang được sử dụng bởi <strong>{{ $bacHoc->chuongTrinhDaoTaos()->count() }} CTĐT</strong>. 
                    Việc thay đổi thông tin có thể ảnh hưởng đến các CTĐT đang sử dụng.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
