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
                                        @php $variant = $item->productVariant; @endphp
                                        @php
                                            $imageUrl = null;
                                            if ($variant && $variant->image) {
                                                $img = $variant->image;
                                                // Nếu không phải URL tuyệt đối, dùng asset() để tạo URL đầy đủ
                                                $isAbsolute = preg_match('/^https?:\/\//i', $img);
                                                $imageUrl = $isAbsolute ? $img : asset($img);
                                            }
                                        @endphp
                                        <img src="{{ $imageUrl ?: asset('images/no-image.png') }}"
                                             alt="{{ optional($variant?->product)->name ?? 'Sản phẩm' }}"
                                             style="width:40px;height:40px;object-fit:cover;margin-right:8px;"
                                             onerror="this.src='{{ asset('images/no-image.png') }}'">
                                        {{ optional($variant?->product)->name ?? 'N/A' }}
                                    </td>
                                    <td>{{ optional($variant?->color)->name ?? '-' }}</td>
                                    <td>{{ optional($variant?->capacity)->name ?? '-' }}</td>
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
                        @php $isAdminRefund = optional($order->refundRequest)->type === 'admin_refund'; @endphp
                        @if($isAdminRefund)
                        <span class="badge bg-warning text-dark">Đã khởi tạo hoàn tiền cho khách</span>
                        @else
                        <span class="badge bg-warning text-dark">Đã duyệt đơn hàng hoàn</span>
                        @endif
                        @elseif($stt === 8)
                        <span class="badge bg-success text-dark">Đã nhận được hàng hoàn</span>
                        @elseif($stt === 9)
                        <span class="badge bg-success text-dark">Hoàn tiền thành công</span>
                        @elseif($stt === 10)
                        <span class="badge bg-success text-dark">Không xác nhận yêu cầu hoàn hàng</span>
                        @elseif($stt === 13)
                        <span class="badge bg-danger">Giao hàng thất bại</span>
                        @elseif($stt === 14)
                        <span class="badge bg-warning text-dark">COD không nhận hàng</span>
                        @elseif($stt === 15)
                        <span class="badge bg-success">Đã giao thành công</span>
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
                        @if (strtolower((string)$order->payment_method) === 'vnpay' && (int)($order->status_method ?? 0) !== 0)
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#initiateRefundModal-{{ $order->id }}">
                            <i class="fas fa-money-bill-wave"></i> Hoàn tiền
                        </button>
                        @endif
                    </div>
                    @elseif($stt === 6)
                    <div class="mt-3">
                        <h6>Hành động:</h6>
                        @if (strtolower((string)$order->payment_method) === 'vnpay' && (int)($order->status_method ?? 0) !== 0)
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

@if($order->refundRequest)
<div class="container pb-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mt-3">
                <div class="card-header bg-warning text-dark">Thông tin hoàn tiền</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ngân hàng</label>
                            <div class="form-control">{{ $order->refundRequest->bank_name ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Số tài khoản</label>
                            <div class="form-control">{{ $order->refundRequest->bank_number ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Chủ tài khoản</label>
                            <div class="form-control">{{ $order->refundRequest->account_name ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Lý do</label>
                            <div class="form-control">{{ $order->refundRequest->reason ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Thời gian yêu cầu</label>
                            <div class="form-control">{{ optional($order->refundRequest->refund_requested_at)->format('d/m/Y H:i') ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Thời gian hoàn tiền</label>
                            <div class="form-control">{{ optional($order->refundRequest->refund_completed_at)->format('d/m/Y H:i') ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Người hoàn</label>
                            <div class="form-control">{{ $order->refundRequest->refunded_by ?? '-' }}</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Ảnh minh chứng hoàn tiền (Admin)</label>
                            @php
                                $proofUrl = $order->refundRequest->proof_image ? asset('storage/' . $order->refundRequest->proof_image) : null;
                            @endphp
                            @if($proofUrl)
                                <div>
                                    <a href="{{ $proofUrl }}" target="_blank">
                                        <img src="{{ $proofUrl }}" alt="Proof" style="max-width:260px; height:auto; border:1px solid #ddd; border-radius:6px;">
                                    </a>
                                </div>
                            @else
                                <div class="text-muted mb-2">Chưa có minh chứng</div>
                                @if($order->refundRequest)
                                <form action="{{ route('admin.refunds.uploadProof', $order->refundRequest->id) }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2 align-items-center flex-wrap">
                                    @csrf
                                    <input type="file" name="proof_image" class="form-control" accept="image/*" required style="max-width:300px;">
                                    <button type="submit" class="btn btn-primary">Tải ảnh minh chứng</button>
                                </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (strtolower((string)$order->payment_method) === 'vnpay' && (int)($order->status_method ?? 0) !== 0 && in_array($stt, [6,13]))
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
                        <option value="Khách hủy đơn">Khách hủy đơn</option>
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
@endsection