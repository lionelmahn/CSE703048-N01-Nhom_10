@extends('layouts.app')

@section('title', 'Quản lý ràng buộc học phần')

@section('content')
<div class="mb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ctdt.index') }}">Chương trình đào tạo</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ctdt.show', $ctdt) }}">{{ $ctdt->ten }}</a></li>
            <li class="breadcrumb-item active">Ràng buộc học phần</li>
        </ol>
    </nav>
    
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-1">Quản lý ràng buộc học phần</h1>
            <p class="text-muted mb-0">{{ $ctdt->ten }}</p>
        </div>
        <a href="{{ route('ctdt.show', $ctdt) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

{{-- Alert for unsaved changes --}}
<div id="unsaved-alert" class="alert alert-warning alert-dismissible fade show d-none" role="alert">
    <i class="bi bi-exclamation-triangle"></i> Bạn có thay đổi chưa lưu. Nhấn "Lưu thay đổi" để áp dụng.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

{{-- Error alert --}}
<div id="error-alert" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
    <div id="error-messages"></div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

{{-- 2 Column Layout --}}
<div class="row" style="height: calc(100vh - 250px); min-height: 600px;">
    {{-- Column 1: Hoc Phan trong CTDT (Cột Đích) --}}
    <div class="col-md-6 d-flex flex-column h-100">
        <div class="card h-100 d-flex flex-column">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">Học phần trong CTĐT</h6>
            </div>
            <div class="card-body p-3 flex-grow-1 overflow-hidden d-flex flex-column">
                {{-- Search box --}}
                <div class="mb-3">
                    <input type="text" class="form-control" id="search-hoc-phan-ctdt" 
                           placeholder="Tìm theo mã hoặc tên học phần...">
                </div>
                
                {{-- Scrollable list --}}
                <div class="flex-grow-1 overflow-auto" id="hoc-phan-list">
                    @forelse($ctdt->ctdtHocPhans->sortBy('thu_tu') as $ctdtHocPhan)
                    {{-- Added onclick directly to HTML and data attributes --}}
                    <div class="hoc-phan-item p-3 mb-2 border rounded" 
                         data-hoc-phan-id="{{ $ctdtHocPhan->hocPhan->id }}"
                         data-ma="{{ $ctdtHocPhan->hocPhan->ma_hp }}"
                         data-ten="{{ $ctdtHocPhan->hocPhan->ten_hp }}"
                         style="cursor: pointer;"
                         onclick="handleSelectHocPhan({{ $ctdtHocPhan->hocPhan->id }}, '{{ $ctdtHocPhan->hocPhan->ma_hp }}', '{{ $ctdtHocPhan->hocPhan->ten_hp }}')">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $ctdtHocPhan->hocPhan->ma_hp }} - {{ $ctdtHocPhan->hocPhan->ten_hp }}</h6>
                                <small class="text-muted">
                                    {{ $ctdtHocPhan->hocPhan->so_tinchi }} TC • {{ $ctdtHocPhan->khoi->ten ?? 'N/A' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mt-2">Chưa có học phần trong CTĐT này</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    {{-- Column 2: Thiet lap rang buoc (Cột Nguồn & Kết quả) --}}
    <div class="col-md-6 d-flex flex-column h-100">
        <div class="card h-100 d-flex flex-column">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">Thiết lập ràng buộc</h6>
            </div>
            <div class="card-body p-3 flex-grow-1 overflow-hidden d-flex flex-column" id="rang-buoc-panel">
                {{-- Empty state --}}
                <div id="empty-state" class="text-center text-muted py-5">
                    <i class="bi bi-arrow-left-circle fs-1"></i>
                    <p class="mt-2">Hãy chọn một học phần từ danh sách bên trái để thiết lập ràng buộc</p>
                </div>
                
                {{-- Content when hoc phan selected --}}
                <div id="rang-buoc-content" class="d-none flex-grow-1 overflow-hidden d-flex flex-column">
                    {{-- Selected hoc phan info --}}
                    <div class="alert alert-info mb-3">
                        <strong>Đang thiết lập cho:</strong>
                        <div id="selected-hoc-phan-info"></div>
                    </div>
                    
                    {{-- Phần 2.A: Form Nguồn - Thêm ràng buộc --}}
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Thêm ràng buộc</h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info small mb-3">
                                <strong>Quy tắc nghiệp vụ:</strong>
                                <ul class="mb-0 small">
                                    <li><strong>BR1:</strong> Học phần ràng buộc tìm từ toàn hệ thống (không giới hạn trong CTĐT)</li>
                                    <li><strong>BR3:</strong> Học phần không thể tự ràng buộc với chính nó</li>
                                    <li><strong>BR2:</strong> Hệ thống sẽ kiểm tra và ngăn ràng buộc vòng khi lưu</li>
                                    <li><strong>BR4:</strong> Ràng buộc "thay thế" tự động tạo quan hệ 2 chiều</li>
                                </ul>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Học phần ràng buộc</label>
                                <select class="form-select" id="search-hp-rang-buoc">
                                    <option value="">Tìm kiếm học phần...</option>
                                </select>
                                <small class="text-muted">Tìm từ toàn bộ hệ thống (BR1)</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Loại ràng buộc</label>
                                <select class="form-select" id="loai-rang-buoc">
                                    <option value="">-- Chọn loại --</option>
                                    <option value="tien_quyet">Tiên quyết</option>
                                    <option value="song_hanh">Song hành</option>
                                    <option value="thay_the">Thay thế</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="handleAddRangBuoc()">
                                <i class="bi bi-plus-circle"></i> Thêm
                            </button>
                        </div>
                    </div>
                    
                    {{-- Phần 2.B: Kết quả - Danh sách ràng buộc hiện tại --}}
                    <div class="flex-grow-1 overflow-auto">
                        <h6 class="mb-2">Các ràng buộc hiện tại</h6>
                        <div id="rang-buoc-list"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bottom action bar --}}
