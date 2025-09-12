{{-- resources/views/layouts/admin/thongke/donhang.blade.php --}}
@extends('layouts.admin.index')
@section('content')
<div class="container-fluid">
    <!-- Filter buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.thongke.donhang', ['filter' => 'all']) }}" 
                   class="btn {{ $filter == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">Tất cả</a>
                <a href="{{ route('admin.thongke.donhang', ['filter' => 'today']) }}" 
                   class="btn {{ $filter == 'today' ? 'btn-primary' : 'btn-outline-primary' }}">Hôm nay</a>
                <a href="{{ route('admin.thongke.donhang', ['filter' => 'week']) }}" 
                   class="btn {{ $filter == 'week' ? 'btn-primary' : 'btn-outline-primary' }}">Tuần này</a>
                <a href="{{ route('admin.thongke.donhang', ['filter' => 'month']) }}" 
                   class="btn {{ $filter == 'month' ? 'btn-primary' : 'btn-outline-primary' }}">Tháng này</a>
                <a href="{{ route('admin.thongke.donhang', ['filter' => 'year']) }}" 
                   class="btn {{ $filter == 'year' ? 'btn-primary' : 'btn-outline-primary' }}">Năm nay</a>
            </div>
        </div>
    </div>

    <!-- Revenue Overview Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:dollar-minimalistic-bold-duotone" class="fs-32 text-success avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Tổng doanh thu</p>
                            <h3 class="text-dark mb-0">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}₫</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:cart-large-2-bold-duotone" class="fs-32 text-primary avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Tổng đơn hàng</p>
                            <h3 class="text-dark mb-0">{{ $totalOrders ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:chart-2-bold-duotone" class="fs-32 text-info avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Giá trị đơn TB</p>
                            <h3 class="text-dark mb-0">{{ number_format($avgOrderValue ?? 0, 0, ',', '.') }}₫</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:box-bold-duotone" class="fs-32 text-warning avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Sản phẩm đã bán</p>
                            <h3 class="text-dark mb-0">{{ $totalProductsSold ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-32 text-warning avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Chưa xử lý</p>
                            <h3 class="text-dark mb-0">{{ $pendingOrders ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-32 text-success avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Đã xử lý</p>
                            <h3 class="text-dark mb-0">{{ $processedOrders ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:refresh-bold-duotone" class="fs-32 text-danger avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Yêu cầu hoàn tiền</p>
                            <h3 class="text-dark mb-0">{{ $totalRefunds ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:money-bag-bold-duotone" class="fs-32 text-secondary avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Số tiền hoàn</p>
                            <h3 class="text-dark mb-0">{{ number_format($refundAmount ?? 0, 0, ',', '.') }}₫</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Monthly Revenue Chart -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Doanh thu theo tháng ({{ now()->year }})</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Order Status Distribution -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Phân bố trạng thái đơn hàng</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusDistributionChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue by Payment Method -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Doanh thu theo phương thức thanh toán</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Phương thức</th>
                                    <th class="text-end">Doanh thu</th>
                                    <th class="text-end">Tỷ lệ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($revenueByPayment as $payment)
                                <tr>
                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                    <td class="text-end">{{ number_format($payment->revenue, 0, ',', '.') }}₫</td>
                                    <td class="text-end">{{ $totalRevenue > 0 ? number_format(($payment->revenue / $totalRevenue) * 100, 1) : 0 }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top Customers -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Khách hàng mua nhiều nhất</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Khách hàng</th>
                                    <th class="text-end">Số đơn</th>
                                    <th class="text-end">Tổng tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCustomers as $customer)
                                <tr>
                                    <td>{{ $customer->user->name ?? 'Khách vãng lai' }}</td>
                                    <td class="text-end">{{ $customer->order_count }}</td>
                                    <td class="text-end">{{ number_format($customer->total_spent, 0, ',', '.') }}₫</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Revenue Chart
    const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'Doanh thu (₫)',
                data: @json($monthlyData),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + '₫';
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + '₫';
                        }
                    }
                }
            }
        }
    });

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: @json($statusLabels),
            datasets: [{
                data: @json($statusData),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40',
                    '#FF6384',
                    '#C9CBCF',
                    '#4BC0C0',
                    '#FF6384',
                    '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endsection 