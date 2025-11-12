@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Quản lý Người dùng</h1>
        @can('create', App\Models\User::class)
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Thêm người dùng
        </a>
        @endcan
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Khoa</th>
                            {{-- Add status column --}}
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                <span class="badge bg-danger">Admin</span>
                                @elseif($user->role === 'khoa')
                                <span class="badge bg-primary">Khoa</span>
                                @elseif($user->role === 'giang_vien')
                                <span class="badge bg-success">Giảng viên</span>
                                @else
                                <span class="badge bg-info">Sinh viên</span>
                                @endif
                            </td>
                            <td>{{ $user->khoa?->ten ?? '-' }}</td>
                            {{-- Add status badge with color --}}
                            <td>
                                <span class="badge bg-{{ $user->active ? 'success' : 'secondary' }}" id="status-badge-{{ $user->id }}">
                                    <i class="fas fa-{{ $user->active ? 'check-circle' : 'lock' }}"></i>
                                    {{ $user->active ? 'Hoạt động' : 'Đã khóa' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    {{-- Add toggle button for lock/unlock --}}
                                    @can('update', $user)
                                    @if($user->id !== auth()->id())
                                    <button type="button" 
                                        class="btn btn-sm btn-{{ $user->active ? 'secondary' : 'success' }}" 
                                        id="toggle-btn-{{ $user->id }}"
                                        onclick="toggleUserActive({{ $user->id }}, {{ $user->active ? 'true' : 'false' }})"
                                        title="{{ $user->active ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}">
                                        <i class="fas fa-{{ $user->active ? 'lock' : 'unlock' }}"></i>
                                    </button>
                                    @endif
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('delete', $user)
                                    @if($user->id !== auth()->id())
                                    <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="if(confirm('Bạn có chắc muốn xóa người dùng này?')) document.getElementById('delete-form-{{ $user->id }}').submit()">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                    @endcan
                                </div>
                                
                                @can('delete', $user)
                                <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Không có người dùng nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Add JavaScript for toggle functionality --}}
<script>
function toggleUserActive(userId, currentActive) {
    const action = currentActive ? 'khóa' : 'mở khóa';
    
    if (!confirm(`Bạn có chắc muốn ${action} tài khoản này?`)) {
        return;
    }
    
    const button = document.getElementById(`toggle-btn-${userId}`);
    button.disabled = true;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    if (!csrfToken) {
        alert('Lỗi: Không tìm thấy CSRF token');
        button.disabled = false;
        return;
    }
    
    fetch(`/users/${userId}/toggle-active`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update status badge
            const badge = document.getElementById(`status-badge-${userId}`);
            badge.className = `badge bg-${data.active ? 'success' : 'secondary'}`;
            badge.innerHTML = `<i class="fas fa-${data.active ? 'check-circle' : 'lock'}"></i> ${data.active ? 'Hoạt động' : 'Đã khóa'}`;
            
            // Update button
            button.className = `btn btn-sm btn-${data.active ? 'secondary' : 'success'}`;
            button.innerHTML = `<i class="fas fa-${data.active ? 'lock' : 'unlock'}"></i>`;
            button.title = data.active ? 'Khóa tài khoản' : 'Mở khóa tài khoản';
            
            // Show success message
            alert(data.message);
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật trạng thái');
    })
    .finally(() => {
        button.disabled = false;
    });
}
</script>
@endsection
