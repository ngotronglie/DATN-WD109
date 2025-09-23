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
                <div class="card-header d-flex justify-content-between item ">
                    <h3 class="card-title">Danh sách đơn hàng</h3>
                </div>
                <div class="card-body">
                    @php
                    // Đếm số lượng đơn hàng cho từng trạng thái
                    $counts = [
                    'all' => \App\Models\Order::count(),
                    0 => \App\Models\Order::where('status', 0)->count(),
                    1 => \App\Models\Order::where('status', 1)->count(),
                    2 => \App\Models\Order::where('status', 2)->count(),
                    4 => \App\Models\Order::where('status', 4)->count(),
                    5 => \App\Models\Order::where('status', 5)->count(),
                    6 => \App\Models\Order::where('status', 6)->count(),
                    7 => \App\Models\Order::where('status', 7)->count(),
                    8 => \App\Models\Order::where('status', 8)->count(),
                    9 => \App\Models\Order::where('status', 9)->count(),
                    11 => \App\Models\Order::where('status', 11)->count(),
                    12 => \App\Models\Order::where('status', 12)->count(),
                    13 => \App\Models\Order::where('status', 13)->count(),
                    14 => \App\Models\Order::where('status', 14)->count(),
                    15 => \App\Models\Order::where('status', 15)->count(),
                    ];
                    @endphp
                    <div class="mb-3">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm {{ request('status') === null ? 'active' : '' }}">Tất cả ({{ $counts['all'] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 0]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 0 ? 'active' : '' }}">Chờ xử lý ({{ $counts[0] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 1]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 1 ? 'active' : '' }}">Đã xác nhận ({{ $counts[1] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 2]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 2 ? 'active' : '' }}">Đang xử lý ({{ $counts[2] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 4]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 4 ? 'active' : '' }}">Đang giao hàng ({{ $counts[4] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 5]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 5 ? 'active' : '' }}">Đã giao hàng ({{ $counts[5] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 6]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 6 ? 'active' : '' }}">Đã hủy ({{ $counts[6] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 7]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 7 ? 'active' : '' }}">Đã duyệt đơn hàng hoàn ({{ $counts[7] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 11]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 11 ? 'active' : '' }}">Đang yêu cầu hoàn hàng ({{ $counts[11] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 8]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 8 ? 'active' : '' }}">Đã nhận được hàng hoàn ({{ $counts[8] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 9]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 9 ? 'active' : '' }}">Đã hoàn tiền ({{ $counts[9] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 12]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 12 ? 'active' : '' }}">Không hoàn hàng ({{ $counts[12] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 13]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 13 ? 'active' : '' }}">Giao hàng thất bại ({{ $counts[13] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 14]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 14 ? 'active' : '' }}">Khách không nhận hàng ({{ $counts[14] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 15]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 15 ? 'active' : '' }}">Đã giao thành công ({{ $counts[15] }})</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Mã đơn</th>
                                    <th class="text-center">Tên khách</th>
                                    <th class="text-center">Tổng tiền</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Trạng thái thanh toán</th>
                                    <th class="text-center">Thanh toán</th>
                                    <th class="text-center">Ngày tạo</th>
                                    <th class="text-center">Voucher</th>
                                    <th class="text-center">Xem chi tiết</th>
                                    <th class="text-center">Thao tác</th>
                                    <th class="text-center">Hoàn tiền</th> <!-- Thêm dòng này -->
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr class="order-row" data-order-id="{{ $order->id }}">
                                    <td class="text-center">{{ $loop->iteration + ($orders->perPage() * ($orders->currentPage() - 1)) }}</td>
                                    <td class="text-center">{{ $order->order_code }}</td>
                                    <td>{{ $order->name }}</td>
                                    <td class="text-end">{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                                    <td class="text-center">
                                        @php $stt = (int) $order->status; @endphp
                                        @if($stt === 0)
                                        <span class="badge bg-warning">Chờ xử lý</span>
                                        @elseif($stt === 1)
                                        <span class="badge bg-primary">Đã xác nhận</span>
                                        @elseif($stt === 2)
                                        <span class="badge bg-info">Đang xử lý</span>
                                        @elseif($stt === 4)
                                        <span class="badge bg-dark">Đang giao hàng</span>
                                        @elseif($stt === 5)
                                        <span class="badge bg-success">Đã giao hàng</span>
                                        @elseif($stt === 6)
                                        <span class="badge bg-danger">Đã hủy</span>
                                        @elseif($stt === 7)
                                        @php $isAdminRefund = optional($order->refundRequest)->type === 'admin_refund'; @endphp
                                        @if($isAdminRefund)
                                        <span class="badge bg-warning text-dark">Đã khởi tạo hoàn tiền cho khách</span>
                                        @else
                                        <span class="badge bg-warning text-dark">Đã duyệt đơn hàng hoàn</span>
                                        @endif
                                        @elseif($stt === 11)
                                        <span class="badge bg-warning text-dark">Đang yêu cầu hoàn hàng</span>
                                        @elseif($stt === 8)
                                        <span class="badge bg-success text-dark">Đã nhận được hàng hoàn</span>
                                        @elseif($stt === 9)
                                        <span class="badge bg-success text-dark">Hoàn tiền thành công</span>
                                        @elseif($stt === 10)
                                        <span class="badge bg-success text-dark">Không xác nhận yêu cầu hoàn tiền</span>
                                        @elseif($stt === 12)
                                        <span class="badge bg-dark text-white">Không hoàn hàng</span>
                                        @elseif($stt === 13)
                                        <span class="badge bg-danger">Giao hàng thất bại</span>
                                        @elseif($stt === 14)
                                        <span class="badge bg-warning text-dark">Khách không nhận hàng</span>
                                        @elseif($stt === 15)
                                        <span class="badge bg-success">Đã giao thành công</span>
                                        @else
                                        <span class="badge bg-secondary">Không xác định</span>
                                        @endif
                                    </td>
<td>
                                    @if ((int)$order->status_method == 0)
                                    <span class="badge bg-danger">Chưa thanh toán</span>
                                    @elseif ((int)$order->status_method == 1)
                                    <span class="badge bg-success">Đã thanh toán </span>
                                    @elseif ((int)$order->status_method == 2)
                                    <span class="badge bg-success">Đã thanh toán </span>
                                    @endif
</td>
                                     <td class="text-center">{{ $order->payment_method }}</td>
                                    <td class="text-center">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="text-center">
                                        @if($order->voucher)
                                        <span class="badge bg-info">{{ $order->voucher->code }}</span>
                                        @else
                                        -
                                        @endif
                                    </td>



                                    <td class="text-center">
                                        <a href="{{ route('admin.orders.detail', $order->id) }}" class="btn btn-sm btn-primary" title="Xem chi tiết">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM8 13c-4.418 0-8-5-8-5s3.582-5 8-5 8 5 8 5-3.582 5-8 5zm0-9a4 4 0 1 0 0 8 4 4 0 0 0 0-8zm0 7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                                            </svg>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        @if (strtolower((string)$order->payment_method) === 'vnpay' && (int)($order->status_method ?? 0) === 0)
                                            <span class="text-muted">-</span>
                                        @else
                                            @php $stt = (int) $order->status; @endphp
                                            @if($stt === 0)
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="1">
                                                <button class="btn btn-success btn-sm" onclick="return confirm('Xác nhận đơn này?')">Xác nhận đơn</button>
                                            </form>
                                            @elseif($stt === 1)
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="2">
                                                <button class="btn btn-info btn-sm me-1" onclick="return confirm('Chuyển sang đóng gói?')">Đóng gói</button>
                                            </form>
                                            @if (strtolower((string)$order->payment_method) === 'vnpay' && (int)($order->status_method ?? 0) !== 0)
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#initiateRefundModal-{{ $order->id }}">Hủy đơn</button>
                                        @endif
                                           
                                            @elseif($stt === 2)
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="4">
                                                <button class="btn btn-dark btn-sm" onclick="return confirm('Chuyển sang đang giao hàng?')">Đang giao hàng</button>
                                            </form>
                                            @elseif($stt === 4)
                                            <form action="{{ route('admin.orders.deliverySuccess', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button class="btn btn-success btn-sm me-1" onclick="return confirm('Xác nhận giao hàng thành công?')">Giao thành công</button>
                                            </form>
                                            <form action="{{ route('admin.orders.deliveryFailed', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button class="btn btn-danger btn-sm me-1" onclick="return confirm('Xác nhận giao hàng thất bại?')">Giao thất bại</button>
                                            </form>
                                            @if (strtolower((string)$order->payment_method) === 'cod')
                                            <form action="{{ route('admin.orders.codNotReceived', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button class="btn btn-warning btn-sm" onclick="return confirm('Xác nhận COD không nhận hàng?')">COD không nhận</button>
                                            </form>
                                            @endif
                                            @elseif($stt === 5)
                                            -
                                            @elseif($stt === 13)
                                            <form action="{{ route('admin.orders.redeliver', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button class="btn btn-primary btn-sm me-1" onclick="return confirm('Xác nhận giao hàng lại?')">Giao hàng lại</button>
                                            </form>
                                            @if (strtolower((string)$order->payment_method) === 'vnpay' && (int)($order->status_method ?? 0) !== 0)
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#initiateRefundModal-{{ $order->id }}">Hoàn tiền</button>
                                            @endif
                                            @elseif($stt === 14)
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="6">
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận hủy đơn hàng COD không nhận?')">Đã nhận hàng hoàn</button>
                                            </form>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($order->refundRequest)
                                        <a href="{{ route('admin.refunds.detail', ['id' => $order->refundRequest->id]) }}"
                                            class="btn btn-info btn-xs mb-1 px-2 py-1" style="font-size: 12px;">
                                            Xem
                                        </a>
                                        @if (!$order->refundRequest->refund_completed_at)

                                        {{-- Nếu đang ở trạng thái yêu cầu hoàn (status = 6) --}}
                                        @if ($order->status == 5 && !$order->refundRequest->is_verified)
                                        <form action="{{ route('admin.refunds.verify', ['id' => $order->refundRequest->id]) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button class="btn btn-primary btn-xs px-2 py-1 mb-1" style="font-size: 12px;" onclick="return confirm('Xác nhận yêu cầu hoàn?')">
                                                Xác thực yêu cầu
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.refunds.reject', ['id' => $order->refundRequest->id]) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button class="btn btn-danger btn-xs px-2 py-1 mb-1" style="font-size: 12px;" onclick="return confirm('Hủy yêu cầu hoàn?')">
                                                Hủy yêu cầu
                                            </button>
                                        </form>

                                        {{-- Cho phép duyệt hoàn hàng khi đang yêu cầu hoàn hàng (11) --}}
                                        @elseif ($order->status == 11 && $order->refundRequest)
                                        
                                        @endif
                                        @endif

                                        {{-- Duyệt hoàn tiền: cho phép từ trạng thái 7 (đã duyệt hoàn hàng) hoặc 8 (đã nhận hàng hoàn) --}}
                                        @if (in_array($order->status, [7,8]) && !$order->refundRequest->refund_completed_at)
                                       
                                        @endif
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>




                                </tr>
                                <tr class="order-detail-row" id="order-detail-{{ $order->id }}" style="display:none; background:#f9f9f9;">
                                    <td colspan="11">
                                        <strong>Chi tiết sản phẩm:</strong>
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Tên sản phẩm</th>
                                                        <th>Ảnh</th>
                                                        <th>Màu</th>
                                                        <th>Dung lượng</th>
                                                        <th>Số lượng</th>
                                                        <th>Giá</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($order->orderDetails as $detail)
                                                    <tr>
                                                        <td>{{ $detail->productVariant->product->name ?? 'N/A' }}</td>
                                                        <td>
                                                            @if(!empty($detail->productVariant->image))
                                                            <img src="{{ $detail->productVariant->image }}" alt="Ảnh" width="40">
                                                            @endif
                                                        </td>
                                                        <td>{{ $detail->productVariant->color->name ?? '-' }}</td>
                                                        <td>{{ $detail->productVariant->capacity->name ?? '-' }}</td>
                                                        <td>{{ $detail->quantity }}</td>
                                                        <td>{{ number_format($detail->price, 0, ',', '.') }} đ</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">Chưa có đơn hàng nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $orders->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@foreach($orders as $order)
    @if (in_array((int)$order->status, [1,2,13]) && strtolower((string)$order->payment_method) === 'vnpay' && (int)($order->status_method ?? 0) !== 0)
    <div class="modal fade" id="initiateRefundModal-{{ $order->id }}" tabindex="-1" aria-labelledby="initiateRefundLabel-{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('admin.orders.refund.initiate', $order->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="initiateRefundLabel-{{ $order->id }}">Khởi tạo hoàn tiền - Đơn #{{ $order->order_code }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Lý do hoàn tiền</label>
                        <select class="form-select" id="refund-reason-select-{{ $order->id }}" name="reason" required>
                            <option value="">-- Chọn lý do --</option>
                            <option value="Hết hàng">Hết hàng</option>
                            <option value="Khác">Khác</option>
                        </select>
                        <input type="text" class="form-control mt-2" id="refund-reason-input-{{ $order->id }}" name="reason_other" placeholder="Nhập lý do khác" style="display:none;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Tạo yêu cầu hoàn tiền</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var select = document.getElementById('refund-reason-select-{{ $order->id }}');
            var input = document.getElementById('refund-reason-input-{{ $order->id }}');
            if (select && input) {
                select.addEventListener('change', function() {
                    if (this.value === 'Khác') {
                        input.style.display = '';
                        input.setAttribute('name','reason');
                    } else {
                        input.style.display = 'none';
                        input.removeAttribute('name');
                        input.value = '';
                    }
                });
            }
        });
    </script>
    @endif
