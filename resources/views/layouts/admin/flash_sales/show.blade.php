@extends('index.admindashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Chi tiết Flash Sale: {{ $flashSale->name }}</h3>
                    <div class="card-tools">
                        @if(!$flashSale->isOngoing())
                        <a href="{{ route('admin.flash-sales.edit', $flashSale->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        @endif
                        <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Thông tin cơ bản -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-info-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Thông tin Flash Sale</span>
                                    <span class="info-box-number">{{ $flashSale->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon {{ $flashSale->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="fas {{ $flashSale->is_active ? 'fa-check' : 'fa-pause' }}"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Trạng thái</span>
                                    <span class="info-box-number">
                                        {{ $flashSale->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thời gian -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Thời gian bắt đầu</span>
                                    <span class="info-box-number">{{ $flashSale->start_time->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Thời gian kết thúc</span>
                                    <span class="info-box-number">{{ $flashSale->end_time->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon 
                                    @if($flashSale->isOngoing()) bg-warning
                                    @elseif($flashSale->isExpired()) bg-danger
                                    @else bg-primary
                                    @endif">
                                    <i class="fas fa-flag"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tình trạng</span>
                                    <span class="info-box-number">
                                        @if($flashSale->isOngoing())
                                            Đang diễn ra
                                        @elseif($flashSale->isExpired())
                                            Đã kết thúc
                                        @else
                                            Sắp diễn ra
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thống kê tổng quan -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tổng sản phẩm</span>
                                    <span class="info-box-number">{{ $flashSale->flashSaleProducts->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-cubes"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tổng số lượng</span>
                                    <span class="info-box-number">{{ $flashSale->flashSaleProducts->sum('sale_quantity') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-shopping-cart"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Đã bán</span>
                                    <span class="info-box-number">
                                        {{ $flashSale->flashSaleProducts->sum(function($item) { 
                                            return $item->initial_stock - $item->remaining_stock; 
                                        }) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-warehouse"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Còn lại</span>
                                    <span class="info-box-number">{{ $flashSale->flashSaleProducts->sum('remaining_stock') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách sản phẩm -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Danh sách sản phẩm Flash Sale</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="18%">Sản phẩm</th>
                                            <th width="7%" class="text-center">Ảnh</th>
                                            <th width="9%" class="text-center">Giá gốc</th>
                                            <th width="10%" class="text-center">Giá Flash Sale</th>
                                            <th width="8%" class="text-center">Ưu tiên</th>
                                            <th width="9%" class="text-center">Trạng thái</th>
                                            <th width="9%" class="text-center">Tiết kiệm</th>
                                            <th width="8%" class="text-center">Số lượng</th>
                                            <th width="7%" class="text-center">Đã bán</th>
                                            <th width="7%" class="text-center">Còn lại</th>
                                            <th width="8%" class="text-center">Tỷ lệ bán</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($flashSale->flashSaleProducts as $flashSaleProduct)
                                        <tr>
                                            <td>
                                                <strong>{{ $flashSaleProduct->productVariant->product->name }}</strong><br>
                                                <small class="text-muted">
                                                    {{ $flashSaleProduct->productVariant->color->name ?? 'N/A' }} - 
                                                    {{ $flashSaleProduct->productVariant->capacity->name ?? 'N/A' }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                @if($flashSaleProduct->productVariant->image)
                                                    <img src="{{ asset($flashSaleProduct->productVariant->image) }}" 
                                                     alt="{{ $flashSaleProduct->productVariant->product->name }}" 
                                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px; border-radius: 5px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="text-muted" style="text-decoration: line-through;">
                                                    {{ number_format($flashSaleProduct->original_price) }}đ
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <strong class="text-danger">
                                                    {{ number_format($flashSaleProduct->sale_price) }}đ
                                                </strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $flashSaleProduct->priority ?? 0 }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $statusClass = match($flashSaleProduct->status ?? 'active') {
                                                        'active' => 'bg-success',
                                                        'featured' => 'bg-warning',
                                                        'inactive' => 'bg-secondary',
                                                        default => 'bg-success'
                                                    };
                                                    $statusText = match($flashSaleProduct->status ?? 'active') {
                                                        'active' => 'Hoạt động',
                                                        'featured' => 'Nổi bật',
                                                        'inactive' => 'Tạm dừng',
                                                        default => 'Hoạt động'
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $saving = $flashSaleProduct->original_price - $flashSaleProduct->sale_price;
                                                    $percentage = round(($saving / $flashSaleProduct->original_price) * 100);
                                                @endphp
                                                <span class="badge bg-success">
                                                    {{ number_format($saving) }}đ<br>
                                                    ({{ $percentage }}%)
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $flashSaleProduct->sale_quantity }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $sold = $flashSaleProduct->initial_stock - $flashSaleProduct->remaining_stock;
                                                @endphp
                                                <span class="badge bg-warning">{{ $sold }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $flashSaleProduct->remaining_stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $flashSaleProduct->remaining_stock }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $sellRate = $flashSaleProduct->sale_quantity > 0 
                                                        ? round(($sold / $flashSaleProduct->sale_quantity) * 100) 
                                                        : 0;
                                                @endphp
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar 
                                                        @if($sellRate >= 80) bg-success
                                                        @elseif($sellRate >= 50) bg-warning
                                                        @else bg-danger
                                                        @endif" 
                                                         role="progressbar" 
                                                         style="width: {{ $sellRate }}%">
                                                        {{ $sellRate }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Chưa có sản phẩm nào trong flash sale này</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Countdown timer nếu flash sale đang hoạt động -->
                    @if($flashSale->isOngoing() && $flashSale->is_active)
                    <div class="card mt-4">
                        <div class="card-header bg-warning">
                            <h4 class="card-title text-dark">
                                <i class="fas fa-clock"></i> Flash Sale đang diễn ra
                            </h4>
                        </div>
                        <div class="card-body text-center">
                            <div id="countdown" class="h3 text-danger"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if($flashSale->isOngoing() && $flashSale->is_active)
<script>
// Countdown timer
function updateCountdown() {
    const endTime = new Date('{{ $flashSale->end_time->format('Y-m-d H:i:s') }}').getTime();
    const now = new Date().getTime();
    const distance = endTime - now;

    if (distance > 0) {
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById('countdown').innerHTML = 
            `<i class="fas fa-fire"></i> Còn lại: ${days}d ${hours}h ${minutes}m ${seconds}s`;
    } else {
        document.getElementById('countdown').innerHTML = '<i class="fas fa-times-circle"></i> Flash Sale đã kết thúc!';
        clearInterval(countdownInterval);
        
        // Reload trang sau 3 giây
        setTimeout(() => {
            location.reload();
        }, 3000);
    }
}

// Cập nhật mỗi giây
const countdownInterval = setInterval(updateCountdown, 1000);
updateCountdown(); // Chạy ngay lập tức
</script>
@endif

<style>
.info-box {
    margin-bottom: 15px;
}
.progress {
    border-radius: 10px;
}
.table th, .table td {
    vertical-align: middle;
}
#countdown {
    font-family: 'Courier New', monospace;
    font-weight: bold;
}
</style>
@endsection
