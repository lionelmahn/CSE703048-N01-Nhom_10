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
                    <td>{{ $ctdt->khoa->ten }}</td>
                    <td>{{ $ctdt->creator->name }}</td>
                    <td>{{ $ctdt->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('ctdt.show', $ctdt) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Xem
                        </a>
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $ctdt->id }}">
                            <i class="fas fa-check"></i> Duyệt
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

<!-- Approve Modals -->
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form method="POST" action="{{ route('ctdt-approval.approve', $ctdt) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Phê duyệt
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@empty
@endforelse
@endsection