@endforeach
@endsection

@section('script')
<script>
    document.querySelectorAll('.order-row').forEach(function(row) {
        row.addEventListener('click', function() {
            var orderId = this.getAttribute('data-order-id');
            var detailRow = document.getElementById('order-detail-' + orderId);
            if (detailRow.style.display === 'none') {
                detailRow.style.display = '';
            } else {
                detailRow.style.display = 'none';
            }
        });
    });

    document.querySelectorAll('.update-status-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            var orderId = this.getAttribute('data-order-id');
            var status = this.getAttribute('data-status');
            if (confirm('Bạn có chắc muốn cập nhật trạng thái đơn hàng?')) {
                fetch(`/admin/orders/${orderId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message || 'Cập nhật trạng thái thành công!');
                            location.reload();
                        } else {
                            alert(data.message || 'Cập nhật trạng thái thất bại!');
                        }
                    })
                    .catch(() => alert('Có lỗi xảy ra!'));
            }
        });
    });
</script>
@endsection

<style>
    .btn-icon-eye {
        display: flex !important;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f5f9;
        border: none;
        width: 38px;
        height: 38px;
        transition: background 0.2s;
        padding: 0;
        margin: 0 auto;
    }

    .btn-icon-eye:hover {
        background: #dbeafe;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.08);
    }

    .btn-icon-eye svg {
        display: block;
    }
</style>