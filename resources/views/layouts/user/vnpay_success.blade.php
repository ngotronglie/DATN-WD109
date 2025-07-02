@extends('index.clientdashboard')
@section('content')
<div class="container py-5">
    <div class="alert alert-success">
        <h4>Thanh toán thành công!</h4>
        <p>Mã đơn hàng: <strong>{{ $order->order_code }}</strong></p>
        <a href="/" class="btn btn-primary mt-3">Về trang chủ</a>
    </div>
</div>
@endsection 