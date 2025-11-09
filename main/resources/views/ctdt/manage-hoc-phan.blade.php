@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Quản lý học phần CTĐT</h2>
            <p class="text-muted mb-0">{{ $ctdt->ten }} ({{ $ctdt->ma }})</p>
        </div>
        <a href="{{ route('ctdt.show', $ctdt->id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Updated row to use fixed viewport height and prevent page scroll -->
    <div class="row g-3" style="height: calc(100vh - 220px); min-height: 600px;">
        <!-- Cột 1: Thư viện học phần (Nguồn) -->
        <div class="col-md-6 d-flex flex-column" style="height: 100%;">
            <div class="card shadow-sm d-flex flex-column" style="height: 100%; overflow: hidden;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-book"></i> Thư viện học phần
                    </h5>
                </div>
                <!-- Card body now uses flex-column with fixed heights for search and scrollable list -->
                <div class="card-body d-flex flex-column p-0" style="flex: 1; min-height: 0;">
                    <!-- Tìm kiếm và lọc - Fixed height -->
                    <div class="p-3 border-bottom bg-light" style="flex-shrink: 0;">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="text" id="searchInput" class="form-control" 
                                       placeholder="Tìm theo tên hoặc mã học phần...">
                            </div>
                            <div class="col-md-4">
                                <select id="khoaFilter" class="form-select">
                                    <option value="">-- Tất cả khoa --</option>
                                    @foreach($khoas as $khoa)
                                        <option value="{{ $khoa->id }}">{{ $khoa->ten }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button id="searchBtn" class="btn btn-primary w-100">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('hoc-phan.create') }}" target="_blank" 
                               class="btn btn-sm btn-outline-success">
                                <i class="bi bi-plus-circle"></i> Tạo học phần mới
                            </a>
                        </div>
                    </div>

                    <!-- Danh sách học phần - Scrollable area with flex-grow -->
                    <div class="p-3" id="hocPhanList" style="flex: 1; overflow-y: auto; min-height: 0;">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-search fs-1"></i>
                            <p>Nhập từ khóa để tìm kiếm học phần</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột 2: Cấu trúc CTĐT (Đích) -->
        <div class="col-md-6 d-flex flex-column" style="height: 100%;">
            <div class="card shadow-sm d-flex flex-column" style="height: 100%; overflow: hidden;">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-diagram-3"></i> {{ $ctdt->ten }}
                        </h5>
                        <button id="addKhoiBtn" class="btn btn-sm btn-light" 
                                onclick="window.open('{{ route('khoi-kien-thuc.create') }}', '_blank')">
                            <i class="bi bi-plus-circle"></i> Thêm khối
                        </button>
                    </div>
                </div>
                <!-- Card body with fixed info alert and scrollable structure -->
                <div class="card-body d-flex flex-column p-0" style="flex: 1; min-height: 0;">
                    <!-- Alert - Fixed height -->
                    <div class="alert alert-info m-3 mb-0" role="alert" style="flex-shrink: 0;">
                        <i class="bi bi-info-circle"></i>
                        <strong>Hướng dẫn:</strong> Chọn một khối kiến thức bên dưới, sau đó nhấn nút 
                        <span class="badge bg-primary">[+]</span> bên cột trái để thêm học phần vào khối đã chọn.
                    </div>

                    <!-- Cấu trúc cây khối kiến thức - Scrollable area -->
                    <div class="p-3" id="ctdtStructure" style="flex: 1; overflow-y: auto; min-height: 0;">
                        <div class="text-center text-muted py-5">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Đang tải...</span>
                            </div>
                            <p class="mt-2">Đang tải cấu trúc CTĐT...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer với nút Lưu -->
    <div class="mt-3 d-flex justify-content-between align-items-center">
        <div>
            <span class="badge bg-info" id="additionCount">0 thêm mới</span>
            <span class="badge bg-danger" id="deletionCount">0 xóa</span>
        </div>
        <div>
            <button id="cancelBtn" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Hủy
            </button>
            <button id="saveBtn" class="btn btn-success" disabled>
                <i class="bi bi-check-circle"></i> Lưu tất cả thay đổi
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// State management
let selectedKhoiId = null;
let ctdtStructure = [];
let additions = []; // [{hoc_phan_id, khoi_id, loai, hocPhanData}]
let deletions = []; // [ctdt_hoc_phan_id]

