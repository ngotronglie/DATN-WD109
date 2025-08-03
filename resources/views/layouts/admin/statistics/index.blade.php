@extends('index.admindashboard')

@section('content')
<div class="container">
    <h2 class="statistics-title">Thống kê doanh thu</h2>

    <form method="GET" action="{{ route('admin.statistics.index') }}" class="mb-4">
        <div class="d-flex gap-3 align-items-end">
            <div>
                <label for="from_date">Từ ngày:</label>
                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div>
                <label for="to_date">Đến ngày:</label>
                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Lọc</button>
            </div>
        </div>
    </form>

    <table class="statistics-table">
        <thead>
            <tr>
                <th>Doanh thu</th>
                <th>Số sản phẩm bán</th>
                <th>Số đơn thành công</th>
                <th>Số người mua</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ number_format($stat->total_revenue, 0, ',', '.') }}₫</td>
                <td>{{ $stat->total_products_sold }}</td>
                <td>{{ $stat->total_success_orders }}</td>
                <td>{{ $stat->total_customers }}</td>
            </tr>
        </tbody>
    </table>
</div>

@if(count($orders) > 0)
<h4 class="mt-4">Chi tiết các đơn hàng thành công</h4>
<table class="table table-bordered mt-2">
    <thead>
        <tr>
            <th>Mã đơn</th>
            <th>Người mua</th>
            <th>Tổng tiền</th>
            <th>Ngày đặt</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
        <tr>
            <td><a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->order_code }}</a></td>
            <td>{{ $order->user_name ?? 'Không có' }}</td>
            <td>{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
                    <div class="d-flex justify-content-end mt-3">
                        {{ $orders->links('pagination::bootstrap-4') }}
                    </div>
@else
<p class="text-center mt-4">Không có đơn hàng thành công nào trong khoảng thời gian này.</p>
@endif
<h4 class="mt-5 mb-3 text-center">Biểu đồ doanh thu theo ngày</h4>
<canvas id="revenueChart" height="100"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($dailyRevenue->pluck('date')) !!},
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: {!! json_encode($dailyRevenue->pluck('revenue')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    ticks: { autoSkip: false }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat().format(value) + '₫';
                        }
                    }
                }
            }
        }
    });
</script>
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($lowStockProducts  as $variant)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $variant->product_name }}</td>
                <td class="text-danger fw-bold">{{ $variant->quantity }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<style>
      h4.text-center {
        font-size: 22px;
        font-weight: bold;
        color: #007bff;
        margin-top: 40px;
        margin-bottom: 20px;
    }

    /* Style cho biểu đồ */
    #revenueChart {
        max-width: 100%;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .statistics-title {
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 20px;
}

form .form-control {
    min-width: 200px;
    padding: 6px 12px;
}

.statistics-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    text-align: center;
}

.statistics-table th, 
.statistics-table td {
    padding: 12px 15px;
    border: 1px solid #dee2e6;
}

.statistics-table thead {
    background-color: #007bff;
    color: #fff;
}

.statistics-table tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}

.statistics-table td {
    font-weight: bold;
    color: #333;
}

    .statistics-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .statistics-table thead {
        background-color: #004085;
        color: #ffffff;
        text-align: center;
    }

    .statistics-table thead th {
        padding: 14px;
        font-size: 16px;
        font-weight: 600;
    }

    .statistics-table tbody td {
        padding: 12px;
        font-size: 15px;
        text-align: center;
        border-bottom: 1px solid #dee2e6;
    }

    .statistics-table tbody tr:hover {
        background-color: #f1f1f1;
        transition: background-color 0.3s ease;
    }

    .statistics-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 25px;
        color: #343a40;
    }

    .container {
        margin-top: 30px;
    }
</style>
@endsection

