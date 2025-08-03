@extends('index.clientdashboard')

@section('content')
<div class="container py-5">
    <div class="card shadow p-4 border-0 rounded-4">
        <h4 class="mb-4 fw-bold text-primary">Chi tiết đơn hàng: <span class="text-dark">{{ $order->order_code }}</span></h4>

        @php
        $statusLabels = [
        0 => 'Chờ xác nhận',
        1 => 'Đã xác nhận',
        2 => 'Đang chuẩn bị',
        3 => 'Đã đến tay shiper',
        4 => 'đang giao đến',
        5 => 'Đã giao',
        6 => 'Đã hủy',
        7 => 'xác nhận yêu cầu Hoàn hàng',
        8 => 'Hoàn hàng',
        9 => 'Hoàn tiền ',
        10 => 'không xác nhận yêu cầu hoàn hàng',
        ];
        $statusColors = [
        0 => 'bg-warning text-dark', // Chờ xác nhận
        1 => 'bg-info text-dark', // Đã xác nhận
        2 => 'bg-primary text-white', // Đang chuẩn bị
        3 => 'bg-primary text-white', // Đã đến tay shiper
        4 => 'bg-warning text-dark', // Đang giao đến
        5 => 'bg-success text-white', // Đã giao
        6 => 'bg-danger text-white', // Đã hủy
        7 => 'bg-secondary text-white', // Xác nhận yêu cầu hoàn hàng
        8 => 'bg-info text-white', // Hoàn hàng
        9 => 'bg-success text-dark', // Hoàn tiền
        10 => 'bg-dark text-white', // Không xác nhận yêu cầu hoàn hàng
        ];

        $status = $order->status;
        $statusText = $statusLabels[$status] ?? 'Không xác định';
        $badgeClass = $statusColors[$status] ?? 'bg-light text-dark';
        @endphp

        <div class="mb-4">
            <p class="mb-1"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
            <p class="mb-0"><strong>Trạng thái:</strong> <span class="badge {{ $badgeClass }} px-3 py-1 rounded-pill">{{ $statusText }}</span></p>
        </div>

        @if ($order->voucher)
        <div class="mb-4">
            <p class="mb-1"><strong>Voucher:</strong> {{ $order->voucher->code }}</p>
            <p class="mb-0"><strong>Giảm giá:</strong>
                {{ $order->voucher->discount_type == 'percent' ? $order->voucher->discount_value . '%' : number_format($order->voucher->discount_value) . '₫' }}
            </p>
        </div>
        @endif

        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Sản phẩm</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-end">Giá</th>
                    <th class="text-end">Tổng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderDetails as $detail)
                <tr>
<<<<<<< HEAD
                <td> @if ($detail->productVariant && $detail->productVariant->image)
                    <img src="{{ $detail->productVariant->image }}" alt="Ảnh sản phẩm" width="100">
                    @else
                    <span>Không có ảnh</span>
                    @endif</td>
=======
                    <td> @if ($detail->productVariant && $detail->productVariant->image)
                        <img src="{{ $detail->productVariant->image }}" alt="Ảnh sản phẩm" width="100">
                        @else
                        <span>Không có ảnh</span>
                        @endif
                    </td>
