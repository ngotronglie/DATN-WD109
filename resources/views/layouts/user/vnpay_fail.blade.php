@extends('index.clientdashboard')

@section('content')
<main>
    <div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="card border-0 shadow-lg text-center p-5 rounded-4" style="max-width: 650px; width: 100%;">
            <!-- Icon thất bại -->
            <div class="mb-4">
                <i class="zmdi zmdi-close-circle text-danger" style="font-size: 5rem;"></i>
            </div>

            <!-- Tiêu đề -->
            <h2 class="fw-bold text-danger mb-3">Thanh toán thất bại!</h2>
            <p class="fs-5 text-muted">Rất tiếc, giao dịch của bạn không thành công.</p>

            <!-- Mã đơn -->
            <div class="bg-light rounded-3 p-3 my-4 shadow-sm">
                <p class="mb-1 text-secondary">Mã đơn hàng:</p>
                <h4 class="fw-bold text-dark">{{ $order ? $order->order_code : 'Không xác định' }}</h4>
            </div>

            <!-- Nút hành động -->
            <div class="d-flex justify-content-center gap-3">
                <a href="/checkout" class="btn btn-danger px-4 py-2 rounded-3">
                    🔄 Thử lại
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-dark px-4 py-2 rounded-3">
                    🏠 Về trang chủ
                </a>
            </div>
        </div>
    </div>
</main>
@endsection
