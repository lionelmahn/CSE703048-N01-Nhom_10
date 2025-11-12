@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Quản lý Ngành</h1>
        @can('create', App\Models\Nganh::class)
        <a href="{{ route('nganh.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Thêm ngành mới
        </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    {{-- Added error message display for constraint violations --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Mã ngành</th>
                            <th>Tên ngành</th>
                            <th>Số CTĐT</th>
                            {{-- Added status column --}}
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nganhs as $nganh)
                        <tr>
                            <td><strong>{{ $nganh->ma }}</strong></td>
                            <td>
                                <a href="{{ route('nganh.show', $nganh) }}" class="text-decoration-none">
                                    {{ $nganh->ten }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $nganh->chuongTrinhDaoTaos->count() }}</span>
                            </td>
                            {{-- Display active status badge --}}
                            <td>
                                <span class="badge status-badge-{{ $nganh->id }}" 
                                      style="background-color: {{ $nganh->active ? '#28a745' : '#6c757d' }}">
                                    <i class="fas {{ $nganh->active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $nganh->active ? 'Hoạt động' : 'Ngừng hoạt động' }}
                                </span>
                            </td>
                            <td>
                                {{-- Updated button group with toggle instead of delete --}}
                                <div class="btn-group btn-group-sm" role="group">
                                    {{-- Toggle Active Button --}}
                                    @can('delete', $nganh)
                                    <button type="button" 
                                            class="btn toggle-btn-{{ $nganh->id }} {{ $nganh->active ? 'btn-secondary' : 'btn-success' }}" 
                                            onclick="toggleNganhActive({{ $nganh->id }}, {{ $nganh->active ? 'true' : 'false' }})"
                                            title="{{ $nganh->active ? 'Ngừng hoạt động' : 'Kích hoạt' }}">
                                        <i class="fas {{ $nganh->active ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                    </button>
                                    @endcan
                                    
                                    <a href="{{ route('nganh.show', $nganh) }}" class="btn btn-info" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('update', $nganh)
                                    <a href="{{ route('nganh.edit', $nganh) }}" class="btn btn-warning" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Chưa có ngành nào. <a href="{{ route('nganh.create') }}">Thêm ngành mới</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $nganhs->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Added JavaScript for AJAX toggle functionality --}}
<script>
function toggleNganhActive(nganhId, currentStatus) {
    const action = currentStatus ? 'ngừng hoạt động' : 'kích hoạt';
    const actionTitle = currentStatus ? 'Ngừng hoạt động ngành này' : 'Kích hoạt ngành này';
    
    if (!confirm(`${actionTitle}?\n\nLưu ý: ${currentStatus ? 'Không thể ngừng hoạt động nếu còn CTĐT đang hoạt động.' : 'Ngành sẽ có thể được sử dụng lại.'}`)) {
        return;
    }
    
    const button = document.querySelector(`.toggle-btn-${nganhId}`);
    button.disabled = true;
    
    fetch(`/nganh/${nganhId}/toggle-active`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button appearance
            button.className = `btn btn-sm toggle-btn-${nganhId} ${data.active ? 'btn-secondary' : 'btn-success'}`;
            button.title = data.active ? 'Ngừng hoạt động' : 'Kích hoạt';
            button.innerHTML = `<i class="fas ${data.active ? 'fa-toggle-off' : 'fa-toggle-on'}"></i>`;
            
            // Update status badge
            const badge = document.querySelector(`.status-badge-${nganhId}`);
            badge.style.backgroundColor = data.active ? '#28a745' : '#6c757d';
            badge.innerHTML = `<i class="fas ${data.active ? 'fa-check-circle' : 'fa-times-circle'}"></i> ${data.active ? 'Hoạt động' : 'Ngừng hoạt động'}`;
            
            // Show success message
            showAlert('success', data.message);
        } else {
            showAlert('danger', data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Không thể thực hiện thao tác. Vui lòng thử lại.');
    })
    .finally(() => {
        button.disabled = false;
    });
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.card'));
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endsection
