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
                <h6 class="mb-0">Học phần</h6>
                @can('update', $ctdt)
                <a href="{{ route('ctdt.manage-hoc-phan', $ctdt->id) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-gear"></i> Quản lý học phần
                </a>
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ctdt->ctdtHocPhans->sortBy('thu_tu') as $ctdtHocPhan)
                        <tr>
                            <td>{{ $ctdtHocPhan->hocPhan->ma_hp }}</td>
                            <td>{{ $ctdtHocPhan->hocPhan->ten_hp }}</td>
                            <td>{{ $ctdtHocPhan->hocPhan->so_tinchi }}</td>
                            <td>{{ $ctdtHocPhan->khoi->ten ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $ctdtHocPhan->loai === 'bat_buoc' ? 'danger' : 'warning' }}">
                                    {{ $ctdtHocPhan->loai === 'bat_buoc' ? 'Bắt buộc' : 'Tự chọn' }}
                                </span>
                            </td>
                            <td>{{ $ctdtHocPhan->thu_tu }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                Chưa có học phần nào
                                @can('update', $ctdt)
                                <br>
                                <a href="{{ route('ctdt.manage-hoc-phan', $ctdt->id) }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Thêm học phần
                                </a>
                                @endcan
                            </td>
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
