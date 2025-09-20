<div style="font-family: 'Segoe UI', sans-serif; color: #333; max-width: 600px; margin: auto; line-height: 1.6;">
    <h2 style="color: #28a745;">Chào {{ $order->name }},</h2>

    <p>Cảm ơn bạn đã đặt hàng tại <strong style="color: #007bff;"> Shop TechZone</strong>.</p>
    <p>
        Chúng tôi đã nhận được đơn hàng của bạn với mã đơn: 
        <strong style="color: #dc3545; font-size: 1.1em;">#{{ $order->order_code }}</strong>.
    </p>
    <p>Đơn hàng của bạn đang được xử lý và sẽ được giao đến bạn trong thời gian sớm nhất.</p>

    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">

    <h4 style="margin-top: 20px; color: #444;">📦 Thông tin đơn hàng:</h4>
    <ul style="padding-left: 20px; list-style: none;">
        <li><strong>📞 Số điện thoại:</strong> {{ $order->phone }}</li>
        <li><strong>🏠 Địa chỉ:</strong> {{ $order->address }}</li>
        <li><strong>💰 Tổng thanh toán:</strong> {{ number_format($order->total_amount) }}đ</li>
        <li><strong>💳 Phương thức thanh toán:</strong> {{ strtoupper($order->payment_method) }}</li>
    </ul>

    <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">

    <p style="margin-top: 30px;">
        Nếu bạn có bất kỳ thắc mắc nào, đừng ngần ngại liên hệ với chúng tôi qua email hoặc hotline.
    </p>

    <p style="margin-top: 40px;">
        Trân trọng,<br>
        <strong style="color: #007bff;">TechZone</strong>
    </p>
</div>
