@extends('layouts.app')

@section('title', $mode === 'copy' ? 'Sao chép Chương trình Đào tạo' : 'Tạo Chương trình Đào tạo')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <h1 class="h3 mb-4">
            @if($mode === 'copy')
                Sao chép Chương trình Đào tạo
            @else
                Tạo mới Chương trình Đào tạo
            @endif
        </h1>
        
        {{-- Add mode selection if not already selected --}}
        @if(!request()->has('mode'))
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Chọn phương thức tạo CTĐT</h5>
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <a href="{{ route('ctdt.create', ['mode' => 'new']) }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-plus-circle"></i> Tạo mới hoàn toàn
                        </a>
                        <p class="text-muted mt-2 small">Tạo CTĐT mới từ đầu với thông tin cơ bản</p>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('ctdt.create', ['mode' => 'copy']) }}" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-copy"></i> Sao chép CTĐT
                        </a>
                        <p class="text-muted mt-2 small">Sao chép từ CTĐT đã có, bao gồm cấu trúc và học phần</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <div class="card">
            <div class="card-body">
                <form action="{{ route('ctdt.store') }}" method="POST" id="ctdtForm">
                    @csrf
                    
                    {{-- Add source CTDT selection if copy mode --}}
                    @if($mode === 'copy')
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Chọn CTĐT nguồn để sao chép cấu trúc, khối kiến thức và danh sách học phần
                    </div>
                    
                    <div class="card mb-4 bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">CTĐT nguồn</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="source_ctdt_id" class="form-label">Chọn CTĐT nguồn <span class="text-danger">*</span></label>
                                <select class="form-select @error('source_ctdt_id') is-invalid @enderror" 
                                        id="source_ctdt_id" name="source_ctdt_id" required>
                                    <option value="">-- Chọn CTĐT để sao chép --</option>
                                    @foreach ($ctdtsForCopy as $ctdt)
                                    <option value="{{ $ctdt->id }}" 
                                            data-bac-hoc="{{ $ctdt->bac_hoc_id }}"
                                            data-loai-hinh="{{ $ctdt->loai_hinh_dao_tao_id }}"
                                            data-nganh="{{ $ctdt->nganh_id }}"
                                            data-chuyen-nganh="{{ $ctdt->chuyen_nganh_id }}"
                                            @selected(old('source_ctdt_id') == $ctdt->id)>
                                        {{ $ctdt->ma_ctdt }} - {{ $ctdt->ten }} 
                                        ({{ $ctdt->nganh->ten ?? 'N/A' }} - {{ $ctdt->khoaHoc->ma ?? 'N/A' }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('source_ctdt_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">CTĐT nguồn phải ở trạng thái Đã phê duyệt hoặc Đã công bố</div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- New form section for CTDT info with auto-generate code --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Thông tin CTĐT {{ $mode === 'copy' ? 'mới' : '' }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bac_hoc_id" class="form-label">Bậc học <span class="text-danger">*</span></label>
                                        <select class="form-select @error('bac_hoc_id') is-invalid @enderror" 
                                                id="bac_hoc_id" name="bac_hoc_id" required>
                                            <option value="">-- Chọn bậc học --</option>
                                            @foreach ($bacHocs as $bacHoc)
                                            <option value="{{ $bacHoc->id }}" 
                                                    data-ma="{{ $bacHoc->ma }}"
                                                    @selected(old('bac_hoc_id') == $bacHoc->id)>
                                                {{ $bacHoc->ma }} - {{ $bacHoc->ten }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('bac_hoc_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="loai_hinh_dao_tao_id" class="form-label">Loại hình đào tạo <span class="text-danger">*</span></label>
                                        <select class="form-select @error('loai_hinh_dao_tao_id') is-invalid @enderror" 
                                                id="loai_hinh_dao_tao_id" name="loai_hinh_dao_tao_id" required>
                                            <option value="">-- Chọn loại hình --</option>
                                            @foreach ($loaiHinhDaoTaos as $loaiHinh)
                                            <option value="{{ $loaiHinh->id }}" 
                                                    data-ma="{{ $loaiHinh->ma }}"
                                                    @selected(old('loai_hinh_dao_tao_id') == $loaiHinh->id)>
                                                {{ $loaiHinh->ma }} - {{ $loaiHinh->ten }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('loai_hinh_dao_tao_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nganh_id" class="form-label">Ngành <span class="text-danger">*</span></label>
                                        <select class="form-select @error('nganh_id') is-invalid @enderror" 
                                                id="nganh_id" name="nganh_id" required>
                                            <option value="">-- Chọn ngành --</option>
                                            @foreach ($nganhs as $nganh)
                                            <option value="{{ $nganh->id }}" 
                                                    data-ma="{{ $nganh->ma }}"
                                                    @selected(old('nganh_id') == $nganh->id)>
                                                {{ $nganh->ma }} - {{ $nganh->ten }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('nganh_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <div class="form-text">Mã ngành theo quy định của Bộ GD&ĐT</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="chuyen_nganh_id" class="form-label">Hướng/Chuyên ngành</label>
                                        <select class="form-select @error('chuyen_nganh_id') is-invalid @enderror" 
                                                id="chuyen_nganh_id" name="chuyen_nganh_id">
                                            <option value="">-- Không chọn --</option>
                                            @foreach ($chuyenNganhs as $chuyenNganh)
                                            <option value="{{ $chuyenNganh->id }}" 
                                                    data-ma="{{ $chuyenNganh->ma }}"
                                                    data-nganh="{{ $chuyenNganh->nganh_id }}"
                                                    @selected(old('chuyen_nganh_id') == $chuyenNganh->id)>
                                                {{ $chuyenNganh->ma }} - {{ $chuyenNganh->ten }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('chuyen_nganh_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <div class="form-text">Để trống nếu không có hướng/chuyên ngành</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="khoa_hoc_id" class="form-label">Khóa học áp dụng <span class="text-danger">*</span></label>
                                        <select class="form-select @error('khoa_hoc_id') is-invalid @enderror" 
                                                id="khoa_hoc_id" name="khoa_hoc_id" required>
                                            <option value="">-- Chọn khóa --</option>
                                            @foreach ($khoaHocs as $khoaHoc)
                                            <option value="{{ $khoaHoc->id }}" 
                                                    data-ma="{{ $khoaHoc->ma }}"
                                                    @selected(old('khoa_hoc_id') == $khoaHoc->id)>
                                                {{ $khoaHoc->ma }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('khoa_hoc_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <div class="form-text">VD: K25, K26...</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ma_ctdt" class="form-label">
                                            Mã CTĐT <span class="text-danger">*</span>
                                            <span class="badge bg-info ms-2">Tự động</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control @error('ma_ctdt') is-invalid @enderror" 
                                                   id="ma_ctdt" 
                                                   name="ma_ctdt" 
                                                   value="{{ old('ma_ctdt') }}" 
                                                   readonly
                                                   placeholder="Chọn đủ thông tin để sinh mã tự động"
                                                   required>
                                            <button class="btn btn-outline-secondary" type="button" id="refreshCode">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                        @error('ma_ctdt')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <div class="form-text" id="codeHelp">
                                            Format: BacHoc-LoaiHinh-MaNganhBo-MaHuongChuyenNganh-KKhoa<br>
                                            VD: DH-CQ-7480201-CNTT-K25
                                        </div>
                                        <div class="alert alert-success mt-2 d-none" id="codeSuccess">
                                            <i class="fas fa-check-circle"></i> Mã CTĐT hợp lệ và chưa tồn tại
                                        </div>
                                        <div class="alert alert-danger mt-2 d-none" id="codeError">
                                            <i class="fas fa-exclamation-circle"></i> Mã CTĐT đã tồn tại trong hệ thống
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nien_khoa_id" class="form-label">Niên khóa <span class="text-danger">*</span></label>
                                        <select class="form-select @error('nien_khoa_id') is-invalid @enderror" 
                                                id="nien_khoa_id" name="nien_khoa_id" required>
                                            <option value="">-- Chọn niên khóa --</option>
                                            @foreach ($nienKhoas as $nk)
                                            <option value="{{ $nk->id }}" @selected(old('nien_khoa_id') == $nk->id)>
                                                {{ $nk->ma }} ({{ $nk->nam_bat_dau }}-{{ $nk->nam_ket_thuc }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('nien_khoa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="hieu_luc_tu" class="form-label">Hiệu lực từ <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('hieu_luc_tu') is-invalid @enderror" 
                                               id="hieu_luc_tu" name="hieu_luc_tu" value="{{ old('hieu_luc_tu') }}" required>
                                        @error('hieu_luc_tu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="hieu_luc_den" class="form-label">Hiệu lực đến</label>
                                        <input type="date" class="form-control @error('hieu_luc_den') is-invalid @enderror" 
                                               id="hieu_luc_den" name="hieu_luc_den" value="{{ old('hieu_luc_den') }}">
                                        @error('hieu_luc_den')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <div class="form-text">Để trống nếu CTĐT không có thời hạn kết thúc</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="mo_ta" class="form-label">Mô tả</label>
                                <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                          id="mo_ta" name="mo_ta" rows="3">{{ old('mo_ta') }}</textarea>
                                @error('mo_ta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> 
                            {{ $mode === 'copy' ? 'Sao chép' : 'Tạo' }}
                        </button>
                        <a href="{{ route('ctdt.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Add JavaScript for auto-generate CTDT code --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('ctdtForm');
    const bacHocSelect = document.getElementById('bac_hoc_id');
    const loaiHinhSelect = document.getElementById('loai_hinh_dao_tao_id');
    const nganhSelect = document.getElementById('nganh_id');
    const chuyenNganhSelect = document.getElementById('chuyen_nganh_id');
    const khoaHocSelect = document.getElementById('khoa_hoc_id');
    const maCtdtInput = document.getElementById('ma_ctdt');
    const refreshBtn = document.getElementById('refreshCode');
    const submitBtn = document.getElementById('submitBtn');
    const codeSuccess = document.getElementById('codeSuccess');
    const codeError = document.getElementById('codeError');
    
    // Auto-generate code when all required fields are filled
    function generateCode() {
        const bacHocId = bacHocSelect.value;
        const loaiHinhId = loaiHinhSelect.value;
        const nganhId = nganhSelect.value;
        const khoaHocId = khoaHocSelect.value;
        const chuyenNganhId = chuyenNganhSelect.value;
        
        if (!bacHocId || !loaiHinhId || !nganhId || !khoaHocId) {
            maCtdtInput.value = '';
            codeSuccess.classList.add('d-none');
            codeError.classList.add('d-none');
            return;
        }
        
        // Call API to generate code
        fetch('{{ route("ctdt.generate-code") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                bac_hoc_id: bacHocId,
                loai_hinh_dao_tao_id: loaiHinhId,
                nganh_id: nganhId,
                chuyen_nganh_id: chuyenNganhId,
                khoa_hoc_id: khoaHocId
            })
        })
        .then(response => response.json())
        .then(data => {
            maCtdtInput.value = data.ma_ctdt;
            
            if (data.is_unique) {
                codeSuccess.classList.remove('d-none');
                codeError.classList.add('d-none');
                submitBtn.disabled = false;
            } else {
                codeSuccess.classList.add('d-none');
                codeError.classList.remove('d-none');
                codeError.textContent = data.message;
                submitBtn.disabled = true;
            }
        })
        .catch(error => {
            console.error('[v0] Error generating code:', error);
        });
    }
    
    // Attach event listeners
    bacHocSelect.addEventListener('change', generateCode);
    loaiHinhSelect.addEventListener('change', generateCode);
    nganhSelect.addEventListener('change', generateCode);
    chuyenNganhSelect.addEventListener('change', generateCode);
    khoaHocSelect.addEventListener('change', generateCode);
    refreshBtn.addEventListener('click', generateCode);
    
    // Filter chuyen nganh by nganh
    nganhSelect.addEventListener('change', function() {
        const nganhId = this.value;
        const options = chuyenNganhSelect.querySelectorAll('option[data-nganh]');
        
        chuyenNganhSelect.value = '';
        
        options.forEach(option => {
            if (!nganhId || option.dataset.nganh === nganhId) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
        
        generateCode();
    });
    
    @if($mode === 'copy')
    // Pre-fill data from source CTDT when selected
    const sourceSelect = document.getElementById('source_ctdt_id');
    sourceSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (selected.value) {
            bacHocSelect.value = selected.dataset.bacHoc || '';
            loaiHinhSelect.value = selected.dataset.loaiHinh || '';
            nganhSelect.value = selected.dataset.nganh || '';
            
            // Trigger nganh change to filter chuyen nganh
            nganhSelect.dispatchEvent(new Event('change'));
            
            setTimeout(() => {
                chuyenNganhSelect.value = selected.dataset.chuyenNganh || '';
            }, 100);
        }
    });
    @endif
});
</script>
@endpush
@endsection
