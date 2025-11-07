@extends('layouts.app')

@section('title', 'CTĐT công khai')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="h3 d-inline-block">Danh sách CTĐT công khai</h1>
    </div>
</div>

<div class="card">
    <div class="card-header bg-light">
        <h6 class="mb-0">CTĐT đã công bố</h6>
    </div>

    <div class="table-responsive">
        <table class="table table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:60%">Tên CTĐT</th>
                    <th style="width:20%">Trạng thái</th>
                    <th style="width:20%" class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $it)
                    <tr>
                        <td>{{ $it->ten ?? ('CTĐT #' . $it->id) }}</td>
                        <td>
                            <span class="badge bg-success">Published</span>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-primary"
                               href="{{ route('ctdt-public.show', $it->id) }}">
                                Xem
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-3">
                            Chưa có CTĐT nào được công bố.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($items->hasPages())
        <div class="card-footer">
            {{ $items->links() }}
        </div>
    @endif
</div>
@endsection
