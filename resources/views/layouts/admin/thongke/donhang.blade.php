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
            <div class="card clickable-card" style="cursor: pointer; transition: all 0.3s ease;" 
                 onclick="window.location.href='{{ route('admin.thongke.donhang.revenue-orders', ['filter' => $filter]) }}'">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:dollar-minimalistic-bold-duotone" class="fs-32 text-success avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Tổng doanh thu</p>
                            <h3 class="text-dark mb-0">
                                {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}₫
                                <i class="fas fa-external-link-alt ms-2 text-muted" style="font-size: 0.8em;"></i>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-3">
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
        </div> -->
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

    <!-- Delivered Orders Card (only delivered orders are counted across the dashboard) -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:shipping-fast-bold-duotone" class="fs-32 text-success avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Đơn đã giao thành công</p>
                            <h3 class="text-dark mb-0">{{ $deliveredCount ?? 0 }}</h3>
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
        <!-- <div class="col-md-3">
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
        </div> -->
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
        <div class="modal fade" id="detailedOrdersModal" tabindex="-1" aria-labelledby="detailedOrdersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailedOrdersModalLabel">Chi tiết đơn hàng theo phương thức thanh toán</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- Bộ lọc -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Lọc theo phương thức thanh toán:</label>
                        <select class="form-select" id="paymentMethodFilter" onchange="fetchFilteredOrders()">
                            <option value="">Tất cả</option>
                            <option value="cod">COD</option>
                            <option value="vnpay">VNPay</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lọc theo trạng thái:</label>
                        <select class="form-select" id="statusFilter" onchange="fetchFilteredOrders()">
                            <option value="">Tất cả</option>
                            <option value="5">Đã giao hàng</option>
                            <option value="6">Đã hủy</option>
                            <option value="14">Đã hoàn thành (khách xác nhận)</option>
                            <option value="15">Tự động hoàn thành</option>
                        </select>
                    </div>
                </div>

                <!-- Bảng dữ liệu -->
                <div class="table-responsive">
                    <table class="table table-striped" id="detailedOrdersTable">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Phương thức</th>
                                <th>Trạng thái</th>
                                <th class="text-end">Tổng tiền</th>
                                <th>Ngày đặt</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dữ liệu sẽ được JS render -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
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
<style>
.clickable-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.clickable-card:hover .text-success {
    color: #198754 !important;
}
.clickable-card:hover .text-dark {
    color: #0d6efd !important;
}
</style>
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
function showDetailedOrders() {
        const modal = new bootstrap.Modal(document.getElementById('detailedOrdersModal'));
        modal.show();
        fetchFilteredOrders(); // load dữ liệu khi mở modal
    }

    /**
     * Hàm gọi API backend để lấy dữ liệu đơn hàng đã lọc
     */
    function fetchFilteredOrders() {
        const paymentMethod = document.getElementById('paymentMethodFilter').value;
        const status = document.getElementById('statusFilter').value;

        // Gọi API
        fetch(`/admin/orders/filter?payment_method=${paymentMethod}&status=${status}`)
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    console.log("Dữ liệu từ server:", result.data);
                    const tbody = document.querySelector('#detailedOrdersTable tbody');
                    tbody.innerHTML = '';

                    // Render dữ liệu mới
                    result.data.forEach(order => {
                        const row = `
                            <tr data-payment-method="${order.payment_method}" data-status="${order.status}">
                                <td>
                                    <a href="/admin/orders/${order.id}" class="text-decoration-none">${order.order_code}</a>
                                </td>
                                <td>${order.customer ? order.customer.name : ''}</td>
                                <td>
                                    <span class="badge bg-${order.payment_method === 'cod' ? 'warning' : 'success'}">
                                        ${order.payment_method.toUpperCase()}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-${order.status == 5 ? 'success' : (order.status == 6 ? 'danger' : 'info')}">
                                        ${getStatusName(order.status)}
                                    </span>
                                </td>
                                <td class="text-end">${Number(order.total_amount).toLocaleString('vi-VN')}₫</td>
                                <td>${formatDate(order.created_at)}</td>
                                <td><span class="text-success">Đã giao thành công</span></td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });
                } else {
                    alert("Không lấy được dữ liệu từ server!");
                }
            })
            .catch(error => {
                console.error("Lỗi khi lấy dữ liệu:", error);
            });
    }

    /**
     * Hàm đổi status code thành tên trạng thái
     */
    function getStatusName(status) {
        const statusNames = {
            5: 'Đã giao hàng',
            6: 'Đã hủy',
            14: 'Đã hoàn thành (khách xác nhận)',
            15: 'Tự động hoàn thành'
        };
        return statusNames[status] || 'Không xác định';
    }

    /**
     * Hàm format ngày giờ
     */
    function formatDate(datetime) {
        const date = new Date(datetime);
        return date.toLocaleDateString('vi-VN') + ' ' + date.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
    }
</script>
@endsection 