@extends('layouts.app')

@section('title', 'Phê duyệt CTĐT')

@section('content')
<div class="mb-4">
    <h1 class="h3">Phê duyệt CTĐT</h1>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mã CTĐT</th>
                    <th>Tên</th>
                    <th>Khoa</th>
                    <th>Tạo bởi</th>
                    <th>Ngày gửi</th>
                    <th style="width: 200px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ctdts as $ctdt)
                <tr>
                    <td><strong>{{ $ctdt->ma_ctdt }}</strong></td>
                    <td>{{ $ctdt->ten }}</td>
                    <td>{{ $ctdt->khoa?->ten ?? 'N/A' }}</td>
                    <td>{{ $ctdt->nguoiTao?->name ?? 'N/A' }}</td>
                    <td>{{ $ctdt->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('ctdt.show', $ctdt) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Xem
                        </a>
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $ctdt->id }}">
                            <i class="fas fa-check"></i> Duyệt
                        </button>
                        {{-- Add rejection button per UC20 Luồng A1 --}}
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $ctdt->id }}">
                            <i class="fas fa-times"></i> Từ chối
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Không có CTĐT chờ duyệt</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="card-footer bg-light">
        {{ $ctdts->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- Approve Modals --}}
@forelse ($ctdts as $ctdt)
<div class="modal fade" id="approveModal{{ $ctdt->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Phê duyệt CTĐT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn phê duyệt <strong>{{ $ctdt->ten }}</strong>?</p>
                {{-- Add warning message per UC20 BR4 --}}
                <p class="text-danger"><i class="bi bi-exclamation-triangle"></i> CTĐT sẽ được ban hành và <strong>không thể chỉnh sửa</strong>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form method="POST" action="{{ route('ctdt-approval.approve', $ctdt) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Đồng ý
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Add Reject Modals per UC20 Luồng A1 --}}
<div class="modal fade" id="rejectModal{{ $ctdt->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('ctdt-approval.reject', $ctdt) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Yêu cầu chỉnh sửa CTĐT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Vui lòng nhập nội dung cần chỉnh sửa cho CTĐT <strong>{{ $ctdt->ten }}</strong>:</p>
                    <div class="mb-3">
                        <label for="ly_do_tra_ve{{ $ctdt->id }}" class="form-label">
                            Nội dung yêu cầu chỉnh sửa <span class="text-danger">*</span>
                        </label>
                        <textarea 
                            name="ly_do_tra_ve" 
                            id="ly_do_tra_ve{{ $ctdt->id }}" 
                            class="form-control" 
                            rows="4" 
                            required
                            minlength="10"
                            placeholder="Nhập lý do yêu cầu chỉnh sửa (tối thiểu 10 ký tự)..."></textarea>
                        <small class="text-muted">BR2: Bắt buộc nhập lý do chỉnh sửa</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-paper-plane"></i> Gửi lại cho Khoa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@empty
@endforelse
@endsection
