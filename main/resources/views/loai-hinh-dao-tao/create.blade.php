@extends('layouts.app')

@section('title', 'Tạo loại hình đào tạo mới')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('loai-hinh-dao-tao.index') }}">Loại hình đào tạo</a></li>
            <li class="breadcrumb-item active">Tạo mới</li>
        </ol>
    </nav>
    <h1 class="h3">Tạo loại hình đào tạo mới</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('loai-hinh-dao-tao.store') }}" id="loaiHinhForm">
                    @csrf
                    
                    <!-- Added auto-generate code checkbox -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="autoGenerateCode" 
                                name="auto_generate_code"
                                value="1"
                                checked
                                onchange="toggleCodeInput()">
                            <label class="form-check-label" for="autoGenerateCode">
                                <i class="fas fa-magic text-primary"></i> Sinh mã tự động
                            </label>
                        </div>
                        <small class="form-text text-muted">Mã gợi ý: <strong id="suggestedCode">{{ $suggestedCode }}</strong></small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="ma" class="form-label">Mã loại hình <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('ma') is-invalid @enderror" 
                            id="ma" 
                            name="ma" 
                            value="{{ old('ma', $suggestedCode) }}"
                            placeholder="VD: LHDT01, LHDT02"
                            maxlength="10"
                            disabled
                            required>
                        @error('ma')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <!-- Updated help text -->
                        <small class="form-text text-muted">Mã sẽ tự động sinh theo định dạng LHDT + số thứ tự</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="ten" class="form-label">Tên loại hình đào tạo <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('ten') is-invalid @enderror" 
                            id="ten" 
                            name="ten" 
                            value="{{ old('ten') }}"
                            placeholder="VD: Chính quy, Vừa học vừa làm, Tại chức"
                            required>
                        @error('ten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu
                        </button>
                        <a href="{{ route('loai-hinh-dao-tao.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-info-circle text-info"></i> Hướng dẫn
                </h5>
                <!-- Updated instructions for auto-generate -->
                <p class="card-text small">
                    <strong>Sinh mã tự động:</strong> Hệ thống sẽ tự động tạo mã theo định dạng chuẩn (VD: LHDT01, LHDT02).
                </p>
                <p class="card-text small">
                    <strong>Nhập thủ công:</strong> Tắt tính năng sinh tự động để nhập mã theo ý muốn.
                </p>
                <hr>
                <p class="card-text small">
                    Ví dụ các loại hình đào tạo:
                </p>
                <ul class="small mb-0">
                    <li><strong>LHDT01:</strong> Chính quy</li>
                    <li><strong>LHDT02:</strong> Liên thông</li>
                    <li><strong>LHDT03:</strong> Văn bằng 2</li>
                    <li><strong>LHDT04:</strong> Vừa học vừa làm</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Added JavaScript for toggle functionality -->
<script>
function toggleCodeInput() {
    const checkbox = document.getElementById('autoGenerateCode');
    const input = document.getElementById('ma');
    
    if (checkbox.checked) {
        input.disabled = true;
        input.value = '{{ $suggestedCode }}';
    } else {
        input.disabled = false;
        input.value = '';
        input.focus();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleCodeInput();
});
</script>
@endsection
