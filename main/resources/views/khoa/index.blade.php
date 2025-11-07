@extends('layouts.app')

@section('title', 'Khoa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Khoa</h1>
    <a href="{{ route('khoa.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tạo mới
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mã</th>
                    <th>Tên</th>
                    <th>Bộ môn</th>
                    <th style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($khoas as $khoa)
                <tr>
                    <td><strong>{{ $khoa->ma }}</strong></td>
                    <td>{{ $khoa->ten }}</td>
                    <td><span class="badge bg-info">{{ $khoa->boMons->count() }}</span></td>
                    <td>
                        <a href="{{ route('khoa.show', $khoa) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('khoa.edit', $khoa) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('khoa.destroy', $khoa) }}" style="display: inline;" onsubmit="return confirm('Xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">Chưa có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="card-footer bg-light">
        {{ $khoas->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
