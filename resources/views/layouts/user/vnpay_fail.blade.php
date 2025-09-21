@extends('index.clientdashboard')

@section('content')
<main>
    <div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="card border-0 shadow-lg text-center p-5 rounded-4" style="max-width: 680px; width: 100%;">
            <style>
                /* Icon trạng thái */
                .status-icon {
                    font-size: 4.5rem;
                }

                /* Badge phương thức */
                .payment-badge {
                    font-size: 1rem;
                    font-weight: 600;
                    padding: 0.5rem 1rem;
                }

                /* Box chứa mã đơn hàng */
                .order-code-box {
                    background-color: #f8f9fa;
                    border-radius: 12px;
                    padding: 1rem;
                    margin-bottom: 1.5rem;
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
                }
                .order-code-box p {
                    margin-bottom: 0.25rem;
                    color: #6c757d;
                    font-size: 0.95rem;
                }
                .order-code-box h4 {
                    margin: 0;
                    line-height: 1.3;
                    font-size: 1.5rem;
                    font-weight: 700;
                    font-variant-numeric: tabular-nums; /* chữ số đều nhau */
                    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
                    letter-spacing: 1px;
                    color: #212529;
                }

                /* Nhóm nút */
                .btn-fx {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: .5rem;
                    line-height: 1;
                    min-width: 160px;
                    text-align: center;
                    font-weight: 600;
                }

                .button-group {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    gap: 1rem;
                }

                /* Responsive */
                @media (max-width: 576px) {
                    .btn-fx {
                        width: 100%;
                    }
                }
            </style>

            <!-- Icon thất bại -->
            <div class="mb-3">
                <i class="zmdi zmdi-close-circle text-danger status-icon"></i>
            </div>

            <!-- Tiêu đề -->
            <h2 class="fw-bold text-danger mb-2">Thanh toán thất bại</h2>
            <p class="text-muted mb-3">Rất tiếc, giao dịch chưa hoàn tất. Bạn có thể thử lại hoặc tiếp tục thanh toán sau.</p>

            <!-- Badge phương thức -->
            <div class="mb-3">
                <span class="badge rounded-pill text-bg-danger payment-badge">Phương thức: VNPAY</span>
            </div>

            <!-- Mã đơn hàng -->
            <div class="order-code-box">
                <p>Mã đơn hàng</p>
                <h4>{{ $order ? $order->order_code : 'Không xác định' }}</h4>
            </div>

            <!-- Nút hành động -->
            <div class="button-group">
                <a href="/checkout" class="btn btn-danger px-4 py-2 rounded-3 btn-fx">
                    <span>🔄</span><span>Thử lại</span>
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-dark px-4 py-2 rounded-3 btn-fx">
                    <span>🏠</span><span>Về trang chủ</span>
                </a>
            </div>
        </div>
    </div>
</main>

@endsection
