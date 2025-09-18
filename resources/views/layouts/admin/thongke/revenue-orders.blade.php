{{-- resources/views/layouts/admin/thongke/revenue-orders.blade.php --}}
@extends('layouts.admin.index')
@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Đơn hàng theo doanh thu</h4>
                    <p class="text-muted mb-0">Danh sách các đơn hàng đã giao thành công</p>
                </div>
                <a href="{{ route('admin.thongke.donhang') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại thống kê
                </a>
            </div>
        </div>
    </div>

    <!-- Filter buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group" role="group">
                @foreach($filterOptions as $key => $label)
                <a href="{{ route('admin.thongke.donhang.revenue-orders', ['filter' => $key]) }}" 
                   class="btn {{ $filter == $key ? 'btn-primary' : 'btn-outline-primary' }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:dollar-minimalistic-bold-duotone" class="fs-32 text-success avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Tổng doanh thu ({{ $filterOptions[$filter] }})</p>
                            <h3 class="text-dark mb-0">{{ number_format($totalRevenue, 0, ',', '.') }}₫</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:cart-large-2-bold-duotone" class="fs-32 text-primary avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Số đơn hàng</p>
                            <h3 class="text-dark mb-0">{{ $orders->total() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Danh sách đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Khách hàng</th>
                                    <th>Phương thức thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th class="text-end">Tổng tiền</th>
                                    <th>Ngày đặt</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none fw-bold">
                                            {{ $order->order_code }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($order->user)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title bg-light text-primary rounded-circle">
                                                        {{ substr($order->user->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $order->user->name }}</h6>
                                                    <small class="text-muted">{{ $order->user->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Khách vãng lai</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->payment_method == 'cod' ? 'warning' : 'success' }}">
                                            {{ strtoupper($order->payment_method) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $statusNames = [
                                                0 => 'Chờ xử lý',
                                                1 => 'Đã xác nhận', 
                                                2 => 'Đang xử lý',
                                                3 => 'Đã giao cho vận chuyển',
                                                4 => 'Đang vận chuyển',
                                                5 => 'Đã giao hàng',
                                                6 => 'Đã hủy',
                                                7 => 'Xác nhận yêu cầu hoàn hàng',
                                                8 => 'Hoàn hàng',
                                                9 => 'Hoàn tiền',
                                                10 => 'Không xác nhận yêu cầu hoàn hàng'
                                            ];
                                            $statusColors = [
                                                0 => 'warning',
                                                1 => 'info',
                                                2 => 'primary',
                                                3 => 'secondary',
                                                4 => 'info',
                                                5 => 'success',
                                                6 => 'danger',
                                                7 => 'warning',
                                                8 => 'secondary',
                                                9 => 'success',
                                                10 => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                            {{ $statusNames[$order->status] ?? 'Không xác định' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-success">
                                            {{ number_format($order->total_amount, 0, ',', '.') }}₫
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="fw-medium">{{ $order->created_at->format('d/m/Y') }}</span>
                                            <br>
                                            <small class="text-muted">{{ $order->created_at->format('H:i:s') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                               xem chi tiết
                                            </a>
                                            @if($order->status < 5)
                                            <a href="{{ route('admin.orders.edit', $order->id) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>Không có đơn hàng nào trong khoảng thời gian này</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($orders->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