>>>>>>> 8d6865d3c52e21186f8e9d710b5c349e0a69a041
                    <td class="text-center">{{ $detail->quantity }}</td>
                    <td class="text-end">{{ number_format($detail->price) }}₫</td>
                    <td class="text-end">{{ number_format($detail->price * $detail->quantity) }}₫</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <td colspan="3" class="text-end">Phí vận chuyển:</td>
                    <td class="text-end">
                        {{ $order->shipping_fee == 0 ? 'Miễn phí' : number_format($order->shipping_fee) . '₫' }}
                    </td>
                </tr>

                @if ($order->voucher)
                <tr>
                    <td colspan="3" class="text-end">Giảm giá:</td>
                    <td class="text-end text-danger">
                        -{{ $order->voucher->discount_type == 'percent' 
                ? $order->voucher->discount_value . '%' 
                : number_format($order->voucher->discount_value) . '₫' }}
                    </td>
                </tr>
                @endif

                <tr>
                    <td colspan="3" class="text-end fw-bold">Tổng tiền cần thanh toán:</td>
                    <td class="text-end fw-bold">
                        {{ number_format($order->total_amount + $order->shipping_fee) }}₫
                    </td>
                </tr>
            </tfoot>



        </table>
        {{-- Nếu đơn hàng đang ở trạng thái đã hoàn thành (4) hoặc đã hủy (5) --}}
        @if (in_array($order->status, [4, 5]))
        @if ($order->refundRequest == null)
        <div class="text-end mt-4">
            <button class="btn btn-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#refundModal">
                Yêu cầu hoàn tiền
            </button>
        </div>
        @else
        <div class="alert alert-warning mt-4 rounded-4">
            Bạn đã gửi yêu cầu hoàn hàng. Vui lòng đợi quản trị viên xử lý.
        </div>
        @endif
        @endif

        {{-- Nếu đơn đang xử lý hoàn trả (status = 7) và thiếu thông tin --}}
        @if ($order->status == 7 && $order->refundRequest)
        @php
        $refund = $order->refundRequest;
        $missingInfo = !$refund->bank_name || !$refund->bank_number || !$refund->image;
        @endphp

        @if ($missingInfo)
        <div class="alert alert-info mt-4 rounded-4 d-flex justify-content-between align-items-center">
            <span>Bạn cần cung cấp thông tin chuyển khoản và ảnh trả hàng.</span>
            <a href="{{ route('account.fillinfo', $refund->id) }}" class="btn btn-primary btn-sm rounded-pill">
                Trả hàng ngay
            </a>
        </div>
        @else
        <div class="alert alert-warning mt-4 rounded-4">
            Bạn đã gửi yêu cầu hoàn hàng. Vui lòng đợi quản trị viên xử lý.
        </div>
        @endif
        @endif


    </div>

    {{-- Thông tin hoàn tiền nếu đã hoàn --}}
    @if ($order->status == 9 && $order->refundRequest)
    <div class="card shadow mt-5 p-4 border-0 rounded-4">
        <h5 class="fw-bold text-primary mb-3">Thông tin hoàn tiền</h5>
        <p><strong>Ngân hàng:</strong> {{ $order->refundRequest->bank_name }}</p>
        <p><strong>Số tài khoản:</strong> {{ $order->refundRequest->bank_number }}</p>
        <p><strong>Người nhận:</strong> {{ $order->refundRequest->account_name }}</p>
        <p><strong>Lý do:</strong> {{ $order->refundRequest->reason }}</p>

        @if ($order->refundRequest->image)
        <div class="mb-3">
            <strong>Ảnh minh chứng (bạn gửi):</strong><br>
            <img src="{{ asset('storage/' . $order->refundRequest->image) }}" class="img-thumbnail mt-2" style="max-width: 300px;">
        </div>
        @endif

        @if ($order->refundRequest->proof_image)
        <div class="mb-3">
            <strong>Ảnh hoàn tiền (admin):</strong><br>
            <img src="{{ asset('storage/' . $order->refundRequest->proof_image) }}" class="img-thumbnail mt-2" style="max-width: 300px;">
        </div>
        @endif

        @if ($order->refundRequest->refund_completed_at)
        <p class="text-success fw-bold">
            Đã hoàn lúc {{ \Carbon\Carbon::parse($order->refundRequest->refund_completed_at)->format('d/m/Y H:i') }} bởi {{ $order->refundRequest->refunded_by }}
        </p>
        @else
        <p class="text-warning fw-bold">
            Yêu cầu đang được xử lý...
        </p>
        @endif
    </div>
    @endif
</div>

{{-- Modal hoàn tiền --}}
<div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('refund.store') }}" method="POST" enctype="multipart/form-data" class="modal-content shadow rounded-4 fs-5">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div class="modal-header">
                <h5 class="modal-title" id="refundModalLabel">Yêu cầu hoàn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Lý do hoàn hàng</label>
                    <select class="form-select reason-select" name="reason" required>
                        <option value="">-- Chọn lý do --</option>
                        <option value="Sản phẩm không giống hình ảnh quảng cáo">Sản phẩm không giống hình ảnh quảng cáo</option>
                        <option value="Giao sai sản phẩm">Giao sai sản phẩm</option>
                        <option value="Sản phẩm bị hỏng, vỡ, trầy xước">Sản phẩm bị hỏng, vỡ, trầy xước</option>
                        <option value="Sản phẩm bị lỗi kỹ thuật">Sản phẩm bị lỗi kỹ thuật</option>
                        <option value="Thiếu linh kiện/phụ kiện">Thiếu linh kiện/phụ kiện</option>
                        <option value="Không đúng mô tả">Không đúng mô tả</option>
                        <option value="Nhận hàng trễ">Nhận hàng trễ</option>
                        <option value="Không còn nhu cầu">Không còn nhu cầu</option>
                        <option value="Đặt nhầm">Đặt nhầm</option>
                        <option value="Khác">Khác (ghi rõ ở ghi chú)</option>
                    </select>
                    <input type="text" name="reason_input" class="form-control mt-2 reason-input" placeholder="Nhập lý do khác nếu chọn 'Khác'" style="display: none;">
                </div>

                <div class="mb-3">
                    <label class="form-label">Ảnh minh chứng (nếu có)</label>
                    <input type="file" class="form-control" name="image" accept="image/*">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </form>

    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.querySelector('.reason-select');
        const input = document.querySelector('.reason-input');

        select.addEventListener('change', function() {
            if (select.value === 'Khác') {
                input.style.display = 'block';
                input.required = true;
            } else {
                input.style.display = 'none';
                input.value = '';
                input.required = false;
            }
        });
    });
</script>
@endsection