@extends('index.admindashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Chi tiết Flash Sale: {{ $flashSale->name }}</h3>
                    <div class="card-tools">
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
                                            <th width="9%" class="text-center">Trạng thái</th>
                                            <th width="9%" class="text-center">Tiết kiệm</th>
                                            <th width="8%" class="text-center">Số lượng</th>
                                            <th width="7%" class="text-center">Đã bán</th>
                                            <th width="10%" class="text-center">Còn lại</th>
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
                                                <input type="hidden" name="variant_id" value="{{ $flashSaleProduct->product_variant_id }}">
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
                                                    {{ number_format($flashSaleProduct->sale_price) }}₫
                                                </strong>
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
                                                <span class="badge bg-warning">{{ $flashSaleProduct->sold_quantity }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success">{{ $flashSaleProduct->remaining_stock }}</span>
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

// Tự động làm mới số liệu định kỳ khi đang diễn ra (15s)
let autoRefreshInterval = null;
function startAutoRefresh() {
    if (autoRefreshInterval) return;
    autoRefreshInterval = setInterval(() => {
        // Reload nhanh để cập nhật Remaining/Sold từ đơn hàng mới
        window.location.reload();
    }, 15000);
}

document.addEventListener('DOMContentLoaded', startAutoRefresh);

// Poll live stats without reloading and update DOM
async function fetchStats() {
    try {
        const res = await fetch('{{ route('admin.flash-sales.api.stats', $flashSale->id) }}', { headers: { 'Accept': 'application/json' } });
        if (!res.ok) return;
        const data = await res.json();
        if (!data || !data.success) return;

        // Update top summary boxes
        const totalQtyEl = document.querySelector('.info-box .info-box-text:contains("Tổng số lượng")');
        // Safer: target by order using NodeLists
        const infos = Array.from(document.querySelectorAll('.row.mb-4 .info-box'));
        if (infos[1]) {
            const num = infos[1].querySelector('.info-box-number');
            if (num) num.textContent = new Intl.NumberFormat('vi-VN').format(data.summary.sale_quantity);
        }
        if (infos[3]) {
            const num = infos[3].querySelector('.info-box-number');
            if (num) num.textContent = new Intl.NumberFormat('vi-VN').format(data.summary.remaining);
        }
        if (infos[2]) {
            const num = infos[2].querySelector('.info-box-number');
            if (num) num.textContent = new Intl.NumberFormat('vi-VN').format(data.summary.sold);
        }

        // Update table rows: sold, remaining, progress
        const tbody = document.querySelector('table tbody');
        if (!tbody) return;
        const rows = Array.from(tbody.querySelectorAll('tr'));
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length < 11) return; // header or empty state
            // Variant info cell has product name and color-capacity, but we need to map by order
            // We can't easily store variant_id in DOM, so add a data attribute in markup below via small tweak
        });
    } catch (_) {}
}

// Attach data-variant-id to each row for quick updates
(function tagRows() {
    const tbody = document.querySelector('table tbody');
    if (!tbody) return;
    const rows = Array.from(tbody.querySelectorAll('tr'));
    rows.forEach(row => {
        const variantIdCell = row.querySelector('input[type="hidden"][name="variant_id"]');
        if (variantIdCell) {
            row.dataset.variantId = variantIdCell.value;
        }
    });
})();

// Re-render numbers for rows using fetched stats
function applyRowStats(items) {
    const map = new Map(items.map(it => [String(it.variant_id), it]));
    const tbody = document.querySelector('table tbody');
    if (!tbody) return;
    const rows = Array.from(tbody.querySelectorAll('tr'));
    rows.forEach(row => {
        const variantId = row.dataset.variantId;
        if (!variantId || !map.has(variantId)) return;
        const it = map.get(variantId);
        const tds = row.querySelectorAll('td');
        // Col indices based on current table: 8: số lượng, 9: đã bán, 10: còn lại, 11: tỷ lệ bán
        // Update "Đã bán"
        if (tds[8]) {
            const badge = tds[8].querySelector('.badge');
            if (badge) badge.textContent = new Intl.NumberFormat('vi-VN').format(it.sold);
        }
        // Update "Còn lại"
        if (tds[9]) {
            const badge = tds[9].querySelector('.badge');
            if (badge) badge.textContent = new Intl.NumberFormat('vi-VN').format(it.remaining_stock);
        }
        // Update progress and color
        if (tds[10]) {
            const bar = tds[10].querySelector('.progress-bar');
            if (bar) {
                const sellRate = it.sale_quantity > 0 ? Math.round((it.sold / it.sale_quantity) * 100) : 0;
                bar.style.width = sellRate + '%';
                bar.textContent = sellRate + '%';
                bar.classList.remove('bg-success', 'bg-warning', 'bg-danger');
                bar.classList.add(sellRate >= 80 ? 'bg-success' : (sellRate >= 50 ? 'bg-warning' : 'bg-danger'));
            }
        }
    });
}

// Poller
async function poll() {
    try {
        const res = await fetch('{{ route('admin.flash-sales.api.stats', $flashSale->id) }}', { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        if (data && data.success) {
            applyRowStats(data.items);
        }
    } catch (_) {}
}

// Start polling every 5s
setInterval(poll, 5000);
poll();
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
