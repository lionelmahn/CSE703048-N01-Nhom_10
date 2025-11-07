@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="h3 d-inline-block">Dashboard</h1>
    </div>
</div>

<!-- Cards Row 1 -->
<div class="row mb-4">
    {{-- Changed from hasRole() to role === 'admin' (removed Spatie Permission) --}}
    @if ($user->role === 'admin')
    <div class="col-md-3 mb-3">
        <div class="card border-left-primary h-100 py-2">
            <div class="card-body">
                <div class="text-primary text-uppercase mb-1" style="font-size: 0.8rem; font-weight: 600;">CTĐT Chờ Duyệt</div>
                <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $pendingCount }}</div>
            </div>
        </div>
    </div>
    @else
    <div class="col-md-3 mb-3">
        <div class="card border-left-warning h-100 py-2">
            <div class="card-body">
                <div class="text-warning text-uppercase mb-1" style="font-size: 0.8rem; font-weight: 600;">Bản Nháp Của Tôi</div>
                <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $draftCount }}</div>
            </div>
        </div>
    </div>
    @endif
    
    <div class="col-md-3 mb-3">
        <div class="card border-left-success h-100 py-2">
            <div class="card-body">
                <div class="text-success text-uppercase mb-1" style="font-size: 0.8rem; font-weight: 600;">CTĐT Mới (7 Ngày)</div>
                <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $newCount }}</div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card border-left-danger h-100 py-2">
            <div class="card-body">
                <div class="text-danger text-uppercase mb-1" style="font-size: 0.8rem; font-weight: 600;">CTĐT Sắp Hết Hiệu Lực</div>
                <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $expiringCount }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">CTĐT theo trạng thái</h6>
            </div>
            <div class="card-body" style="position: relative; height: 300px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">CTĐT theo khoa</h6>
            </div>
            <div class="card-body" style="position: relative; height: 300px;">
                <canvas id="khoaChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Hoạt động gần đây</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tên CTĐT</th>
                            <th>Trạng thái</th>
                            <th>Khoa</th>
                            <th>Cập nhật</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Changed from $activities (Spatie) to $recentCtdt --}}
                        @forelse ($recentCtdt as $ctdt)
                        <tr>
                            <td>
                                <a href="{{ route('ctdt.show', $ctdt) }}" class="text-decoration-none">
                                    {{ $ctdt->ten }}
                                </a>
                            </td>
                            <td>
                                @if ($ctdt->trang_thai === 'draft')
                                    <span class="badge bg-secondary">Bản nháp</span>
                                @elseif ($ctdt->trang_thai === 'pending')
                                    <span class="badge bg-warning">Chờ duyệt</span>
                                @elseif ($ctdt->trang_thai === 'approved')
                                    <span class="badge bg-info">Đã duyệt</span>
                                @elseif ($ctdt->trang_thai === 'published')
                                    <span class="badge bg-success">Công bố</span>
                                @else
                                    <span class="badge bg-danger">Lưu trữ</span>
                                @endif
                            </td>
                            <td>{{ $ctdt->khoa->ten ?? 'N/A' }}</td>
                            <td><small class="text-muted">{{ $ctdt->updated_at->diffForHumans() }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Chưa có CTĐT nào</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Draft', 'Pending', 'Approved', 'Published', 'Archived'],
            datasets: [{
                data: [
                    {{ $ctdtByStatus->get('draft', 0) }},
                    {{ $ctdtByStatus->get('pending', 0) }},
                    {{ $ctdtByStatus->get('approved', 0) }},
                    {{ $ctdtByStatus->get('published', 0) }},
                    {{ $ctdtByStatus->get('archived', 0) }}
                ],
                backgroundColor: [
                    '#e2e3e5',
                    '#fff3cd',
                    '#cfe2ff',
                    '#d1e7dd',
                    '#f8d7da'
                ],
                borderColor: [
                    '#dee2e6',
                    '#ffc107',
                    '#0d6efd',
                    '#198754',
                    '#dc3545'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Khoa Chart
    const khoaCtx = document.getElementById('khoaChart').getContext('2d');
    const khoaChart = new Chart(khoaCtx, {
        type: 'bar',
        data: {
            labels: [
                @foreach ($ctdtByKhoa as $item)
                    '{{ $item->khoa->ten }}',
                @endforeach
            ],
            datasets: [{
                label: 'Số CTĐT',
                data: [
                    @foreach ($ctdtByKhoa as $item)
                        {{ $item->count }},
                    @endforeach
                ],
                backgroundColor: '#0d6efd',
                borderColor: '#0d6efd',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
@endsection
