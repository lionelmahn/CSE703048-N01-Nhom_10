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
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    @can('update', $user)
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('delete', $user)
                                    <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="if(confirm('Bạn có chắc muốn xóa người dùng này?')) document.getElementById('delete-form-{{ $user->id }}').submit()">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
                            <td colspan="7" class="text-center">Không có người dùng nào</td>
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
@endsection
