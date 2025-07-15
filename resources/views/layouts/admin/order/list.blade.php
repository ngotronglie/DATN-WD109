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
                            3 => \App\Models\Order::where('status', 3)->count(),
                            4 => \App\Models\Order::where('status', 4)->count(),
                            5 => \App\Models\Order::where('status', 5)->count(),
                            6 => \App\Models\Order::where('status', 6)->count(),
                            7 => \App\Models\Order::where('status', 7)->count(),
                            8 => \App\Models\Order::where('status', 8)->count(),
                        ];
                    @endphp
                    <div class="mb-3">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm {{ request('status') === null ? 'active' : '' }}">Tất cả ({{ $counts['all'] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 0]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 0 ? 'active' : '' }}">Chờ xử lý ({{ $counts[0] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 1]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 1 ? 'active' : '' }}">Đã xác nhận ({{ $counts[1] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 2]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 2 ? 'active' : '' }}">Đang xử lý ({{ $counts[2] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 3]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 3 ? 'active' : '' }}">Đã giao cho đơn vị vận chuyển ({{ $counts[3] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 4]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 4 ? 'active' : '' }}">Đang vận chuyển ({{ $counts[4] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 5]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 5 ? 'active' : '' }}">Đã giao hàng ({{ $counts[5] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 6]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 6 ? 'active' : '' }}">Đã hủy ({{ $counts[6] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 7]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 7 ? 'active' : '' }}">Đã hoàn trả ({{ $counts[7] }})</a>
                        <a href="{{ route('admin.orders.index', ['status' => 8]) }}" class="btn btn-outline-secondary btn-sm {{ request('status') == 8 ? 'active' : '' }}">Đã hoàn tiền ({{ $counts[8] }})</a>
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
                                        @elseif($stt === 3)
                                            <span class="badge bg-secondary">Đã giao cho đơn vị vận chuyển</span>
                                        @elseif($stt === 4)
                                            <span class="badge bg-dark">Đang vận chuyển</span>
                                        @elseif($stt === 5)
                                            <span class="badge bg-success">Đã giao hàng</span>
                                        @elseif($stt === 6)
                                            <span class="badge bg-danger">Đã hủy</span>
                                        @elseif($stt === 7)
                                            <span class="badge bg-warning text-dark">Đã hoàn trả</span>
                                        @elseif($stt === 8)
                                            <span class="badge bg-success text-dark">Đã hoàn tiền</span>
                                        @else
                                            <span class="badge bg-secondary">Không xác định</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $order->status_method ?? '-' }}</td>
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
        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM8 13c-4.418 0-8-5-8-5s3.582-5 8-5 8 5 8 5-3.582 5-8 5zm0-9a4 4 0 1 0 0 8 4 4 0 0 0 0-8zm0 7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
    </svg>
</a>
                                    </td>
                                    <td class="text-center">
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
                                                <button class="btn btn-info btn-sm" onclick="return confirm('Chuyển sang đóng gói?')">Đóng gói</button>
                                            </form>
                                        @elseif($stt === 2)
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="3">
                                                <button class="btn btn-secondary btn-sm" onclick="return confirm('Giao cho vận chuyển?')">Giao cho vận chuyển</button>
                                            </form>
                                        @elseif($stt === 3)
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="4">
                                                <button class="btn btn-dark btn-sm" onclick="return confirm('Đang vận chuyển?')">Đang vận chuyển</button>
                                            </form>
                                        @elseif($stt === 4)
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="6">
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hủy đơn này?')">Hủy đơn</button>
                                            </form>
                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="status" value="5">
                                                <button class="btn btn-success btn-sm" onclick="return confirm('Hoàn thành đơn này?')">Hoàn thành đơn hàng</button>
                                            </form>
                                            @elseif($stt === 5)
                                            -

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
            if(confirm('Bạn có chắc muốn cập nhật trạng thái đơn hàng?')) {
                fetch(`/admin/orders/${orderId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
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
    box-shadow: 0 2px 8px rgba(37,99,235,0.08);
}
.btn-icon-eye svg {
    display: block;
}
</style>