// Load CTDT structure on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCtdtStructure();
    searchHocPhan();
    
    // Search button
    document.getElementById('searchBtn').addEventListener('click', searchHocPhan);
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.which === 13) searchHocPhan();
    });
    
    // Cancel button
    document.getElementById('cancelBtn').addEventListener('click', function() {
        if (additions.length > 0 || deletions.length > 0) {
            if (confirm('Bạn có chắc muốn hủy tất cả thay đổi chưa lưu?')) {
                window.location.href = '{{ route("ctdt.show", $ctdt->id) }}';
            }
        } else {
            window.location.href = '{{ route("ctdt.show", $ctdt->id) }}';
        }
    });
    
    // Save button
    document.getElementById('saveBtn').addEventListener('click', saveChanges);
});

// Load CTDT structure
function loadCtdtStructure() {
    console.log('[v0] Loading CTDT structure...');
    const url = '{{ route("ctdt.structure", $ctdt->id) }}';
    console.log('[v0] Fetching URL:', url);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('[v0] Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('[v0] CTDT structure loaded:', data);
        ctdtStructure = data.structure || [];
        renderCtdtStructure();
    })
    .catch(error => {
        console.error('[v0] Failed to load CTDT structure:', error);
        document.getElementById('ctdtStructure').innerHTML = `
            <div class="alert alert-danger m-3">
                <i class="bi bi-exclamation-triangle"></i> 
                Không thể tải cấu trúc CTĐT. 
                <br><small>Lỗi: ${error.message}</small>
                <br><button class="btn btn-sm btn-primary mt-2" onclick="loadCtdtStructure()">
                    <i class="bi bi-arrow-clockwise"></i> Thử lại
                </button>
            </div>
        `;
    });
}

