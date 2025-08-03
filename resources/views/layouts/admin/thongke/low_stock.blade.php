@extends('layouts.admin.index')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <iconify-icon icon="solar:box-minimalistic-bold-duotone" class="text-warning me-2"></iconify-icon>
                        Danh sách sản phẩm sắp hết hàng
                    </h4>
                    <a href="{{ route('admin.thongke.sanpham') }}" class="btn btn-secondary btn-sm">
                        <iconify-icon icon="solar:arrow-left-bold-duotone" class="me-1"></iconify-icon>
                        Quay lại thống kê
                    </a>
                </div>
                <div class="card-body">
                    @if($lowStockVariants->count() > 0)
                        <div class="alert alert-warning">
                            <iconify-icon icon="solar:info-circle-bold-duotone" class="me-2"></iconify-icon>
                            <strong>Cảnh báo:</strong> Có {{ $lowStockVariants->count() }} biến thể sản phẩm sắp hết hàng (số lượng ≤ 5).
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="25%">Tên sản phẩm</th>
                                        <th width="20%">Biến thể</th>
                                        <th width="15%">Màu sắc</th>
                                        <th width="15%">Dung lượng</th>
                                        <th width="10%">Số lượng còn</th>
                                        <th width="10%">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockVariants as $index => $variant)
                                    <tr class="{{ $variant->quantity <= 2 ? 'table-danger' : 'table-warning' }}">
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $variant->product->name ?? 'N/A' }}</strong>
                                            @if($variant->product)
                                                <br>
                                                <small class="text-muted">
                                                    <a href="{{ route('admin.products.edit', $variant->product->slug) }}" target="_blank">
<iconify-icon icon="solar:pen-bold-duotone" class="me-1"></iconify-icon>
                                                        Chỉnh sửa
                                                    </a>
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($variant->product)
                                                <span class="badge bg-info">{{ $variant->product->category->Name ?? 'N/A' }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($variant->color)
                                                <span class="badge" style="background-color: {{ $variant->color->code ?? '#ccc' }}; color: white;">
                                                    {{ $variant->color->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($variant->capacity)
                                                <span class="badge bg-secondary">{{ $variant->capacity->name }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($variant->quantity <= 2)
                                                <span class="badge bg-danger fs-6">{{ $variant->quantity }}</span>
                                            @elseif($variant->quantity <= 3)
                                                <span class="badge bg-warning fs-6">{{ $variant->quantity }}</span>
                                            @else
                                                <span class="badge bg-info fs-6">{{ $variant->quantity }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($variant->quantity <= 2)
                                                <span class="badge bg-danger">Cực kỳ thấp</span>
                                            @elseif($variant->quantity <= 3)
<span class="badge bg-warning">Thấp</span>
                                            @else
                                                <span class="badge bg-info">Cần bổ sung</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <iconify-icon icon="solar:box-bold-duotone" class="fs-48 text-success mb-3"></iconify-icon>
                            <h5 class="text-success">Tuyệt vời!</h5>
                            <p class="text-muted">Không có sản phẩm nào sắp hết hàng. Tất cả sản phẩm đều có đủ số lượng.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table-danger {
    background-color: rgba(220, 53, 69, 0.1) !important;
}
.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}
</style>
@endsection