@extends('layouts.app')

@section('title', 'Tạo bậc học mới')

@section('content')
<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('bac-hoc.index') }}">Bậc học</a></li>
            <li class="breadcrumb-item active">Tạo mới</li>
        </ol>
    </nav>
    <h1 class="h3">Tạo bậc học mới</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('bac-hoc.store') }}" id="bacHocForm">
                    @csrf
                    
                    <!-- Added auto-generate checkbox -->
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
                        <label for="ma" class="form-label">Mã bậc học <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('ma') is-invalid @enderror" 
                            id="ma" 
                            name="ma" 
                            value="{{ old('ma', $suggestedCode) }}"
                            placeholder="VD: BH01, BH02"
                            maxlength="10"
                            disabled
                            required>
                        @error('ma')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Mã sẽ tự động sinh theo định dạng BH + số thứ tự</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="ten" class="form-label">Tên bậc học <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control @error('ten') is-invalid @enderror" 
                            id="ten" 
                            name="ten" 
                            value="{{ old('ten') }}"
                            placeholder="VD: Đại học, Thạc sĩ, Tiến sĩ"
                            required>
                        @error('ten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu
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
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-info-circle text-info"></i> Hướng dẫn
                </h5>
                <p class="card-text small">
                    <strong>Sinh mã tự động:</strong> Hệ thống sẽ tự động tạo mã theo định dạng chuẩn (VD: BH01, BH02).
                </p>
                <p class="card-text small">
                    <strong>Nhập thủ công:</strong> Tắt tính năng sinh tự động để nhập mã theo ý muốn.
                </p>
                <hr>
                <p class="card-text small">
                    Ví dụ các bậc học:
                </p>
                <ul class="small mb-0">
                    <li><strong>BH01:</strong> Đại học</li>
                    <li><strong>BH02:</strong> Thạc sĩ</li>
                    <li><strong>BH03:</strong> Tiến sĩ</li>
                    <li><strong>BH04:</strong> Cao đẳng</li>
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
