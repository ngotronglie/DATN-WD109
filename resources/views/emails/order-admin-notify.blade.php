<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng mới #{{ $order->order_code }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color:#111; }
        .wrap { max-width:640px; margin:0 auto; border:1px solid #eee; border-radius:8px; overflow:hidden }
        .header { background:#111827; color:#fff; padding:14px 18px; font-weight:700; font-size:16px }
        .content { padding:18px }
        .muted { color:#6b7280 }
        .table { width:100%; border-collapse: collapse; margin-top:12px }
        .table th, .table td { border:1px solid #e5e7eb; padding:8px; text-align:left; font-size: 14px }
        .mb-8 { margin-bottom:8px }
        .mb-4 { margin-bottom:4px }
        .badge { display:inline-block; padding:2px 8px; border-radius:999px; background:#eef2ff; color:#3730a3; font-size:12px }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">Đơn hàng mới #{{ $order->order_code }}</div>
    <div class="content">

       <p>Xin chào Admin</p>
        
        <p class="mb-8">Có đơn hàng mới vừa được tạo bởi người dùng <strong>{{ $user->name }}</strong> ({{ $user->email }}).</p>

        <div class="mb-8">
            <div class="mb-4"><strong>Mã đơn:</strong> {{ $order->order_code }}</div>
            <div class="mb-4"><strong>Khách hàng:</strong> {{ $order->name }} - {{ $order->phone }}</div>
            <div class="mb-4"><strong>Địa chỉ:</strong> {{ $order->address }}</div>
            <div class="mb-4">
                <strong>Tổng tiền:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} đ
                <span class="badge">{{ strtoupper((string)$order->payment_method) }}</span>
            </div>
            <div class="mb-4 muted"><strong>Thời gian:</strong> {{ optional($order->created_at)->format('d/m/Y H:i') }}</div>
        </div>

        @if(method_exists($order, 'orderDetails') && $order->relationLoaded('orderDetails'))
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>SL</th>
                        <th>Giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderDetails as $detail)
                        <tr>
                            <td>{{ optional(optional($detail->productVariant)->product)->name ?? 'N/A' }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>{{ number_format($detail->price, 0, ',', '.') }} đ</td>
                            <td>{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }} đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

      
    </div>
</div>
</body>
</html>
