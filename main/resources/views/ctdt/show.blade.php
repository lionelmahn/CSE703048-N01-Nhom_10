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
                        <p>{{ $ctdt->chuyenNganh?->ten ?? '' }}</p>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm học phần</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('ctdt-item.add-hoc-phan', $ctdt) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="hoc_phan_id" class="form-label">Học phần <span class="text-danger">*</span></label>
                        <select class="form-select" id="hoc_phan_id" name="hoc_phan_id" required>
                            <option value="">-- Chọn học phần --</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="loai" class="form-label">Loại <span class="text-danger">*</span></label>
                        <select class="form-select" id="loai" name="loai" required>
                            <option value="bat_buoc">Bắt buộc</option>
                            <option value="tu_chon">Tự chọn</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@endsection