// Render CTDT structure tree
function renderCtdtStructure() {
    let html = '<div class="accordion" id="khoiAccordion">';
    
    ctdtStructure.forEach((khoi, index) => {
        const isSelected = selectedKhoiId === khoi.khoi_id;
        const khoiClass = isSelected ? 'border-success border-3' : '';
        const hocPhans = khoi.hoc_phans || [];
        
        // Get additions for this khoi
        const khoiAdditions = additions.filter(a => a.khoi_id === khoi.khoi_id);
        
        // Calculate total credits
        let totalCredits = 0;
        hocPhans.forEach(hp => {
            if (!deletions.includes(hp.id)) {
                totalCredits += hp.so_tinchi;
            }
        });
        khoiAdditions.forEach(add => {
            totalCredits += add.hocPhanData.so_tinchi;
        });
        
        html += `
            <div class="accordion-item ${khoiClass}">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#khoi${khoi.khoi_id}"
                            aria-expanded="false"
                            aria-controls="khoi${khoi.khoi_id}">
                        <span class="fw-bold">${khoi.ten}</span>
                        <span class="badge bg-secondary ms-2">${totalCredits} TC</span>
                        ${isSelected ? '<span class="badge bg-success ms-2">Đang chọn</span>' : ''}
                    </button>
                </h2>
                <div id="khoi${khoi.khoi_id}" 
                     class="accordion-collapse collapse" 
                     data-bs-parent="#khoiAccordion">
                    <div class="accordion-body" onclick="selectKhoi(${khoi.khoi_id})">
                        <div class="list-group list-group-flush" id="hocPhanList${khoi.khoi_id}">
        `;
        
        // Existing hoc phans
        hocPhans.forEach(hp => {
            const isDeleted = deletions.includes(hp.id);
            const itemClass = isDeleted ? 'text-decoration-line-through text-danger bg-danger bg-opacity-10' : '';
            
            html += `
                <div class="list-group-item ${itemClass}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="fw-bold">${hp.ma_hp} - ${hp.ten_hp}</div>
                            <small class="text-muted">${hp.so_tinchi} TC • ${hp.loai === 'bat_buoc' ? 'Bắt buộc' : 'Tự chọn'}</small>
                            ${isDeleted ? '<span class="badge bg-danger ms-2">Sẽ xóa</span>' : ''}
                        </div>
                        <button class="btn btn-sm ${isDeleted ? 'btn-outline-success' : 'btn-outline-danger'}" 
                                onclick="${isDeleted ? 'undoDelete' : 'markForDeletion'}(${hp.id})">
                            <i class="bi bi-${isDeleted ? 'arrow-counterclockwise' : 'trash'}"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        
        // Added hoc phans (ghi nhận tạm)
        khoiAdditions.forEach(add => {
            const hp = add.hocPhanData;
            html += `
                <div class="list-group-item bg-success bg-opacity-10 border-success">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="fw-bold">${hp.ma_hp} - ${hp.ten_hp}</div>
                            <small class="text-muted">${hp.so_tinchi} TC</small>
                            <span class="badge bg-success ms-2">Thêm mới</span>
                        </div>
                        <button class="btn btn-sm btn-outline-danger" 
                                onclick="removeAddition(${add.hoc_phan_id}, ${khoi.khoi_id})">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        
        if (hocPhans.length === 0 && khoiAdditions.length === 0) {
            html += `
                <div class="text-center text-muted py-3">
                    <i class="bi bi-inbox"></i>
                    <p class="mb-0">Chưa có học phần nào</p>
                </div>
            `;
        }
        
        html += `
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    
    html += `
        <div class="mt-3 p-3 border-top bg-light">
            <p class="text-muted small mb-2">
                <i class="bi bi-info-circle"></i> Muốn thêm khối kiến thức khác?
            </p>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-success" onclick="showAddKhoiModal()">
                    <i class="bi bi-plus-circle"></i> Thêm khối từ hệ thống
                </button>
                <a href="{{ route('khoi-kien-thuc.create') }}" target="_blank" 
                   class="btn btn-sm btn-outline-success">
                    <i class="bi bi-plus-circle"></i> Tạo khối mới
                </a>
            </div>
        </div>
    `;
    
    document.getElementById('ctdtStructure').innerHTML = html;
}

// Select khoi as target
function selectKhoi(khoiId) {
    selectedKhoiId = khoiId;
    renderCtdtStructure();
}

// Search hoc phan
function searchHocPhan() {
    const search = document.getElementById('searchInput').value;
    const khoaId = document.getElementById('khoaFilter').value;
    
    document.getElementById('hocPhanList').innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Đang tìm...</span>
            </div>
        </div>
    `;
    
    // Build query string
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (khoaId) params.append('khoa_id', khoaId);
    
    const url = '{{ route("api.hoc-phan.search") }}?' + params.toString();
    console.log('[v0] Searching hoc phan:', url);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('[v0] Response status:', response.status);
        console.log('[v0] Response headers:', response.headers);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('[v0] Error response body:', text);
                throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('[v0] Hoc phan list loaded:', data);
        renderHocPhanList(data.data);
    })
    .catch(error => {
        console.error('[v0] Failed to search hoc phan:', error);
        document.getElementById('hocPhanList').innerHTML = `
            <div class="alert alert-danger">Có lỗi khi tìm kiếm: ${error.message}</div>
        `;
    });
}

// Render hoc phan list
function renderHocPhanList(hocPhans) {
    if (hocPhans.length === 0) {
        document.getElementById('hocPhanList').innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1"></i>
                <p>Không tìm thấy học phần nào</p>
            </div>
        `;
        return;
    }
    
    let html = '<div class="list-group list-group-flush">';
    
    hocPhans.forEach(hp => {
        // Check if already added
        const alreadyInCTDT = ctdtStructure.some(k => 
            k.hoc_phans.some(h => h.hoc_phan_id === hp.id)
        );
        const alreadyInAdditions = additions.some(a => a.hoc_phan_id === hp.id);
        const isAdded = alreadyInCTDT || alreadyInAdditions;
        
        html += `
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="fw-bold">${hp.ma_hp} - ${hp.ten_hp}</div>
                        <small class="text-muted">
                            ${hp.so_tinchi} TC • ${hp.khoa ? hp.khoa.ten : 'N/A'}
                            ${!hp.active ? '<span class="badge bg-danger">Ngưng hoạt động</span>' : ''}
                        </small>
                        ${isAdded ? '<span class="badge bg-secondary ms-2">Đã có trong CTĐT</span>' : ''}
                    </div>
                    <button class="btn btn-sm btn-primary" 
                            onclick="addHocPhan(${hp.id}, '${hp.ma_hp}', '${hp.ten_hp}', ${hp.so_tinchi})"
                            ${isAdded || !hp.active ? 'disabled' : ''}>
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    document.getElementById('hocPhanList').innerHTML = html;
}

// Add hoc phan to selected khoi (ghi nhận tạm)
function addHocPhan(hocPhanId, maHp, tenHp, soTinchi) {
    if (!selectedKhoiId) {
        alert('Vui lòng chọn một khối kiến thức (bên phải) trước khi thêm học phần.');
        return;
    }
    
    // Check if already added
    if (additions.some(a => a.hoc_phan_id === hocPhanId)) {
        alert('Học phần này đã được thêm vào.');
        return;
    }
    
    // Add to additions array
    additions.push({
        hoc_phan_id: hocPhanId,
        khoi_id: selectedKhoiId,
        loai: 'bat_buoc',
        hocPhanData: {
            ma_hp: maHp,
            ten_hp: tenHp,
            so_tinchi: soTinchi
        }
    });
    
    updateCounters();
    renderCtdtStructure();
    searchHocPhan(); // Refresh list to show "Đã có" badge
}

// Remove from additions (undo add)
function removeAddition(hocPhanId, khoiId) {
    additions = additions.filter(a => !(a.hoc_phan_id === hocPhanId && a.khoi_id === khoiId));
    updateCounters();
    renderCtdtStructure();
    searchHocPhan();
}

// Mark for deletion
function markForDeletion(ctdtHocPhanId) {
    if (!deletions.includes(ctdtHocPhanId)) {
        deletions.push(ctdtHocPhanId);
    }
    updateCounters();
    renderCtdtStructure();
}

// Undo deletion
function undoDelete(ctdtHocPhanId) {
    deletions = deletions.filter(id => id !== ctdtHocPhanId);
    updateCounters();
    renderCtdtStructure();
}

// Update counters
function updateCounters() {
    document.getElementById('additionCount').textContent = `${additions.length} thêm mới`;
    document.getElementById('deletionCount').textContent = `${deletions.length} xóa`;
    
    // Enable/disable save button
    if (additions.length > 0 || deletions.length > 0) {
        document.getElementById('saveBtn').disabled = false;
    } else {
        document.getElementById('saveBtn').disabled = true;
    }
}

// Save all changes
function saveChanges() {
    if (additions.length === 0 && deletions.length === 0) {
        alert('Không có thay đổi nào để lưu.');
        return;
    }
    
    if (!confirm(`Bạn có chắc muốn lưu ${additions.length} thêm mới và ${deletions.length} xóa?`)) {
        return;
    }
    
    document.getElementById('saveBtn').disabled = true;
    document.getElementById('saveBtn').innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang lưu...';
    
    fetch('{{ route("ctdt.save-changes", $ctdt->id) }}', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            additions: additions.map(a => ({
                hoc_phan_id: a.hoc_phan_id,
                khoi_id: a.khoi_id,
                loai: a.loai
            })),
            deletions: deletions
        })
    })
    .then(response => {
        console.log('[v0] Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('[v0] Save changes response:', data);
        if (data.success) {
            alert('Lưu thay đổi thành công!');
            window.location.href = '{{ route("ctdt.show", $ctdt->id) }}';
        } else {
            alert('Có lỗi: ' + (data.errors ? data.errors.join('\n') : 'Unknown error'));
            document.getElementById('saveBtn').disabled = false;
            document.getElementById('saveBtn').innerHTML = '<i class="bi bi-check-circle"></i> Lưu tất cả thay đổi';
        }
    })
    .catch(error => {
        console.error('[v0] Failed to save changes:', error);
        alert('Có lỗi xảy ra khi lưu thay đổi.');
        document.getElementById('saveBtn').disabled = false;
        document.getElementById('saveBtn').innerHTML = '<i class="bi bi-check-circle"></i> Lưu tất cả thay đổi';
    });
}

function showAddKhoiModal() {
    // Load all available khoi kien thuc from system
    const ctdtId = {{ $ctdt->id }};
    const url = `/api/ctdt/${ctdtId}/khoi-kien-thuc/available`;
    console.log('[v0] Loading available khoi from:', url);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        console.log('[v0] Response status:', response.status);
        console.log('[v0] Response headers:', response.headers);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('[v0] Error response body:', text);
                throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('[v0] Available khoi loaded:', data);
        
        if (!Array.isArray(data)) {
            console.error('[v0] Response is not an array:', data);
            alert('Dữ liệu trả về không đúng định dạng.');
            return;
        }
        
        if (data.length === 0) {
            alert('Không có khối kiến thức nào khả dụng. Vui lòng tạo khối mới.');
            return;
        }
        
        // Build modal HTML
        let modalHtml = `
            <div class="modal fade" id="selectKhoiModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">Chọn khối kiến thức từ hệ thống</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="list-group" id="availableKhoiList">
        `;
        
        data.forEach(khoi => {
            const khoiTen = khoi.ten.replace(/'/g, "\\'");
            const khoiMa = khoi.ma.replace(/'/g, "\\'");
            const khoiMoTa = khoi.mo_ta ? khoi.mo_ta : 'Không có mô tả';
            
            modalHtml += `
                <div class="list-group-item list-group-item-action" style="cursor: pointer;" 
                     onclick="addKhoiToCTDT(${khoi.id}, '${khoiTen}', '${khoiMa}')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold">${khoi.ma} - ${khoi.ten}</div>
                            <small class="text-muted">${khoiMoTa}</small>
                        </div>
                        <i class="bi bi-plus-circle text-success fs-4"></i>
                    </div>
                </div>
            `;
        });
        
        modalHtml += `
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove old modal if exists
        const oldModal = document.getElementById('selectKhoiModal');
        if (oldModal) oldModal.remove();
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('selectKhoiModal'));
        modal.show();
    })
    .catch(error => {
        console.error('[v0] Error loading available khoi:', error);
        alert('Có lỗi khi tải danh sách khối kiến thức: ' + error.message);
    });
}

function addKhoiToCTDT(khoiId, khoiTen, khoiMa) {
    // Check if already in structure
    if (ctdtStructure.some(k => k.khoi_id === khoiId)) {
        alert(`Khối "${khoiTen}" đã có trong CTĐT này.`);
        return;
    }
    
    // Add to structure temporarily
    ctdtStructure.push({
        id: null, // New khoi, not saved yet
        khoi_id: khoiId,
        ten: khoiTen,
        ma: khoiMa,
        hoc_phans: [],
        is_new: true
    });
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('selectKhoiModal'));
    if (modal) modal.hide();
    
    // Re-render structure
    renderCtdtStructure();
    
    // Mark as having changes
    updateCounters();
    
    alert(`Đã thêm khối "${khoiTen}" vào CTĐT. Nhớ nhấn "Lưu tất cả thay đổi" để lưu.`);
}
</script>
@endpush

@endsection
