@extends('index.admindashboard')

@section('content')
<div class="container py-5">
    <div class="mb-3">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-left"></i> Quay lại danh sách đơn hàng</a>
    </div>
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">Chi tiết đơn hàng #{{ $order->order_code }}</div>
                <div class="card-body">
                    <h5>Thông tin người nhận</h5>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Họ tên:</strong> {{ $order->name }}</li>
                        <li class="list-group-item"><strong>SĐT:</strong> {{ $order->phone }}</li>
                        <li class="list-group-item"><strong>Địa chỉ:</strong> {{ $order->address }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ $order->email }}</li>
                        <li class="list-group-item"><strong>Ghi chú:</strong> {{ $order->note }}</li>
                        <li class="list-group-item"><strong>Thanh toán:</strong> {{ $order->payment_method }}</li>
                        @if($order->voucher)
                        <li class="list-group-item"><strong>Voucher:</strong> {{ $order->voucher->code }}</li>
                        @endif
                    </ul>
                    <h5 class="mt-4">Sản phẩm trong đơn hàng</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Màu</th>
                                    <th>Dung lượng</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($order->orderDetails as $item)
                                @php
                                $itemTotal = $item->price * $item->quantity;
                                $total += $itemTotal;
                                @endphp
                                <tr>
                                    <td>
                                        @if($item->productVariant->image)
                                        <img src="{{ $item->productVariant->image }}" alt="{{ $item->productVariant->product->name ?? '' }}" style="width:40px;height:40px;object-fit:cover;margin-right:8px;">
                                        @endif
                                        {{ $item->productVariant->product->name ?? '' }}
                                    </td>
                                    <td>{{ $item->productVariant->color->name ?? '-' }}</td>
                                    <td>{{ $item->productVariant->capacity->name ?? '-' }}</td>
                                    <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($itemTotal, 0, ',', '.') }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <strong>Tổng tiền: </strong>
                        <span style="font-size:1.2em;color:#d9534f;font-weight:600;">{{ number_format($total, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="mt-2">
                        <strong>Phí ship: </strong>
                        <span style="color:#ff9800;font-weight:600;">
                            @php
                            $shipping = $total > 2000000 ? 0 : ($total > 0 ? 50000 : 0);
                            @endphp
                            {{ $shipping === 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . 'đ' }}
                        </span>
                    </div>
                    @php
                    $discountAmount = 0;
                    if ($order->voucher) {
                    $discountAmount = floor($total * ($order->voucher->discount / 100));
                    if ($order->voucher->min_money && $discountAmount < $order->voucher->min_money) $discountAmount = $order->voucher->min_money;
                        if ($order->voucher->max_money && $discountAmount > $order->voucher->max_money) $discountAmount = $order->voucher->max_money;
                        }
                        $finalTotal = $total - $discountAmount + $shipping;
                        @endphp
                        @if($discountAmount > 0)
                        <div class="mt-2">
                            <strong>Giảm giá: </strong>
                            <span style="color:#28a745;font-weight:600;">-{{ number_format($discountAmount, 0, ',', '.') }}đ</span>
                        </div>
                        @endif
                        <div class="mt-2">
                            <strong>Thành tiền: </strong>
                            <span style="font-size:1.2em;color:#007bff;font-weight:600;">{{ number_format($finalTotal, 0, ',', '.') }}đ</span>
                        </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">Thông tin đơn hàng</div>
                <div class="card-body">
                    <div><strong>Mã đơn:</strong> #{{ $order->order_code }}</div>
                    <div><strong>Trạng thái:</strong>
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
                        <span class="badge bg-warning text-dark">Đã xác nhận yêu cầu hoàn hàng</span>
                        @elseif($stt === 8)
                        <span class="badge bg-success text-dark">Đã nhận hàng hoàn</span>
                        @elseif($stt === 9)
                        <span class="badge bg-success text-dark">Hoàn tiền thành công</span>
                        @elseif($stt === 10)
                        <span class="badge bg-success text-dark">Không xác nhận yêu cầu hoàn hàng</span>
                        @elseif($stt === 13)
                        <span class="badge bg-danger">Giao hàng thất bại</span>
                        @elseif($stt === 14)
                        <span class="badge bg-warning text-dark">COD không nhận hàng</span>
                        @else
                        <span class="badge bg-secondary">Không xác định</span>
                        @endif
                    </div>
                    <div><strong>Ngày đặt:</strong> {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}</div>
                    <div><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</div>
                    <div><strong>Ghi chú:</strong> {{ $order->note }}</div>
                    
                    @if($stt === 4)
                    <div class="mt-3">
                        <h6>Hành động:</h6>
                        <form action="{{ route('admin.orders.deliverySuccess', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm me-2" onclick="return confirm('Xác nhận giao hàng thành công?')">
                                <i class="fas fa-check"></i> Giao thành công
                            </button>
                        </form>
                        <form action="{{ route('admin.orders.deliveryFailed', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm me-2" onclick="return confirm('Xác nhận giao hàng thất bại?')">
                                <i class="fas fa-times"></i> Giao thất bại
                            </button>
                        </form>
                        @if (strtolower((string)$order->payment_method) === 'cod')
                        <form action="{{ route('admin.orders.codNotReceived', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Xác nhận COD không nhận hàng?')">
                                <i class="fas fa-user-times"></i> Khách không nhận hàng
                            </button>
                        </form>
                        @endif
                    </div>
                    @elseif($stt === 13)
                    <div class="mt-3">
                        <h6>Hành động:</h6>
                        <form action="{{ route('admin.orders.redeliver', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm me-2" onclick="return confirm('Xác nhận giao hàng lại?')">
                                <i class="fas fa-redo"></i> Giao hàng lại
                            </button>
                        </form>
                        @if (strtolower((string)$order->payment_method) === 'vnpay')
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#initiateRefundModal-{{ $order->id }}">
                            <i class="fas fa-money-bill-wave"></i> Hoàn tiền
                        </button>
                        @endif
                    </div>
                    @elseif($stt === 14)
                    <div class="mt-3">
                        <h6>Hành động:</h6>
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="status" value="6">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận hủy đơn hàng COD không nhận?')">
                                <i class="fas fa-times"></i> Hủy đơn
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if (strtolower((string)$order->payment_method) === 'vnpay' && $stt === 13)
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
                        <option value="Giao hàng thất bại">Giao hàng thất bại</option>
                        <option value="Khác">Khác</option>
                    </select>
                    <input type="text" class="form-control mt-2" id="refund-reason-input-{{ $order->id }}" name="reason_other" placeholder="Nhập lý do khác" style="display:none;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning">Tạo yêu cầu hoàn</button>
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
@endsection