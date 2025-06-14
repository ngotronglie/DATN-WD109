@extends('layouts.admin.index')

@section('content')
<div class="container-fluid">
    <!-- Statistics Cards Row -->
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:shop-2-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Total Products</p>
                            <h3 class="text-dark mt-1 mb-0">{{ $totalProducts }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 py-2 bg-light bg-opacity-50">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 2.3%</span>
                            <span class="text-muted ms-1 fs-12">Last Month</span>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="text-reset fw-semibold fs-12">View More</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-32 text-success avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Total Users</p>
                            <h3 class="text-dark mt-1 mb-0">{{ $totalUsers }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 py-2 bg-light bg-opacity-50">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 8.1%</span>
                            <span class="text-muted ms-1 fs-12">Last Month</span>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="text-reset fw-semibold fs-12">View More</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:cart-3-bold-duotone" class="fs-32 text-warning avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Total Orders</p>
                            <h3 class="text-dark mt-1 mb-0">{{ $totalOrders }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 py-2 bg-light bg-opacity-50">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 5.3%</span>
                            <span class="text-muted ms-1 fs-12">Last Month</span>
                        </div>
                        <a href="{{ route('admin.orders.index') }}" class="text-reset fw-semibold fs-12">View More</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:money-bag-bold-duotone" class="fs-32 text-danger avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Total Revenue</p>
                            <h3 class="text-dark mt-1 mb-0">${{ number_format($totalRevenue, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 py-2 bg-light bg-opacity-50">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 12.5%</span>
                            <span class="text-muted ms-1 fs-12">Last Month</span>
                        </div>
                        <a href="{{ route('admin.revenue.index') }}" class="text-reset fw-semibold fs-12">View More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Revenue Overview</h4>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                This Year
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item">This Year</a>
                                <a href="javascript:void(0);" class="dropdown-item">Last Year</a>
                                <a href="javascript:void(0);" class="dropdown-item">Last 6 Months</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="revenue-chart" class="apex-charts" data-colors='["#3b7ddd", "#e9ecef"]'></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">Top Products</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Sales</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->total_sales }}</td>
                                    <td>${{ number_format($product->revenue, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title">Recent Orders</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Products</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>
                                        @foreach($order->items as $item)
                                            {{ $item->product->name }} ({{ $item->quantity }})<br>
                                        @endforeach
                                    </td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status_color }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
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

@push('scripts')
<script>
    // Revenue Chart
    var options = {
        series: [{
            name: 'Revenue',
            data: @json($revenueData)
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        colors: ['#3b7ddd'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                inverseColors: false,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [20, 100, 100, 100]
            }
        },
        xaxis: {
            categories: @json($revenueLabels)
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return '$' + value.toFixed(2);
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
    chart.render();
</script>
@endpush
@endsection 