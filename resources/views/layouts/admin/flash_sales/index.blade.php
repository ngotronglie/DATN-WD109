@extends('index.admindashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Danh sách Flash Sales</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.flash-sales.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tạo Flash Sale mới
                        </a>
                        <a href="{{ route('admin.flash-sales.statistics') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-chart-bar"></i> Thống kê
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Tên Flash Sale</th>
                                    <th class="text-center">Thời gian bắt đầu</th>
                                    <th class="text-center">Thời gian kết thúc</th>
                                    <th class="text-center">Số sản phẩm</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Tình trạng</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($flashSales as $flashSale)
                                <tr>
                                    <td class="text-center">{{ $flashSale->id }}</td>
                                    <td>{{ $flashSale->name }}</td>
                                    <td class="text-center">{{ $flashSale->start_time->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">{{ $flashSale->end_time->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $flashSale->flashSaleProducts->count() }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($flashSale->is_active)
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-secondary">Tạm dừng</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($flashSale->isOngoing())
                                            <span class="badge bg-warning text-dark">Đang diễn ra</span>
                                        @elseif($flashSale->isExpired())
                                            <span class="badge bg-danger">Đã kết thúc</span>
                                        @else
                                            <span class="badge bg-primary">Sắp diễn ra</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.flash-sales.show', $flashSale->id) }}"
                                               class="btn btn-info btn-sm me-1" title="Xem chi tiết">
                                                👁️
                                            </a>
                                            
                                            @if(!$flashSale->isOngoing())
                                            <a href="{{ route('admin.flash-sales.edit', $flashSale->id) }}"
                                               class="btn btn-warning btn-sm me-1" title="Chỉnh sửa">
                                                ✏️
                                            </a>
                                            @endif

                                            <button type="button" 
                                                    class="btn {{ $flashSale->is_active ? 'btn-secondary' : 'btn-success' }} btn-sm me-1"
                                                    title="{{ $flashSale->is_active ? 'Tạm dừng' : 'Kích hoạt' }}"
                                                    onclick="toggleStatus({{ $flashSale->id }})">
                                                {{ $flashSale->is_active ? '⏸️' : '▶️' }}
                                            </button>

                                            @if(!$flashSale->isOngoing())
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm" 
                                                    title="Xóa"
                                                    onclick="deleteFlashSale({{ $flashSale->id }})">
                                                🗑️
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Chưa có flash sale nào</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $flashSales->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-group {
    display: flex;
    gap: 5px;
}
</style>
@endsection

@section('scripts')
<script>
function toggleStatus(flashSaleId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ route('admin.flash-sales.toggle-status', '') }}/${flashSaleId}`;
    form.style.display = 'none';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    form.appendChild(csrfToken);
    document.body.appendChild(form);
    form.submit();
}

function deleteFlashSale(flashSaleId) {
    if (confirm('Bạn có chắc chắn muốn xóa flash sale này?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.flash-sales.destroy', '') }}/${flashSaleId}`;
        form.style.display = 'none';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
