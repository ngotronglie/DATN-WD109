@extends('index.clientdashboard')

@section('content')
<main>
    <div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="card border-0 shadow-lg text-center p-5 rounded-4" style="max-width: 680px; width: 100%;">
            <style>
                /* Icon */
                .status-icon {
                    font-size: 4.5rem;
                }

                /* Badge */
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
                    font-variant-numeric: tabular-nums;
                    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
                    letter-spacing: 1px;
                    color: #212529;
                }

                /* Button nhóm */
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
                .btn-fx span:first-child {
                    font-size: 1.1rem;
                }

                /* Đảm bảo nút căn giữa khi wrap */
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

            <!-- Icon Status -->
            <div class="mb-3">
                <i class="zmdi zmdi-check-circle text-success status-icon"></i>
            </div>

            <!-- Title -->
            <h2 class="fw-bold text-success mb-2">Thanh toán thành công</h2>
            <p class="text-muted mb-3">Cảm ơn bạn đã mua hàng tại <strong class="text-dark">Shop TechZone</strong>.</p>

            <!-- Info Chip -->
            <div class="mb-3">
                <span class="badge rounded-pill text-bg-success payment-badge">Phương thức: VNPAY</span>
            </div>

            <!-- Order Code -->
            <div class="order-code-box">
                <p>Mã đơn hàng của bạn</p>
                <h4>{{ $order->order_code }}</h4>
            </div>

            <!-- Buttons -->
            <div class="button-group">
                <a href="{{ route('account.order') }}" class="btn btn-success px-4 py-2 rounded-3 btn-fx">
                    <span>📦</span><span>Xem đơn hàng</span>
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-dark px-4 py-2 rounded-3 btn-fx">
                    <span>🏠</span><span>Về trang chủ</span>
                </a>
            </div>
        </div>
    </div>
</main>


@endsection

