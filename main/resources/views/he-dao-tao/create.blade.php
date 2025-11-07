@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Thêm Hệ Đào Tạo Mới</h1>
        <a href="{{ route('he-dao-tao.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay Lại
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('he-dao-tao.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="ma" class="form-label">Mã Hệ Đào Tạo <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('ma') is-invalid @enderror" 
                                   id="ma" 
                                   name="ma" 
                                   value="{{ old('ma') }}" 
                                   required 
                                   placeholder="VD: DH, CD, TC">
                            @error('ma')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Mã hệ đào tạo phải là duy nhất</div>
                        </div>

                        <div class="mb-3">
                            <label for="ten" class="form-label">Tên Hệ Đào Tạo <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('ten') is-invalid @enderror" 
                                   id="ten" 
                                   name="ten" 
                                   value="{{ old('ten') }}" 
                                   required 
                                   placeholder="VD: Đại học, Cao đẳng, Trung cấp">
                            @error('ten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu
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
                    <h5 class="card-title">Hướng dẫn</h5>
                    <p class="card-text small">
                        Hệ đào tạo là phân loại theo trình độ đào tạo như Đại học, Cao đẳng, Trung cấp, v.v.
                    </p>
                    <ul class="small">
                        <li>Mã hệ đào tạo phải ngắn gọn và dễ nhớ</li>
                        <li>Tên hệ đào tạo phải rõ ràng</li>
                        <li>Mỗi hệ đào tạo có thể có nhiều ngành</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
