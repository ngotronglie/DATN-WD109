<p>Xin chào {{ $order->name }},</p>

<p>Đơn hàng #{{ $order->order_code }} của bạn sẽ được hoàn tiền do: <strong>{{ $refund->reason }}</strong>.</p>

<p>Vui lòng cung cấp thông tin tài khoản ngân hàng để chúng tôi chuyển khoản hoàn tiền cho bạn bằng cách truy cập liên kết sau:</p>

<p><a href="{{ route('account.fillinfo', $refund->id) }}">Nhập thông tin ngân hàng cho đơn #{{ $order->order_code }}</a></p>

<p>Bạn không cần gửi trả hàng trong trường hợp này.</p>

<p>Trân trọng,</p>
<p>BeeHat Store</p>


