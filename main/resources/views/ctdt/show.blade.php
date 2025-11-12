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
            @php
                $statusLabels = [
                    'draft' => 'Bản nháp',
                    'can_chinh_sua' => 'Cần chỉnh sửa',
                    'cho_phe_duyet' => 'Chờ phê duyệt',
                    'da_phe_duyet' => 'Đã phê duyệt',
                    'published' => 'Đã công bố',
                    'archived' => 'Lưu trữ'
                ];
                $statusColors = [
                    'draft' => 'secondary',
                    'can_chinh_sua' => 'warning',
                    'cho_phe_duyet' => 'info',
                    'da_phe_duyet' => 'success',
                    'published' => 'primary',
                    'archived' => 'dark'
                ];
            @endphp
            <span class="badge bg-{{ $statusColors[$ctdt->trang_thai] ?? 'secondary' }} mb-2 d-block fs-6">
                {{ $statusLabels[$ctdt->trang_thai] ?? ucfirst($ctdt->trang_thai) }}
            </span>
            
            @if ($ctdt->trang_thai === 'can_chinh_sua' && $ctdt->ly_do_tra_ve)
            <div class="alert alert-warning mt-2">
                <strong><i class="bi bi-exclamation-triangle"></i> Lý do yêu cầu chỉnh sửa:</strong>
                <p class="mb-0 mt-1">{{ $ctdt->ly_do_tra_ve }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

@if (in_array($ctdt->trang_thai, ['cho_phe_duyet', 'da_phe_duyet']))
<div class="alert alert-info">
    <i class="bi bi-lock-fill"></i>
    @if ($ctdt->trang_thai === 'cho_phe_duyet')
        <strong>CTĐT đã bị khóa</strong> - Đang chờ phê duyệt. Bạn không thể chỉnh sửa cho đến khi được phê duyệt hoặc yêu cầu chỉnh sửa.
    @else
        <strong>CTĐT đã bị khóa vĩnh viễn</strong> - Đã được phê duyệt và ban hành chính thức. Không thể chỉnh sửa.
    @endif
</div>
@endif

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Thông tin chung</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Bậc học</small>
                        <p>{{ $ctdt->bacHoc->ten ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Loại hình đào tạo</small>
                        <p>{{ $ctdt->loaiHinhDaoTao->ten ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Hệ đào tạo</small>
                        <p>{{ $ctdt->heDaoTao?->ten ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Khoa</small>
                        <p>{{ $ctdt->khoa->ten ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Ngành</small>
                        <p>{{ $ctdt->nganh->ten ?? 'N/A' }} ({{ $ctdt->nganh->ma ?? '' }})</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Chuyên ngành</small>
                        <p>{{ $ctdt->chuyenNganh?->ten ?? 'Đại trà' }}</p>
                    </div>
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
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-book-open me-2"></i>Khung chương trình</h6>
                @can('update', $ctdt)
                <a href="{{ route('ctdt.manage-hoc-phan', $ctdt->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-gear"></i> Quản lý học phần
                </a>
                @endcan
            </div>
            <div class="card-body p-0">
                @php
                    $stt = 1;
                    
                    // Filter out courses with null khoi_id first
                    $validHocPhans = $ctdt->ctdtHocPhans->filter(function($item) {
                        return $item->khoi_id !== null && $item->khoi !== null;
                    });
                    
                    // Group by khoi_id
                    $khoiGroups = $validHocPhans->groupBy('khoi_id');
                    
                    // Sort groups by khoi.ma
                    $sortedKhoiGroups = $khoiGroups->sortBy(function($group, $khoiId) {
                        $khoi = $group->first()->khoi;
                        return $khoi ? $khoi->ma : 'ZZZ';
                    });
                    
                    // Track orphan courses (those without proper khoi)
                    $orphanHocPhans = $ctdt->ctdtHocPhans->filter(function($item) {
                        return $item->khoi_id === null || $item->khoi === null;
                    });
                    
                    $constraintsByHocPhan = [];
                    foreach ($ctdt->ctdtRangBuocs as $rangBuoc) {
                        if (!isset($constraintsByHocPhan[$rangBuoc->hoc_phan_id])) {
                            $constraintsByHocPhan[$rangBuoc->hoc_phan_id] = [];
                        }
                        $constraintsByHocPhan[$rangBuoc->hoc_phan_id][] = $rangBuoc;
                    }
                    
                    $kieuLabels = [
                        'tien_quyet' => 'Tiên quyết',
                        'hoc_truoc' => 'Học trước',
                        'song_hanh' => 'Song hành',
                        'khong_hoc_cung' => 'Không học cùng'
                    ];
                    
                    $totalCourses = $ctdt->ctdtHocPhans->count();
                    $totalCredits = $ctdt->ctdtHocPhans->sum(function($item) {
                        return $item->hocPhan->so_tinchi;
                    });
                    
                    if (env('APP_DEBUG')) {
                        error_log("Total courses: " . $ctdt->ctdtHocPhans->count());
                        error_log("Valid courses: " . $validHocPhans->count());
                        error_log("Orphan courses: " . $orphanHocPhans->count());
                        error_log("Knowledge blocks: " . $sortedKhoiGroups->count());
                        
                        foreach ($sortedKhoiGroups as $khoiId => $hocPhans) {
                            $khoi = $hocPhans->first()->khoi;
                            error_log("Rendering Khoi: " . ($khoi ? $khoi->ma : 'NULL') . " (ID: $khoiId) with " . $hocPhans->count() . " courses");
                        }
                    }
                @endphp
                
                <div class="table-responsive">
                    <table class="table table-bordered curriculum-table mb-0">
                        <thead>
                            <tr class="table-warning">
                                <th class="text-center" style="width: 100px;">Mã khối</th>
                                <th style="width: 250px;">Tên khối</th>
                                <th class="text-center" style="width: 60px;">STT</th>
                                <th class="text-center" style="width: 120px;">Mã học phần</th>
                                <th>Tên học phần</th>
                                <th class="text-center" style="width: 80px;">Số TC</th>
                                <th class="text-center" style="width: 100px;">Loại</th>
                                <th style="width: 250px;">Điều kiện ràng buộc</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sortedKhoiGroups as $khoiId => $hocPhans)
                                @php
                                    $khoi = $hocPhans->first()->khoi;
                                    // Sort courses by thu_tu within each block
                                    $sortedHocPhans = $hocPhans->sortBy('thu_tu')->values();
                                    $totalInKhoi = $sortedHocPhans->count();
                                    
                                    if (env('APP_DEBUG')) {
                                        error_log("Rendering Khoi: " . $khoi->ma . " (ID: $khoiId) with $totalInKhoi courses");
                                    }
                                @endphp
                                
                                @foreach($sortedHocPhans as $index => $ctdtHocPhan)
                                    @php
                                        $hocPhan = $ctdtHocPhan->hocPhan;
                                        $constraints = $constraintsByHocPhan[$hocPhan->id] ?? [];
                                    @endphp
                                    <tr>
                                        {{-- Only render khoi cells on first row of each block --}}
                                        @if($index === 0)
                                        <td class="text-center align-middle fw-bold bg-light" rowspan="{{ $totalInKhoi }}">
                                            {{ $khoi->ma }}
                                        </td>
                                        <td class="align-middle fw-semibold bg-light" rowspan="{{ $totalInKhoi }}">
                                            {{ $khoi->ten }}
                                        </td>
                                        @endif
                                        
                                        <td class="text-center">{{ $stt++ }}</td>
                                        
                                        <td class="text-center">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                {{ $hocPhan->ma_hp }}
                                            </span>
                                        </td>
                                        
                                        <td>
                                            <a href="#" class="text-decoration-none text-primary">
                                                {{ $hocPhan->ten_hp }}
                                            </a>
                                        </td>
                                        
                                        <td class="text-center">
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                {{ $hocPhan->so_tinchi }}
                                            </span>
                                        </td>
                                        
                                        <td class="text-center">
                                            @if($ctdtHocPhan->loai === 'bat_buoc')
                                            <span class="badge bg-danger">Bắt buộc</span>
                                            @else
                                            <span class="badge bg-warning">Tự chọn</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if(!empty($constraints))
                                                <div class="small">
                                                    @foreach($constraints as $constraint)
                                                        @php
                                                            $relatedHocPhan = $constraint->lienQuanHocPhan;
                                                            $kieuLabel = $kieuLabels[$constraint->kieu] ?? ucfirst($constraint->kieu);
                                                        @endphp
                                                        <div class="mb-1">
                                                            <i class="fas fa-arrow-right text-muted me-1"></i>
                                                            <span class="fw-semibold text-primary">{{ $kieuLabel }}</span>
                                                            ({{ $relatedHocPhan->ten_hp }})
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                            <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 opacity-25"></i>
                                        <p class="mb-0">Chưa có học phần nào được gán vào khối kiến thức</p>
                                    </td>
                                </tr>
                            @endforelse
                            
                            {{-- Show warning for orphan courses --}}
                            @if($orphanHocPhans->isNotEmpty())
                                <tr>
                                    <td colspan="8" class="text-center text-danger bg-danger bg-opacity-10 py-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Cảnh báo:</strong> Có {{ $orphanHocPhans->count() }} học phần chưa được gán vào khối kiến thức hợp lệ. 
                                        Vui lòng kiểm tra lại dữ liệu.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="5" class="text-end">Tổng cộng:</td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $totalCredits }} TC</span>
                                </td>
                                <td colspan="2" class="text-center">
                                    {{ $totalCourses }} học phần | {{ $sortedKhoiGroups->count() }} khối kiến thức
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <style>
                    .curriculum-table {
                        font-size: 0.9rem;
                    }
                    
                    .curriculum-table thead th {
                        font-weight: 600;
                        font-size: 0.85rem;
                        vertical-align: middle;
                        background-color: #fff3cd;
                        border-color: #dee2e6;
                    }
                    
                    .curriculum-table tbody tr {
                        transition: background-color 0.15s ease;
                    }
                    
                    .curriculum-table tbody tr:hover {
                        background-color: #f8f9fa;
                    }
                    
                    .curriculum-table tbody td {
                        vertical-align: middle;
                        border-color: #dee2e6;
                    }
                    
                    .curriculum-table tbody td.bg-light {
                        background-color: #f8f9fa !important;
                    }
                    
                    .curriculum-table tfoot td {
                        border-top: 2px solid #dee2e6;
                    }
                    
                    .curriculum-table a:hover {
                        text-decoration: underline !important;
                    }
                </style>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Hành động</h6>
            </div>
            <div class="card-body d-grid gap-2">
                @if (in_array($ctdt->trang_thai, ['draft', 'can_chinh_sua']))
                    @can('update', $ctdt)
                    <a href="{{ route('ctdt.edit', $ctdt) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>
                    <a href="{{ route('ctdt.manage-hoc-phan', $ctdt->id) }}" class="btn btn-primary">
                        <i class="bi bi-gear"></i> Quản lý học phần
                    </a>
                    <a href="{{ route('ctdt.rang-buoc', $ctdt->id) }}" class="btn btn-info">
                        <i class="bi bi-diagram-3"></i> Ràng buộc học phần
                    </a>
                    @endcan
                @endif
                
                @if (in_array($ctdt->trang_thai, ['draft', 'can_chinh_sua']))
                    @can('update', $ctdt)
                    <button type="button" class="btn btn-success" onclick="confirmSendForApproval()">
                        <i class="bi bi-send"></i> Gửi phê duyệt
                    </button>
                    @endcan
                @endif
                
                @if ($ctdt->trang_thai === 'cho_phe_duyet' && Auth::user()->role === 'admin')
                    <button type="button" class="btn btn-success" onclick="confirmApprove()">
                        <i class="bi bi-check-circle"></i> Phê duyệt
                    </button>
                    <button type="button" class="btn btn-warning" onclick="showRejectModal()">
                        <i class="bi bi-x-circle"></i> Yêu cầu chỉnh sửa
                    </button>
                @endif
                
                <a href="{{ route('ctdt.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
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
                
                @if ($ctdt->trang_thai === 'da_phe_duyet' && $ctdt->approved_by)
                <hr>
                <small class="text-muted">Phê duyệt bởi</small>
                <p>{{ $ctdt->nguoiPheDuyet?->name ?? 'N/A' }}</p>
                
                <small class="text-muted">Ngày phê duyệt</small>
                <p>{{ $ctdt->approved_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sendApprovalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận gửi phê duyệt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn gửi phê duyệt CTĐT <strong>{{ $ctdt->ten }}</strong> không?</p>
                <p class="text-warning"><i class="bi bi-exclamation-triangle"></i> Sau khi gửi, bạn sẽ không thể chỉnh sửa cho đến khi được phê duyệt hoặc yêu cầu chỉnh sửa.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form method="POST" action="{{ route('ctdt.send-for-approval', $ctdt) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Đồng ý</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận phê duyệt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn phê duyệt CTĐT <strong>{{ $ctdt->ten }}</strong> không?</p>
                <p class="text-danger"><i class="bi bi-exclamation-triangle"></i> CTĐT sẽ được ban hành và <strong>không thể chỉnh sửa</strong>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form method="POST" action="{{ route('ctdt-approval.approve', $ctdt) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Đồng ý</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('ctdt-approval.reject', $ctdt) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Yêu cầu chỉnh sửa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Vui lòng nhập nội dung cần chỉnh sửa cho CTĐT <strong>{{ $ctdt->ten }}</strong>:</p>
                    <div class="mb-3">
                        <label for="ly_do_tra_ve" class="form-label">Nội dung yêu cầu chỉnh sửa <span class="text-danger">*</span></label>
                        <textarea name="ly_do_tra_ve" id="ly_do_tra_ve" class="form-control" rows="4" required></textarea>
                        <div class="invalid-feedback">Vui lòng nhập nội dung yêu cầu chỉnh sửa.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Gửi lại cho Khoa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmSendForApproval() {
    new bootstrap.Modal(document.getElementById('sendApprovalModal')).show();
}

function confirmApprove() {
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}

function showRejectModal() {
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
@endsection
