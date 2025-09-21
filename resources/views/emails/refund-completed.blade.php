<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hoàn tiền thành công</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#111; }
        .container { max-width: 680px; margin: 0 auto; padding: 20px; }
        .card { border: 1px solid #eee; border-radius: 10px; padding: 20px; }
        .title { font-size: 20px; font-weight: 700; margin-bottom: 10px; }
        .muted { color:#555; }
        .btn { display:inline-block; padding:10px 16px; background:#0d6efd; color:#fff !important; text-decoration:none; border-radius:8px; }
        .info { background:#f8f9fa; border-radius:8px; padding:12px; margin:12px 0; }
        img { max-width: 100%; height: auto; border-radius: 6px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="title">Hoàn tiền thành công</div>
        <p>Xin chào {{ $order->user->name ?? 'Quý khách' }},</p>
        <p class="muted">Chúng tôi đã xử lý hoàn tiền cho đơn hàng <strong>#{{ $order->order_code ?? $order->id }}</strong>.</p>

        <div class="info">
            <p><strong>Thông tin đơn hàng</strong></p>
            <p>Mã đơn: <strong>{{ $order->order_code ?? $order->id }}</strong></p>
            <p>Tổng tiền: <strong>{{ number_format($order->total_amount + ($order->shipping_fee ?? 0)) }}₫</strong></p>
            <p>Phương thức thanh toán: <strong>{{ strtoupper($order->payment_method ?? 'VNPAY') }}</strong></p>
        </div>

        @if($refund)
        <div class="info">
            <p><strong>Thông tin nhận hoàn</strong></p>
            @if(!empty($refund->bank_name))
                <p>Ngân hàng: <strong>{{ $refund->bank_name }}</strong></p>
            @endif
            @if(!empty($refund->bank_number))
                <p>Số tài khoản: <strong>{{ $refund->bank_number }}</strong></p>
            @endif
            @if(!empty($refund->account_name))
                <p>Chủ tài khoản: <strong>{{ $refund->account_name }}</strong></p>
            @endif
            @if(!empty($refund->reason))
                <p>Lý do hoàn: <strong>{{ $refund->reason }}</strong></p>
            @endif
        </div>
        @endif

        @if(!empty($proofUrl))
            <p><strong>Ảnh minh chứng chuyển khoản</strong></p>
            <p class="muted">Bạn có thể tham khảo minh chứng giao dịch bên dưới:</p>
            <p><img src="{{ $proofUrl }}" alt="Ảnh minh chứng hoàn tiền"></p>
        @endif

        <p>
            <a class="btn" href="{{ route('user.orders.show', $order->id) }}">Xem chi tiết đơn hàng</a>
        </p>

        <p class="muted">Nếu bạn cần hỗ trợ thêm, vui lòng phản hồi email này.</p>
        <p>Trân trọng,<br>Đội ngũ hỗ trợ khách hàng</p>
    </div>
</div>
</body>
</html>
