<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng bị hủy</title>
</head>
<body>
    <h2>Xin chào {{ $user->name }},</h2>

    <p>Đơn hàng của bạn với mã <strong>#{{ $order->order_code }}</strong> đã được hủy thành công.</p>

    <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi.</p>

    <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
</body>
</html>
