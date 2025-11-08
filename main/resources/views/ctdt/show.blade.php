@extends('layouts.app')

@section('title', 'Chi tiết Chương trình Đào tạo')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h1 class="h3">{{ $ctdt->ten }}</h1>
            <p class="text-muted">Mã: {{ $ctdt->ma_ctdt }}</p>
        </div>
        <div>
            <span class="badge-status status-{{ $ctdt->trang_thai }} mb-2 d-block">
                {{ ucfirst(str_replace('_', ' ', $ctdt->trang_thai)) }}
            </span>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Thông tin chung</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Added Bac Hoc display -->
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Bậc học</small>
                        <p>{{ $ctdt->bacHoc->ten ?? 'N/A' }}</p>
                    </div>
                    <!-- Added Loai Hinh Dao Tao display -->
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Loại hình đào tạo</small>
                        <p>{{ $ctdt->loaiHinhDaoTao->ten ?? 'N/A' }}</p>
                    </div>
                    <!-- Added HeDaoTao display -->
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Hệ đào tạo</small>
                        <p>{{ $ctdt->heDaoTao?->ten ?? 'N/A' }}</p>
                    </div>
                    <!-- Added Khoa display -->
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Khoa</small>
                        <p>{{ $ctdt->khoa->ten ?? 'N/A' }}</p>
                    </div>
                    <!-- Added Nganh display -->
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Ngành</small>
                        <p>{{ $ctdt->nganh->ten ?? 'N/A' }} ({{ $ctdt->nganh->ma ?? '' }})</p>
                    </div>
                    <!-- Added Chuyen Nganh display -->
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Chuyên ngành</small>
                        <p>{{ $ctdt->chuyenNganh?->ten ?? 'Đại trà' }}</p>
                    </div>
                    <!-- Removed heDaoTao field since we removed the relationship -->
                    <!-- Added Khoa Hoc display -->
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Khóa học</small>
                        <p>{{ $ctdt->khoaHoc->ma ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Niên khóa</small>
                        <p>{{ $ctdt->nienKhoa->ma }} ({{ $ctdt->nienKhoa->nam_bat_dau }}-{{ $ctdt->nienKhoa->nam_ket_thuc }})</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Hiệu lực từ</small>
                        <p>{{ $ctdt->hieu_luc_tu->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Hiệu lực đến</small>
                        <p>{{ $ctdt->hieu_luc_den ? $ctdt->hieu_luc_den->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    @if ($ctdt->mo_ta)
                    <div class="col-md-12">
                        <small class="text-muted">Mô tả</small>
                        <p>{{ $ctdt->mo_ta }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between">
                <h6 class="mb-0">Học phần</h6>
                @can('update', $ctdt)
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addHocPhanModal">
                    <i class="fas fa-plus"></i> Thêm
                </button>
                @endcan
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã</th>
                            <th>Tên</th>
                            <th>Tín chỉ</th>
                            <th>Khối</th>
                            <th>Loại</th>
                            <th style="width: 80px;">Thứ tự</th>
                            @can('update', $ctdt)<th style="width: 60px;">Xóa</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ctdt->ctdtHocPhans->sortBy('thu_tu') as $ctdtHocPhan)
                        <tr>
                            <td>{{ $ctdtHocPhan->hocPhan->ma_hp }}</td>
                            <td>{{ $ctdtHocPhan->hocPhan->ten_hp }}</td>
                            <td>{{ $ctdtHocPhan->hocPhan->so_tinchi }}</td>
                            <td>{{ $ctdtHocPhan->khoiKienThuc->ten ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $ctdtHocPhan->loai === 'bat_buoc' ? 'danger' : 'warning' }}">
                                    {{ $ctdtHocPhan->loai === 'bat_buoc' ? 'Bắt buộc' : 'Tự chọn' }}
                                </span>
                            </td>
                            <td>{{ $ctdtHocPhan->thu_tu }}</td>
                            @can('update', $ctdt)
                            <td>
                                <form method="POST" action="{{ route('ctdt-item.remove-hoc-phan', [$ctdt, $ctdtHocPhan->hocPhan]) }}" style="display: inline;" onsubmit="return confirm('Xóa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                            @endcan
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">Chưa có học phần nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Hành động</h6>
            </div>
            <div class="card-body d-grid gap-2">
                @can('update', $ctdt)
                <a href="{{ route('ctdt.edit', $ctdt) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Sửa
                </a>
                <a href="{{ route('ctdt.clone', $ctdt) }}" class="btn btn-info" onclick="return confirm('Sao chép CTĐT?');">
                    <i class="fas fa-copy"></i> Sao chép
                </a>
                @if ($ctdt->trang_thai === 'draft')
                <form method="POST" action="{{ route('ctdt.send-for-approval', $ctdt) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Gửi phê duyệt?');">
                        <i class="fas fa-paper-plane"></i> Gửi phê duyệt
                    </button>
                </form>
                @endif
                @endcan
                
                @can('approve', $ctdt)
                @if ($ctdt->trang_thai === 'pending')
                <form method="POST" action="{{ route('ctdt-approval.approve', $ctdt) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-check"></i> Phê duyệt
                    </button>
                </form>
                @endif
                @endcan
                
                @can('publish', $ctdt)
                @if ($ctdt->trang_thai === 'approved')
                <form method="POST" action="{{ route('ctdt-approval.publish', $ctdt) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-globe"></i> Công bố
                    </button>
                </form>
                @endif
                @endcan
                
                <a href="{{ route('ctdt.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Thông tin thêm</h6>
            </div>
            <div class="card-body">
                <small class="text-muted">Tạo bởi</small>
                <p>{{ $ctdt->nguoiTao?->name ?? 'N/A' }}</p>
                
                <small class="text-muted">Ngày tạo</small>
                <p>{{ $ctdt->created_at->format('d/m/Y H:i') }}</p>
                
                <small class="text-muted">Cập nhật lúc</small>
                <p>{{ $ctdt->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>

@can('update', $ctdt)
<div class="modal fade" id="addHocPhanModal" tabindex="-1">
    <div class="modal-dialog modal-xl" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-layer-group me-2"></i>
                    Quản lý học phần CTĐT
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="height: 70vh;">
                <div class="row g-0 h-100">
                    <!-- Panel 1: Khung chương trình (Left Panel) -->
                    <div class="col-md-7 border-end" style="overflow-y: auto;">
                        <div class="p-3 bg-light border-bottom sticky-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-book me-2"></i>
                                    Danh sách học phần
                                </h6>
                                <button type="button" class="btn btn-sm btn-primary" id="btnSelectHocPhan" disabled>
                                    <i class="fas fa-plus me-1"></i>
                                    Chọn học phần
                                </button>
                            </div>
                            <div id="selectedKhoiInfo" class="mt-2 text-muted small">
                                <i class="fas fa-info-circle"></i> Chọn một khối kiến thức để xem danh sách học phần
                            </div>
                        </div>
                        <div id="hocPhanListPanel" class="p-3">
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-arrow-right fa-3x mb-3 opacity-25"></i>
                                <p>Chọn một khối kiến thức từ panel bên phải</p>
                            </div>
                        </div>
                    </div>

                    <!-- Panel 2: Danh sách khối kiến thức (Right Panel) -->
                    <div class="col-md-5" style="overflow-y: auto; background: #f8f9fa;">
                        <div class="p-3 bg-white border-bottom sticky-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-layer-group me-2"></i>
                                    Khối kiến thức
                                </h6>
                                <a href="{{ route('khoi-kien-thuc.create') }}" 
                                   class="btn btn-sm btn-success" 
                                   target="_blank"
                                   title="Mở trang quản lý khối kiến thức trong tab mới">
                                    <i class="fas fa-plus me-1"></i>
                                    Thêm khối
                                </a>
                            </div>
                        </div>
                        <div id="khoiListPanel" class="p-3">
                            @forelse ($allKhoiKienThuc as $khoi)
                            <div class="card mb-2 khoi-item" 
                                data-khoi-id="{{ $khoi->id }}"
                                data-khoi-ten="{{ $khoi->ten }}"
                                style="cursor: pointer; transition: all 0.2s;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $khoi->ten }}</h6>
                                            <small class="text-muted">Mã: {{ $khoi->ma }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-info hoc-phan-count">0</span>
                                            <small class="d-block text-muted">học phần</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Chưa có khối kiến thức nào. Vui lòng thêm khối kiến thức trước.
                                <div class="mt-2">
                                    <a href="{{ route('khoi-kien-thuc.create') }}" 
                                       class="btn btn-sm btn-primary" 
                                       target="_blank">
                                        <i class="fas fa-plus me-1"></i>
                                        Thêm khối kiến thức mới
                                    </a>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Đóng
                </button>
                <button type="button" class="btn btn-success" id="btnSaveAll" disabled>
                    <i class="fas fa-save me-1"></i>
                    Lưu tất cả
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Popup con: Danh sách học phần hệ thống -->
<div class="modal fade" id="selectHocPhanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-search me-2"></i>
                    Chọn học phần từ hệ thống
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Tìm kiếm và lọc -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small">Tìm kiếm</label>
                        <input type="text" class="form-control" id="searchHocPhan" 
                            placeholder="Tìm theo tên hoặc mã học phần...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Khoa</label>
                        <select class="form-select" id="filterKhoa">
                            <option value="">Tất cả</option>
                            <!-- Will be populated dynamically -->
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Bộ môn</label>
                        <select class="form-select" id="filterBoMon">
                            <option value="">Tất cả</option>
                            <!-- Will be populated dynamically -->
                        </select>
                    </div>
                </div>

                <!-- Danh sách học phần -->
                <div id="availableHocPhanList" style="max-height: 400px; overflow-y: auto;">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                    </div>
                </div>

                <!-- Phân trang -->
                <div id="paginationContainer" class="mt-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Hủy
                </button>
                <button type="button" class="btn btn-primary" id="btnConfirmSelection">
                    <i class="fas fa-check me-1"></i>
                    Lưu
                </button>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection

@push('styles')
<style>
.modal-backdrop {
    z-index: 1040;
}

.modal {
    z-index: 1050;
}

.modal.show {
    display: block;
}

#selectHocPhanModal {
    z-index: 1060;
}

#selectHocPhanModal + .modal-backdrop {
    z-index: 1055;
}

/* Ensure modal content is above backdrop */
.modal-dialog {
    position: relative;
    z-index: 1051;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctdtId = {{ $ctdt->id }};
    let selectedKhoiId = null;
    let selectedKhoiTen = '';
    let tempSelectedHocPhans = {};
    let selectedHocPhansForAdd = [];

    const mainModal = new bootstrap.Modal(document.getElementById('addHocPhanModal'), {
        backdrop: true,
        keyboard: true
    });
    
    const selectModal = new bootstrap.Modal(document.getElementById('selectHocPhanModal'), {
        backdrop: true,
        keyboard: true
    });

    // Panel 2: Click khối kiến thức
    document.querySelectorAll('.khoi-item').forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all
            document.querySelectorAll('.khoi-item').forEach(k => {
                k.classList.remove('border-primary', 'bg-light');
            });
            
            // Add active class to selected
            this.classList.add('border-primary', 'bg-light');
            
            selectedKhoiId = this.dataset.khoiId;
            selectedKhoiTen = this.dataset.khoiTen;
            
            // Enable "Chọn học phần" button
            document.getElementById('btnSelectHocPhan').disabled = false;
            document.getElementById('btnSaveAll').disabled = false;
            
            // Update info text
            document.getElementById('selectedKhoiInfo').innerHTML = 
                `<i class="fas fa-check-circle text-success"></i> Đang xem: <strong>${selectedKhoiTen}</strong>`;
            
            // Load học phần của khối này
            loadHocPhansByKhoi(selectedKhoiId);
        });
    });

    // Load danh sách học phần của một khối
    function loadHocPhansByKhoi(khoiId) {
        const panel = document.getElementById('hocPhanListPanel');
        panel.innerHTML = '<div class="text-center py-4"><div class="spinner-border" role="status"></div></div>';
        
        fetch(`/ctdt/${ctdtId}/khoi/${khoiId}/hoc-phans`)
            .then(response => response.json())
            .then(data => {
                // Merge with temp selections
                const allHocPhans = [...data];
                if (tempSelectedHocPhans[khoiId]) {
                    allHocPhans.push(...tempSelectedHocPhans[khoiId]);
                }
                
                renderHocPhanList(allHocPhans, khoiId);
                
                // Update count badge
                const khoiCard = document.querySelector(`.khoi-item[data-khoi-id="${khoiId}"]`);
                if (khoiCard) {
                    khoiCard.querySelector('.hoc-phan-count').textContent = allHocPhans.length;
                }
            })
            .catch(error => {
                console.error('[v0] Error loading hoc phans:', error);
                panel.innerHTML = '<div class="alert alert-danger">Không thể tải danh sách học phần</div>';
            });
    }

    // Render danh sách học phần
    function renderHocPhanList(hocPhans, khoiId) {
        const panel = document.getElementById('hocPhanListPanel');
        
        if (hocPhans.length === 0) {
            panel.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                    <p>Chưa có học phần nào trong khối này</p>
                </div>`;
            return;
        }
        
        let html = '<div class="table-responsive"><table class="table table-sm table-hover mb-0">';
        html += '<thead class="table-light"><tr>';
        html += '<th style="width: 100px;">Mã HP</th>';
        html += '<th>Tên học phần</th>';
        html += '<th style="width: 80px;">Tín chỉ</th>';
        html += '<th style="width: 100px;">Loại</th>';
        html += '<th style="width: 60px;"></th>';
        html += '</tr></thead><tbody>';
        
        hocPhans.forEach(hp => {
            const isNew = hp.isNew === true;
            const rowClass = isNew ? 'table-success' : '';
            const badge = isNew ? '<span class="badge bg-success ms-2">Mới</span>' : '';
            
            html += `<tr class="${rowClass}">`;
            html += `<td>${hp.ma_hp}</td>`;
            html += `<td>${hp.ten_hp}${badge}</td>`;
            html += `<td>${hp.so_tinchi}</td>`;
            html += `<td><span class="badge bg-${hp.loai === 'bat_buoc' ? 'danger' : 'warning'}">${hp.loai === 'bat_buoc' ? 'Bắt buộc' : 'Tự chọn'}</span></td>`;
            html += `<td>`;
            if (isNew) {
                html += `<button class="btn btn-sm btn-danger" onclick="removeNewHocPhan(${hp.hoc_phan_id}, ${khoiId})"><i class="fas fa-times"></i></button>`;
            }
            html += `</td>`;
            html += '</tr>';
        });
        
        html += '</tbody></table></div>';
        panel.innerHTML = html;
    }

    // Remove newly added hoc phan from temp
    window.removeNewHocPhan = function(hocPhanId, khoiId) {
        if (tempSelectedHocPhans[khoiId]) {
            tempSelectedHocPhans[khoiId] = tempSelectedHocPhans[khoiId].filter(hp => hp.hoc_phan_id !== hocPhanId);
        }
        loadHocPhansByKhoi(khoiId);
    };

    // Button "Chọn học phần" - mở popup con
    document.getElementById('btnSelectHocPhan').addEventListener('click', function() {
        if (!selectedKhoiId) {
            alert('Vui lòng chọn một khối kiến thức trước');
            return;
        }
        
        selectedHocPhansForAdd = [];
        selectModal.show();
        loadAvailableHocPhans();
    });

    // Load available hoc phans (popup con)
    let currentPage = 1;
    function loadAvailableHocPhans(page = 1) {
        const search = document.getElementById('searchHocPhan').value;
        const khoaId = document.getElementById('filterKhoa').value;
        const boMonId = document.getElementById('filterBoMon').value;
        
        const params = new URLSearchParams({
            khoi_id: selectedKhoiId,
            search: search,
            page: page,
            per_page: 10
        });
        
        if (khoaId) params.append('khoa_id', khoaId);
        if (boMonId) params.append('bo_mon_id', boMonId);
        
        const container = document.getElementById('availableHocPhanList');
        container.innerHTML = '<div class="text-center py-4"><div class="spinner-border" role="status"></div></div>';
        
        fetch(`/ctdt/${ctdtId}/available-hoc-phans?${params}`)
            .then(response => response.json())
            .then(result => {
                renderAvailableHocPhans(result.data);
                renderPagination(result.current_page, result.last_page);
                currentPage = result.current_page;
            })
            .catch(error => {
                console.error('[v0] Error loading available hoc phans:', error);
                container.innerHTML = '<div class="alert alert-danger">Không thể tải danh sách</div>';
            });
    }

    // Render available hoc phans
    function renderAvailableHocPhans(hocPhans) {
        const container = document.getElementById('availableHocPhanList');
        
        if (hocPhans.length === 0) {
            container.innerHTML = '<div class="alert alert-info">Không tìm thấy học phần nào</div>';
            return;
        }
        
        let html = '<div class="list-group">';
        hocPhans.forEach(hp => {
            const isSelected = selectedHocPhansForAdd.some(s => s.id === hp.id);
            html += `
                <div class="list-group-item list-group-item-action">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                            value="${hp.id}" 
                            id="hp_${hp.id}"
                            data-ma="${hp.ma_hp}"
                            data-ten="${hp.ten_hp}"
                            data-tinchi="${hp.so_tinchi}"
                            ${isSelected ? 'checked' : ''}
                            onchange="toggleHocPhanSelection(this)">
                        <label class="form-check-label w-100" for="hp_${hp.id}">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>${hp.ma_hp}</strong> - ${hp.ten_hp}
                                    <br><small class="text-muted">${hp.khoa?.ten || 'N/A'} • ${hp.so_tinchi} TC</small>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>`;
        });
        html += '</div>';
        container.innerHTML = html;
    }

    // Toggle selection
    window.toggleHocPhanSelection = function(checkbox) {
        const id = parseInt(checkbox.value);
        const ma = checkbox.dataset.ma;
        const ten = checkbox.dataset.ten;
        const tinchi = parseInt(checkbox.dataset.tinchi);
        
        if (checkbox.checked) {
            selectedHocPhansForAdd.push({id, ma_hp: ma, ten_hp: ten, so_tinchi: tinchi});
        } else {
            selectedHocPhansForAdd = selectedHocPhansForAdd.filter(hp => hp.id !== id);
        }
    };

    // Render pagination
    function renderPagination(current, last) {
        const container = document.getElementById('paginationContainer');
        if (last <= 1) {
            container.innerHTML = '';
            return;
        }
        
        let html = '<nav><ul class="pagination pagination-sm justify-content-center mb-0">';
        
        for (let i = 1; i <= last; i++) {
            html += `<li class="page-item ${i === current ? 'active' : ''}">
                <a class="page-link" href="#" onclick="loadAvailableHocPhans(${i}); return false;">${i}</a>
            </li>`;
        }
        
        html += '</ul></nav>';
        container.innerHTML = html;
    }

    // Make loadAvailableHocPhans accessible
    window.loadAvailableHocPhans = loadAvailableHocPhans;

    // Search and filter handlers
    let searchTimeout;
    document.getElementById('searchHocPhan').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadAvailableHocPhans(1), 300);
    });
    
    document.getElementById('filterKhoa').addEventListener('change', () => loadAvailableHocPhans(1));
    document.getElementById('filterBoMon').addEventListener('change', () => loadAvailableHocPhans(1));

    // Confirm selection in popup con
    document.getElementById('btnConfirmSelection').addEventListener('click', function() {
        if (selectedHocPhansForAdd.length === 0) {
            alert('Vui lòng chọn ít nhất một học phần');
            return;
        }
        
        // Add to temp selections with default loai = 'bat_buoc'
        if (!tempSelectedHocPhans[selectedKhoiId]) {
            tempSelectedHocPhans[selectedKhoiId] = [];
        }
        
        selectedHocPhansForAdd.forEach(hp => {
            tempSelectedHocPhans[selectedKhoiId].push({
                hoc_phan_id: hp.id,
                khoi_id: parseInt(selectedKhoiId),
                loai: 'bat_buoc',
                thu_tu: 0,
                isNew: true
            });
        });
        
        // Reload panel 1
        loadHocPhansByKhoi(selectedKhoiId);
        
        // Close popup con
        selectModal.hide();
        
        // Clear selection
        selectedHocPhansForAdd = [];
    });

    // Save all changes
    document.getElementById('btnSaveAll').addEventListener('click', function() {
        // Collect all temp selections
        const allItems = [];
        for (const [khoiId, hocPhans] of Object.entries(tempSelectedHocPhans)) {
            hocPhans.forEach(hp => {
                allItems.push({
                    hoc_phan_id: hp.hoc_phan_id,
                    khoi_id: parseInt(khoiId),
                    loai: hp.loai,
                    thu_tu: hp.thu_tu
                });
            });
        }
        
        if (allItems.length === 0) {
            alert('Không có thay đổi nào để lưu');
            return;
        }
        
        // Disable button
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Đang lưu...';
        
        // Send to server
        fetch(`/ctdt/${ctdtId}/hoc-phans`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({items: allItems})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Reload page to show updated data
                window.location.reload();
            } else {
                alert('Lỗi:\n' + data.errors.join('\n'));
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-save me-1"></i>Lưu tất cả';
            }
        })
        .catch(error => {
            console.error('[v0] Error saving:', error);
            alert('Có lỗi xảy ra khi lưu');
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-save me-1"></i>Lưu tất cả';
        });
    });

    // Reset when closing main modal
    document.getElementById('addHocPhanModal').addEventListener('hidden.bs.modal', function() {
        tempSelectedHocPhans = {};
        selectedKhoiId = null;
        selectedKhoiTen = '';
        document.getElementById('btnSelectHocPhan').disabled = true;
        document.getElementById('btnSaveAll').disabled = true;
        
        // Remove all active classes
        document.querySelectorAll('.khoi-item').forEach(k => {
            k.classList.remove('border-primary', 'bg-light');
        });
        
        // Reset panel 1
        document.getElementById('hocPhanListPanel').innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="fas fa-arrow-right fa-3x mb-3 opacity-25"></i>
                <p>Chọn một khối kiến thức từ panel bên phải</p>
            </div>`;
    });
});
</script>
@endpush