<div class="row mt-3">
    <div class="col-12">
        <div class="d-flex justify-content-between">
            <a href="{{ route('ctdt.show', $ctdt) }}" class="btn btn-secondary" onclick="return confirmExit()">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
            <button type="button" class="btn btn-success" id="btn-save-changes" disabled onclick="handleSaveChanges()">
                <i class="bi bi-check-circle"></i> Lưu thay đổi
            </button>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.hoc-phan-item {
    cursor: pointer;
    transition: all 0.2s;
}

.hoc-phan-item:hover {
    background-color: #f8f9fa;
}

.hoc-phan-item.selected {
    background-color: #d1ecf1;
    border-color: #17a2b8 !important;
    border-width: 2px !important;
}

.rang-buoc-item {
    transition: all 0.2s;
}

.rang-buoc-item.staged-add {
    background-color: #d4edda;
    border-left: 4px solid #28a745;
    font-style: italic;
}

.rang-buoc-item.staged-delete {
    background-color: #f8d7da;
    border-left: 4px solid #dc3545;
    text-decoration: line-through;
    opacity: 0.7;
}

.badge-kieu {
    font-size: 0.85rem;
    padding: 0.35em 0.65em;
}
</style>
@endpush

@push('scripts')
{{-- Simplified JavaScript with vanilla JS for click handling --}}
<script>
// Global state
let selectedHocPhanId = null;
let selectedHocPhanMa = '';
let selectedHocPhanTen = '';
let stagedChanges = [];
let currentRangBuocs = {};

