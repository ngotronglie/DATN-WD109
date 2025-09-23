@extends('index.clientdashboard')

@section('content')

<div class="container py-5">
    <div class="card shadow p-4 border-0 rounded-4">
<div class="d-flex align-items-center mb-4">
    <h4 class="fw-bold text-primary mb-0">Chi tiết đơn hàng:</h4>
    <span class="ms-2 fs-3 fw-bold text-dark">{{ $order->order_code }}</span>
</div>


        @php
        $statusLabels = [
        0 => 'Chờ xác nhận',
        1 => 'Đã xác nhận',
        2 => 'Đang chuẩn bị',
        4 => 'Đang giao hàng',
        5 => 'Đã giao',
        6 => 'Đã hủy',
        7 => 'Đã duyệt yêu cầu hoàn hàng',
        8 => 'Đã trả hàng',
        9 => 'Hoàn tiền thành công',
        10 => 'Không xác nhận yêu cầu hoàn hàng',
        11 => 'Đang yêu cầu hoàn hàng',
        12 => 'Không hoàn hàng',
        13 => 'Giao hàng thất bại',
        15 => 'Đã giao thành công',
        ];
        $statusColors = [
        0 => 'bg-warning text-dark', // Chờ xác nhận
        1 => 'bg-info text-dark', // Đã xác nhận
        2 => 'bg-primary text-white', // Đang chuẩn bị
        4 => 'bg-warning text-dark', // Đang giao hàng
        5 => 'bg-success text-white', // Đã giao
        6 => 'bg-danger text-white', // Đã hủy
        7 => 'bg-secondary text-white', // Xác nhận yêu cầu hoàn tiền
        8 => 'bg-info text-white', // Hoàn hàng
        9 => 'bg-success text-dark', // Hoàn tiền
        10 => 'bg-dark text-white', // Không xác nhận yêu cầu hoàn tiền
        11 => 'bg-warning text-dark', // Đang yêu cầu hoàn hàng
        12 => 'bg-dark text-white', // Không hoàn hàng
        13 => 'bg-danger text-white', // Giao hàng thất bại
        15 => 'bg-success text-white', // Đã giao thành công
        ];

        $status = $order->status;
        $statusText = $statusLabels[$status] ?? 'Không xác định';
        $badgeClass = $statusColors[$status] ?? 'bg-light text-dark';

        // Tùy biến nhãn trạng thái 7: nếu là hoàn tiền do admin (admin_refund) thì hiển thị 'Đang hoàn tiền'
        if ((int)$status === 7 && ($order->refundRequest?->type ?? null) === 'admin_refund') {
            $statusText = 'Đang hoàn tiền';
        }

        // Không còn điều chỉnh nhãn theo phương thức thanh toán cho các trạng thái hoàn hàng/hoàn tiền trung gian.

        // Nếu VNPAY chưa thanh toán và đơn còn ở trạng thái chờ (0), hiển thị 'Chờ thanh toán'
        if ((int)$order->status === 0 && strtolower((string)$order->payment_method) === 'vnpay' && (int)$order->status_method === 0) {
            $statusText = 'Chờ thanh toán';
            $badgeClass = 'bg-warning text-dark';
        }
        @endphp

        <div class="mb-4">
            <p class="mb-1"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
            <p class="mb-0"><strong>Trạng thái:</strong> <span class="badge {{ $badgeClass }} px-3 py-1 rounded-pill">{{ $statusText }}</span></p>
        </div>
        @if (session('success'))
        <div class="alert alert-success rounded-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger rounded-4">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger rounded-4">
            <div class="fw-bold mb-1">Có lỗi xảy ra:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (in_array($order->status, [0,1]))
        <form action="{{ route('order.cancel', $order->id) }}" method="POST" class="mb-4">
            @csrf
            <button type="submit" class="btn btn-outline-danger rounded-pill" onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">Hủy đơn</button>
        </form>
        @endif

        {{-- Nếu là VNPAY và chưa thanh toán, hiển thị nút tiếp tục thanh toán --}}
        @if (strtolower((string)$order->payment_method) === 'vnpay' && (int)$order->status_method === 0)
        <div class="mb-4">
            <a href="/vnpay/payment?order_id={{ $order->id }}" class="btn btn-primary rounded-pill">
                Tiếp tục thanh toán VNPAY
            </a>
        </div>
        @endif

        @if ((int)$order->status === 6 && strtolower((string)$order->payment_method) === 'vnpay')
        <form action="{{ route('user.orders.reorder', $order->id) }}" method="POST" class="mb-4">
            @csrf
            <button type="submit" class="btn btn-primary rounded-pill" onclick="return confirm('Thêm lại các sản phẩm vào giỏ hàng?')">Đặt lại hàng</button>
        </form>
        @endif

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
                <td> @if ($detail->productVariant && $detail->productVariant->image)
                    <img src="{{ $detail->productVariant->image }}" alt="Ảnh sản phẩm" width="100">
                    @else
                    <span>Không có ảnh</span>
                    @endif</td>
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
        {{-- Nếu đơn hàng đang ở trạng thái đang giao đến (4), đã giao (5) hoặc đã giao thành công (15) --}}
        @if (in_array($order->status, [4, 5, 15]) && strtolower((string)$order->payment_method) === 'vnpay')
        @if ($order->refundRequest == null)
        <div class="text-end mt-4">

        </div>
        @else
        <div class="alert alert-warning mt-4 rounded-4">
            Bạn đã gửi yêu cầu hoàn hàng. Vui lòng đợi quản trị viên xử lý.
        </div>
        @endif
        @endif

        {{-- Hiển thị sau khi Admin đánh dấu giao hàng thành công (status = 5) và khách chưa xác nhận trước đó --}}
        @if ($order->status == 5 && empty($order->received_confirmed_at))
        <form action="{{ route('user.orders.confirmReceived', $order->id) }}" method="POST" class="text-end mt-2">
            @csrf
            <button type="submit" class="btn btn-outline-success rounded-pill">Xác nhận đã nhận hàng</button>
        </form>
        @endif

        {{-- Bước 3: Khi admin đã duyệt hoàn hàng (status = 7) và KHÁCH là người yêu cầu (type != admin_refund) --}}
        @php $isVnp = strtolower((string)$order->payment_method) === 'vnpay'; @endphp
        @if ($order->status == 7 && $order->refundRequest && ($order->refundRequest->type !== 'admin_refund'))
        <div class="alert alert-info mt-2 rounded-4 d-flex justify-content-between align-items-center">
            <span>Yêu cầu hoàn hàng của bạn đã được duyệt. Vui lòng gửi/trả hàng lại cho shop. Sau khi đã gửi, hãy nhấn nút "Đã trả hàng" để chúng tôi bắt đầu quy trình hoàn tiền.</span>
        </div>
        <form action="{{ route('user.orders.markReturned', $order->id) }}" method="POST" class="text-end mt-2">
            @csrf
            <button type="submit" class="btn btn-outline-primary rounded-pill">Đã trả hàng</button>
        </form>
        @endif

        @if (in_array($order->status, [5,15]) && $order->refundRequest == null && empty($order->received_confirmed_at))
        <div class="text-end mt-2">
            <button class="btn btn-outline-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#returnModal">
                Yêu cầu hoàn hàng
            </button>
        </div>
        @endif

        {{-- Nếu đơn đang xử lý hoàn tiền do admin (status = 7) và thiếu thông tin ngân hàng --}}
        @if ($order->status == 7 && $order->refundRequest)
        @php
        $refund = $order->refundRequest;
        $missingInfo = !$refund->bank_name || !$refund->bank_number || !$refund->account_name;
        @endphp

        @if ($missingInfo)
        <div class="alert alert-info mt-4 rounded-4 d-flex justify-content-between align-items-center">
            <span>Bạn cần cung cấp thông tin tài khoản ngân hàng để nhận hoàn tiền.</span>
            <a href="{{ route('account.fillinfo', $refund->id) }}" class="btn btn-primary btn-sm rounded-pill">
                Cung cấp thông tin ngân hàng
            </a>
        </div>
        @endif
        @endif

        {{-- Nếu đơn giao hàng thất bại (status = 13) và có yêu cầu hoàn tiền --}}
        @if ($order->status == 13 && $order->refundRequest)
        @php
        $refund = $order->refundRequest;
        $missingInfo = !$refund->bank_name || !$refund->bank_number || !$refund->account_name;
        @endphp

        @if ($missingInfo)
        <div class="alert alert-warning mt-4 rounded-4 d-flex justify-content-between align-items-center">
            <span>Đơn hàng giao thất bại. Bạn cần cung cấp thông tin tài khoản ngân hàng để nhận hoàn tiền.</span>
            <a href="{{ route('account.fillinfo', $refund->id) }}" class="btn btn-primary btn-sm rounded-pill">
                Cung cấp thông tin ngân hàng
            </a>
        </div>
        @else
        <div class="alert alert-info mt-4 rounded-4">
            <span>Đơn hàng giao thất bại. Thông tin ngân hàng đã được cung cấp, đang chờ admin xử lý hoàn tiền.</span>
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

