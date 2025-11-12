@extends('layouts.app')

@section('title', 'Chương trình Đào tạo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Chương trình Đào tạo</h1>
    @can('create', App\Models\ChuongTrinhDaoTao::class)
    {{-- Update button to dropdown with 2 options --}}
    <div class="btn-group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-plus"></i> Thêm mới
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a class="dropdown-item" href="{{ route('ctdt.create', ['mode' => 'new']) }}">
                    <i class="fas fa-plus-circle"></i> Tạo mới hoàn toàn
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('ctdt.create', ['mode' => 'copy']) }}">
                    <i class="fas fa-copy"></i> Sao chép CTĐT
                </a>
            </li>
        </ul>
    </div>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mã CTĐT</th>
                    <th>Tên</th>
                    <th>Khoa</th>
                    <th>Ngành</th>
                    <th>Khóa</th>
                    <th>Trạng thái</th>
                    <th>Hiệu lực từ</th>
                    <th style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ctdts as $ctdt)
                <tr>
                    <td><strong>{{ $ctdt->ma_ctdt }}</strong></td>
                    <td>{{ $ctdt->ten }}</td>
                    <td>{{ $ctdt->khoa?->ten ?? 'N/A' }}</td>
                    <td>{{ $ctdt->nganh?->ten ?? 'N/A' }}</td>
                    <td>
                        {{-- Display khoa hoc --}}
                        <span class="badge bg-secondary">{{ $ctdt->khoaHoc?->ma ?? 'N/A' }}</span>
                    </td>
                    <td>
                        {{-- Updated status map với status mới và màu sắc phân biệt --}}
                        @php
                            $statusMap = [
                                'draft' => ['class' => 'secondary', 'text' => 'Bản nháp', 'icon' => 'fa-file-alt'],
                                'can_chinh_sua' => ['class' => 'warning text-dark', 'text' => 'Cần chỉnh sửa', 'icon' => 'fa-edit'],
                                'cho_phe_duyet' => ['class' => 'info', 'text' => 'Chờ phê duyệt', 'icon' => 'fa-clock'],
                                'da_phe_duyet' => ['class' => 'success', 'text' => 'Đã phê duyệt', 'icon' => 'fa-check-circle'],
                                'published' => ['class' => 'primary', 'text' => 'Đã công bố', 'icon' => 'fa-globe'],
                                'archived' => ['class' => 'dark', 'text' => 'Lưu trữ', 'icon' => 'fa-archive'],
                            ];
                            $status = $statusMap[$ctdt->trang_thai] ?? ['class' => 'secondary', 'text' => $ctdt->trang_thai, 'icon' => 'fa-question'];
                        @endphp
                        <span class="badge bg-{{ $status['class'] }}">
                            <i class="fas {{ $status['icon'] }}"></i> {{ $status['text'] }}
                        </span>
                    </td>
                    <td>{{ $ctdt->hieu_luc_tu?->format('d/m/Y') ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('ctdt.show', $ctdt) }}" class="btn btn-sm btn-info" title="Xem">
                            <i class="fas fa-eye"></i>
                        </a>
                        @can('update', $ctdt)
                        <a href="{{ route('ctdt.edit', $ctdt) }}" class="btn btn-sm btn-warning" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        @can('delete', $ctdt)
                        <form method="POST" action="{{ route('ctdt.destroy', $ctdt) }}" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa CTĐT này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                        Chưa có chương trình đào tạo nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($ctdts->hasPages())
    <div class="card-footer bg-light">
        {{ $ctdts->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
