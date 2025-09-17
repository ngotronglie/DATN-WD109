@extends('index.clientdashboard')

@section('content')
<body>
    <!-- Main Content -->
    <main>
        <div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="card border-0 shadow-lg text-center p-5 rounded-4" style="max-width: 650px; width: 100%;">
                <!-- Icon Success -->
                <div class="mb-4">
                    <i class="zmdi zmdi-check-circle text-success" style="font-size: 5rem;"></i>
                </div>

                <!-- Title -->
                <h2 class="fw-bold text-success mb-3">Thanh toán thành công!</h2>
                <p class="fs-5 text-muted">Cảm ơn bạn đã mua hàng tại 
                    <strong class="text-dark">Shop TechZone</strong>.
                </p>

                <!-- Order Code -->
                <div class="bg-light rounded-3 p-3 my-4 shadow-sm">
                    <p class="mb-1 text-secondary">Mã đơn hàng của bạn:</p>
                    <h4 class="fw-bold text-dark">{{ $order->order_code }}</h4>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('home') }}" class="btn btn-dark px-4 py-2 rounded-3">
                        🏠 Về trang chủ
                    </a>
                    <a href="{{ route('account.order') }}" class="btn btn-outline-success px-4 py-2 rounded-3">
                        📦 Xem đơn hàng
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>


@endsection