// Handle select hoc phan from column 1
function handleSelectHocPhan(hocPhanId, ma, ten) {
    console.log('[v0] Selected hoc phan:', hocPhanId, ma, ten);
    
    selectedHocPhanId = hocPhanId;
    selectedHocPhanMa = ma;
    selectedHocPhanTen = ten;
    
    // Update UI - remove all selected classes
    document.querySelectorAll('.hoc-phan-item').forEach(item => {
        item.classList.remove('selected');
    });
    
    // Add selected class to clicked item
    const clickedItem = document.querySelector(`[data-hoc-phan-id="${hocPhanId}"]`);
    if (clickedItem) {
        clickedItem.classList.add('selected');
    }
    
    // Show content panel (Bước 5)
    document.getElementById('empty-state').classList.add('d-none');
    document.getElementById('rang-buoc-content').classList.remove('d-none');
    
    // Update selected info
    document.getElementById('selected-hoc-phan-info').innerHTML = `<strong>${ma}</strong> - ${ten}`;
    
    // Load rang buoc for this hoc phan
    loadRangBuoc(hocPhanId);
}

// Load rang buoc data from server
function loadRangBuoc(hocPhanId) {
    fetch(`{{ route('ctdt.show', $ctdt) }}/rang-buoc/${hocPhanId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentRangBuocs[hocPhanId] = data.data;
                renderRangBuocList();
            }
        })
        .catch(error => {
            console.error('[v0] Error loading rang buoc:', error);
            alert('Có lỗi khi tải danh sách ràng buộc');
        });
}

// Render rang buoc list (Phần 2.B)
function renderRangBuocList() {
    const hocPhanId = selectedHocPhanId;
    const rangBuocs = currentRangBuocs[hocPhanId] || {};
    
    let html = '';
    
    // Render by type (Bước 9 - nhóm theo loại)
    const kieuConfigs = {
        'tien_quyet': { label: 'Tiên quyết', color: 'danger' },
        'song_hanh': { label: 'Song hành', color: 'warning' },
        'thay_the': { label: 'Thay thế', color: 'info' }
    };
    
    Object.keys(kieuConfigs).forEach(kieu => {
        const config = kieuConfigs[kieu];
        const items = rangBuocs[kieu] || [];
        
        if (items.length > 0) {
            html += `<div class="mb-3">
                <h6><span class="badge bg-${config.color}">${config.label}</span></h6>
                <div class="list-group">`;
            
            items.forEach(rb => {
                const stagedDelete = stagedChanges.find(c => 
                    c.action === 'delete' && 
                    c.hoc_phan_id == hocPhanId && 
                    c.lien_quan_hp_id == rb.lien_quan_hp_id &&
                    c.kieu === kieu
                );
                
                const cssClass = stagedDelete ? 'staged-delete' : '';
                const btnText = stagedDelete ? 'Hoàn tác' : 'Xóa';
                
                html += `<div class="list-group-item rang-buoc-item ${cssClass}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${rb.lien_quan_hoc_phan.ma_hp}</strong> - ${rb.lien_quan_hoc_phan.ten_hp}
                            <small class="text-muted">(${rb.lien_quan_hoc_phan.so_tinchi} TC)</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger"
                                onclick="handleToggleDelete(${hocPhanId}, ${rb.lien_quan_hp_id}, '${kieu}')">
                            <i class="bi bi-${stagedDelete ? 'arrow-counterclockwise' : 'x'}"></i> ${btnText}
                        </button>
                    </div>
                </div>`;
            });
            
            html += `</div></div>`;
        }
    });
    
    // Render staged adds (Bước 9 - hiển thị thay đổi tạm với màu xanh)
    const stagedAdds = stagedChanges.filter(c => c.action === 'add' && c.hoc_phan_id == hocPhanId);
    if (stagedAdds.length > 0) {
        html += `<div class="mb-3">
            <h6><span class="badge bg-secondary">Mới thêm (chưa lưu)</span></h6>
            <div class="list-group">`;
        
        stagedAdds.forEach((add, index) => {
            const config = kieuConfigs[add.kieu];
            
            html += `<div class="list-group-item rang-buoc-item staged-add">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge badge-kieu bg-${config.color}">${config.label}</span>
                        <strong>${add.hp_data.ma_hp}</strong> - ${add.hp_data.ten_hp}
                        <small class="text-muted">(${add.hp_data.so_tinchi} TC)</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger"
                            onclick="handleUndoAdd(${index})">
                        <i class="bi bi-x"></i> Hủy
                    </button>
                </div>
            </div>`;
        });
        
        html += `</div></div>`;
    }
    
    if (html === '') {
        html = '<p class="text-muted">Chưa có ràng buộc nào</p>';
    }
    
    document.getElementById('rang-buoc-list').innerHTML = html;
}

// Handle add rang buoc (Bước 7-9)
function handleAddRangBuoc() {
    const $select = $('#search-hp-rang-buoc');
    const lienQuanHpId = $select.val();
    const kieu = document.getElementById('loai-rang-buoc').value;
    
    console.log('[v0] Adding rang buoc:', lienQuanHpId, kieu);
    
    if (!lienQuanHpId || !kieu) {
        alert('Vui lòng chọn học phần và loại ràng buộc');
        return;
    }
    
    if (parseInt(lienQuanHpId) === parseInt(selectedHocPhanId)) {
        showErrors([`Học phần "${selectedHocPhanTen}" không thể tự ràng buộc với chính nó (BR3)`]);
        return;
    }
    
    const hpData = $select.data('selected-hp');
    
    if (!hpData) {
        alert('Dữ liệu học phần không hợp lệ');
        return;
    }
    
    // Bước 8: Kiểm tra nhanh không trùng
    const exists = stagedChanges.find(c => 
        c.action === 'add' &&
        c.hoc_phan_id == selectedHocPhanId &&
        c.lien_quan_hp_id == lienQuanHpId &&
        c.kieu === kieu
    );
    
    if (exists) {
        alert('Ràng buộc này đã được thêm');
        return;
    }
    
    const existsInDb = checkExistingRangBuoc(selectedHocPhanId, lienQuanHpId, kieu);
    if (existsInDb) {
        alert('Ràng buộc này đã tồn tại');
        return;
    }
    
    // Bước 8: Ghi nhận tạm (stage)
    stagedChanges.push({
        action: 'add',
        hoc_phan_id: selectedHocPhanId,
        lien_quan_hp_id: lienQuanHpId,
        kieu: kieu,
        hp_data: hpData
    });
    
    // Clear form
    $select.val(null).trigger('change');
    document.getElementById('loai-rang-buoc').value = '';
    
    // Bước 9: Cập nhật UI ngay lập tức
    updateUnsavedStatus();
    renderRangBuocList();
}

function checkExistingRangBuoc(hocPhanId, lienQuanHpId, kieu) {
    const rangBuocs = currentRangBuocs[hocPhanId] || {};
    const items = rangBuocs[kieu] || [];
    return items.some(rb => rb.lien_quan_hp_id == lienQuanHpId);
}

// Handle toggle delete (stage/unstage delete) - Luồng A1
function handleToggleDelete(hocPhanId, lienQuanHpId, kieu) {
    const existingIndex = stagedChanges.findIndex(c => 
        c.action === 'delete' &&
        c.hoc_phan_id == hocPhanId &&
        c.lien_quan_hp_id == lienQuanHpId &&
        c.kieu === kieu
    );
    
    if (existingIndex >= 0) {
        // Unstage delete
        stagedChanges.splice(existingIndex, 1);
    } else {
        // Stage delete
        stagedChanges.push({
            action: 'delete',
            hoc_phan_id: hocPhanId,
            lien_quan_hp_id: lienQuanHpId,
            kieu: kieu
        });
    }
    
    updateUnsavedStatus();
    renderRangBuocList();
}

// Handle undo add
function handleUndoAdd(index) {
    stagedChanges.splice(index, 1);
    updateUnsavedStatus();
    renderRangBuocList();
}

// Update unsaved status indicator
function updateUnsavedStatus() {
    const hasChanges = stagedChanges.length > 0;
    
    if (hasChanges) {
        document.getElementById('unsaved-alert').classList.remove('d-none');
        document.getElementById('btn-save-changes').disabled = false;
    } else {
        document.getElementById('unsaved-alert').classList.add('d-none');
        document.getElementById('btn-save-changes').disabled = true;
    }
}

// Handle save changes (Bước 12-15)
function handleSaveChanges() {
    if (stagedChanges.length === 0) {
        return;
    }
    
    const btn = document.getElementById('btn-save-changes');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang lưu...';
    
    // Bước 13-15: Gửi tất cả thay đổi lên server để validate và lưu
    fetch(`{{ route('ctdt.show', $ctdt) }}/rang-buoc/save`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            changes: stagedChanges
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Bước 15: Lưu thành công
            alert('Cập nhật ràng buộc thành công');
            stagedChanges = [];
            updateUnsavedStatus();
            
            // Reload rang buoc for current hoc phan
            if (selectedHocPhanId) {
                loadRangBuoc(selectedHocPhanId);
            }
        } else if (data.errors) {
            // Bước 14: Có lỗi validation
            showErrors(data.errors);
        }
    })
    .catch(error => {
        console.error('[v0] Error saving:', error);
        alert('Có lỗi xảy ra khi lưu ràng buộc');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle"></i> Lưu thay đổi';
    });
}

// Show errors (Bước 14)
function showErrors(errors) {
    let html = '<ul class="mb-0">';
    errors.forEach(error => {
        html += `<li>${error}</li>`;
    });
    html += '</ul>';
    
    document.getElementById('error-messages').innerHTML = html;
    document.getElementById('error-alert').classList.remove('d-none');
    
    // Scroll to error
    document.getElementById('error-alert').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Confirm exit if has unsaved changes - Luồng A2
function confirmExit() {
    if (stagedChanges.length > 0) {
        return confirm('Các thay đổi chưa lưu sẽ bị mất. Bạn có chắc chắn muốn thoát?');
    }
    return true;
}

// Search in column 1
function initSearch() {
    const searchInput = document.getElementById('search-hoc-phan-ctdt');
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.hoc-phan-item').forEach(item => {
            const ma = item.dataset.ma.toLowerCase();
            const ten = item.dataset.ten.toLowerCase();
            if (ma.includes(query) || ten.includes(query)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
}

// Initialize when DOM ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('[v0] DOM loaded - initializing rang-buoc page');
    initSearch();
    initSelect2ForHocPhan();
});

// Initialize Select2 for searching hoc phan from entire system
function initSelect2ForHocPhan() {
    const $select = $('#search-hp-rang-buoc');
    
    if ($select.length === 0) {
        console.error('[v0] Select2 element not found');
        return;
    }
    
    console.log('[v0] Initializing Select2');
    
    $select.select2({
        ajax: {
            url: '{{ route("api.hoc-phan.search") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                console.log('[v0] Select2 search:', params.term);
                return { q: params.term };
            },
            processResults: function(data) {
                console.log('[v0] Select2 results:', data);
                const filteredData = data.data.filter(hp => hp.id != selectedHocPhanId);
                
                return {
                    results: filteredData.map(hp => ({
                        id: hp.id,
                        text: `${hp.ma_hp} - ${hp.ten_hp} (${hp.so_tinchi} TC)`,
                        hp: hp
                    }))
                };
            },
            cache: true
        },
        placeholder: 'Tìm kiếm học phần...',
        minimumInputLength: 1,
        language: {
            inputTooShort: function() {
                return 'Nhập ít nhất 1 ký tự để tìm kiếm';
            },
            searching: function() {
                return 'Đang tìm kiếm...';
            },
            noResults: function() {
                return 'Không tìm thấy học phần nào';
            }
        }
    }).on('select2:select', function(e) {
        const option = e.params.data;
        console.log('[v0] Selected HP:', option);
        $(this).data('selected-hp', option.hp);
    });
    
    console.log('[v0] Select2 initialized successfully');
}
</script>
@endpush