{{-- Modal hoàn tiền (chỉ hiển thị khi thanh toán online) --}}
@if (strtolower((string)$order->payment_method) === 'vnpay')
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
@endif

{{-- Modal hoàn hàng (chỉ hiển thị khi đã giao hoặc đã giao thành công) --}}
@if (in_array($order->status, [5,15]) && empty($order->received_confirmed_at))
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('order.return', $order->id) }}" method="POST" enctype="multipart/form-data" class="modal-content shadow rounded-4 fs-5">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="returnModalLabel">Yêu cầu hoàn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Lý do hoàn hàng</label>
                    <select class="form-select return-reason-select" name="reason" required>
                        <option value="">-- Chọn lý do --</option>
                        <option value="Không ưng ý sau khi nhận">Không ưng ý sau khi nhận</option>
                        <option value="Sản phẩm không đúng mong đợi">Sản phẩm không đúng mong đợi</option>
                        <option value="Đổi ý">Đổi ý</option>
                        <option value="Khác">Khác (ghi rõ ở ghi chú)</option>
                    </select>
                    <input type="text" name="reason_input" class="form-control mt-2 return-reason-input" placeholder="Nhập lý do khác nếu chọn 'Khác'" style="display: none;">
                </div>

                <div class="border rounded p-3 mb-3 bg-light">
                    <div class="mb-2 fw-bold">Thông tin ngân hàng nhận hoàn tiền</div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Tên ngân hàng</label>
                            <input type="text" class="form-control" name="bank_name" placeholder="VD: Vietcombank" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Số tài khoản</label>
                            <input type="text" class="form-control" name="bank_number" placeholder="Nhập số tài khoản" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Chủ tài khoản</label>
                            <input type="text" class="form-control" name="account_name" placeholder="Tên chủ tài khoản" required>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">Vui lòng nhập chính xác để quá trình hoàn tiền diễn ra nhanh chóng.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ảnh minh chứng (nếu có)</label>
                    <input type="file" class="form-control" name="image" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Gửi yêu cầu hoàn hàng</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </form>
    </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select2 = document.querySelector('.return-reason-select');
            const input2 = document.querySelector('.return-reason-input');
            if (select2 && input2) {
                select2.addEventListener('change', function() {
                    if (select2.value === 'Khác') {
                        input2.style.display = 'block';
                        input2.required = true;
                    } else {
                        if (input2.value === '' || input2.value === null) {
                            input2.style.display = 'none';
                            input2.required = false;
                        }
                    }
                });
            }
        });
    </script>
@endif
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