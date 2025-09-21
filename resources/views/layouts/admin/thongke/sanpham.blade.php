{{-- resources/views/layouts/admin/thongke/sanpham.blade.php --}}
@extends('layouts.admin.index')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:cart-large-2-bold-duotone" class="fs-32 text-success avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Tổng sản phẩm</p>
                            <h3 class="text-dark mb-0">{{ $totalProducts ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:star-bold-duotone" class="fs-32 text-warning avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Sản phẩm bán chạy nhất</p>
                            <h3 class="text-dark mb-0">{{ $bestSellerName ?? '-' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:box-minimalistic-broken" class="fs-32 text-danger avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Sản phẩm sắp hết hàng</p>
                            <h3 class="text-dark mb-0">
                                <a href="{{ route('admin.products.lowStock') }}" style="color: inherit; text-decoration: underline; cursor: pointer;" title="Xem chi tiết">
                                    {{ $lowStockCount ?? 0 }}
                                </a>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:box-minimalistic-broken" class="fs-32 text-secondary avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Sản phẩm hết hàng</p>
                            <h3 class="text-dark mb-0">{{ $outOfStockCount ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title mb-0">Top 5 sản phẩm bán chạy</h5>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="barChartFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-menu"></i> <span id="barChartFilterLabel">
                                    @if($barFilter === 'day') Theo ngày
                                    @elseif($barFilter === 'month') Theo tháng
                                    @else Theo năm @endif
                                </span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="barChartFilter">
                                <li><a class="dropdown-item bar-filter-option" href="#" data-filter="day">Theo ngày</a></li>
                                <li><a class="dropdown-item bar-filter-option" href="#" data-filter="month">Theo tháng</a></li>
                                <li><a class="dropdown-item bar-filter-option" href="#" data-filter="year">Theo năm</a></li>
                            </ul>
                        </div>
                    </div>
                    <div id="bar-chart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tỷ lệ sản phẩm còn hàng / hết hàng</h5>
                    <div id="pie-chart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Dropdown filter cho biểu đồ cột
    document.querySelectorAll('.bar-filter-option').forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            var filter = this.getAttribute('data-filter');
            var label = this.textContent;
            document.getElementById('barChartFilterLabel').textContent = label;
            // Reload lại trang với tham số filter (hoặc có thể dùng Ajax nếu muốn)
            var url = new URL(window.location.href);
            url.searchParams.set('bar_filter', filter);
            window.location.href = url.toString();
        });
    });

    // Biểu đồ cột - Top 5 sản phẩm bán chạy
    var barOptions = {
        chart: {
            type: 'bar',
            height: 350
        },
        series: [{
            name: 'Số lượng bán',
            data: @json($barData)
        }],
        xaxis: {
            categories: @json($barLabels)
        },
        colors: ['#1e84c4'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '50%'
            }
        }
    };
    var barChart = new ApexCharts(document.querySelector("#bar-chart"), barOptions);
    barChart.render();

    // Biểu đồ tròn - Tỷ lệ sản phẩm còn hàng/hết hàng
    var pieOptions = {
        chart: {
            type: 'pie',
            height: 350
        },
        series: [{{ $inStock }}, {{ $outStock }}],
        labels: ['Còn hàng', 'Hết hàng'],
        colors: ['#28a745', '#dc3545']
    };
    var pieChart = new ApexCharts(document.querySelector("#pie-chart"), pieOptions);
    pieChart.render();
</script>
@endsection 