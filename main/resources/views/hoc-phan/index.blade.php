@extends('layouts.app')

@section('title', 'Học phần')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Học phần</h1>
    @can('create', App\Models\HocPhan::class)
    <a href="{{ route('hoc-phan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tạo mới
    </a>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mã HP</th>
                    <th>Tên</th>
                    <th>Tín chỉ</th>
                    <th>Khoa</th>
                    <th>Trạng thái</th>
                    <th style="width: 200px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($hocPhans as $hocPhan)
                <tr>
                    <td><strong>{{ $hocPhan->ma_hp }}</strong></td>
                    <td>{{ $hocPhan->ten_hp }}</td>
                    <td>{{ $hocPhan->so_tinchi }}</td>
                    <td>{{ $hocPhan->khoa->ten }}</td>
                    {{-- Updated status badge with better colors --}}
                    <td>
                        <span class="badge status-badge-{{ $hocPhan->id }} @if($hocPhan->active) bg-success @else bg-secondary @endif">
                            <i class="fas @if($hocPhan->active) fa-check-circle @else fa-times-circle @endif"></i>
                            {{ $hocPhan->active ? 'Hoạt động' : 'Ngừng hoạt động' }}
                        </span>
                    </td>
                    {{-- Added button group with toggle active button --}}
                    <td>
                        <div class="btn-group" role="group">
                            @can('update', $hocPhan)
                            <button type="button" 
                                    class="btn btn-sm toggle-active-btn toggle-btn-{{ $hocPhan->id }} @if($hocPhan->active) btn-secondary @else btn-success @endif" 
                                    data-id="{{ $hocPhan->id }}"
                                    data-active="{{ $hocPhan->active ? '1' : '0' }}"
                                    title="{{ $hocPhan->active ? 'Tắt hoạt động' : 'Bật hoạt động' }}">
                                <i class="fas @if($hocPhan->active) fa-toggle-on @else fa-toggle-off @endif"></i>
                            </button>
                            @endcan
                            <a href="{{ route('hoc-phan.show', $hocPhan) }}" class="btn btn-sm btn-info" title="Xem">
                                <i class="fas fa-eye"></i>
                            </a>
                            @can('update', $hocPhan)
                            <a href="{{ route('hoc-phan.edit', $hocPhan) }}" class="btn btn-sm btn-warning" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                            @can('delete', $hocPhan)
                            <button type="button" 
                                    class="btn btn-sm btn-danger" 
                                    onclick="document.getElementById('delete-form-{{ $hocPhan->id }}').submit();"
                                    title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endcan
                        </div>
                        @can('delete', $hocPhan)
                        <form id="delete-form-{{ $hocPhan->id }}" 
                              method="POST" 
                              action="{{ route('hoc-phan.destroy', $hocPhan) }}" 
                              style="display: none;"
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa học phần này?');">
                            @csrf
                            @method('DELETE')
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Chưa có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="card-footer bg-light">
        {{ $hocPhans->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- Add AJAX script for toggle active functionality --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    if (!csrfToken) {
        console.error('[v0] CSRF token meta tag not found in page head');
        return;
    }
    
    document.querySelectorAll('.toggle-active-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const hocPhanId = this.dataset.id;
            const isActive = this.dataset.active === '1';
            const button = this;
            const statusBadge = document.querySelector('.status-badge-' + hocPhanId);
            const toggleBtn = document.querySelector('.toggle-btn-' + hocPhanId);
            
            console.log('[v0] Toggle clicked for HocPhan ID:', hocPhanId, 'Current active:', isActive);
            
            // Confirm before toggling
            const action = isActive ? 'tắt hoạt động' : 'bật hoạt động';
            if (!confirm(`Bạn có chắc chắn muốn ${action} học phần này?`)) {
                console.log('[v0] User cancelled toggle');
                return;
            }
            
            // Disable button during request
            button.disabled = true;
            
            const url = `/hoc-phan/${hocPhanId}/toggle-active`;
            console.log('[v0] Sending POST request to:', url);
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('[v0] Response status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.json();
            })
            .then(data => {
                console.log('[v0] Response data:', data);
                
                if (data.success) {
                    // Update button state
                    button.dataset.active = data.active ? '1' : '0';
                    
                    console.log('[v0] Updated active status to:', data.active);
                    
                    // Update button class and icon
                    if (data.active) {
                        toggleBtn.classList.remove('btn-success');
                        toggleBtn.classList.add('btn-secondary');
                        toggleBtn.querySelector('i').classList.remove('fa-toggle-off');
                        toggleBtn.querySelector('i').classList.add('fa-toggle-on');
                        toggleBtn.title = 'Tắt hoạt động';
                    } else {
                        toggleBtn.classList.remove('btn-secondary');
                        toggleBtn.classList.add('btn-success');
                        toggleBtn.querySelector('i').classList.remove('fa-toggle-on');
                        toggleBtn.querySelector('i').classList.add('fa-toggle-off');
                        toggleBtn.title = 'Bật hoạt động';
                    }
                    
                    // Update status badge
                    if (data.active) {
                        statusBadge.classList.remove('bg-secondary');
                        statusBadge.classList.add('bg-success');
                        statusBadge.innerHTML = '<i class="fas fa-check-circle"></i> Hoạt động';
                    } else {
                        statusBadge.classList.remove('bg-success');
                        statusBadge.classList.add('bg-secondary');
                        statusBadge.innerHTML = '<i class="fas fa-times-circle"></i> Ngừng hoạt động';
                    }
                    
                    // Show success message
                    alert(data.message);
                } else {
                    console.error('[v0] Toggle failed:', data);
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
                }
            })
            .catch(error => {
                console.error('[v0] Error:', error);
                alert('Có lỗi xảy ra: ' + error.message + '. Vui lòng thử lại.');
            })
            .finally(() => {
                button.disabled = false;
                console.log('[v0] Toggle request completed');
            });
        });
    });
});
</script>
@endsection
