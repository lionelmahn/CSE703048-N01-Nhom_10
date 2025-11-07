@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Chỉnh Sửa Hệ Đào Tạo</h1>
        <a href="{{ route('he-dao-tao.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay Lại
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('he-dao-tao.update', $heDaoTao) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="ma" class="form-label">Mã Hệ Đào Tạo <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('ma') is-invalid @enderror" 
                                   id="ma" 
                                   name="ma" 
                                   value="{{ old('ma', $heDaoTao->ma) }}" 
                                   required>
                            @error('ma')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ten" class="form-label">Tên Hệ Đào Tạo <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('ten') is-invalid @enderror" 
                                   id="ten" 
                                   name="ten" 
                                   value="{{ old('ten', $heDaoTao->ten) }}" 
                                   required>
                            @error('ten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập Nhật
                            </button>
                            <a href="{{ route('he-dao-tao.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Thông tin</h5>
                    <div class="small">
                        <p><strong>Ngày tạo:</strong> {{ $heDaoTao->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Cập nhật:</strong> {{ $heDaoTao->updated_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Số ngành:</strong> <span class="badge bg-info">{{ $heDaoTao->nganhs->count() }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
