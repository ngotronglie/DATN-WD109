@extends('index.clientdashboard')
@section('content')
<div class="container py-5">
    <div class="alert alert-danger">
        <h4>Thanh toán thất bại!</h4>
        <p>Mã đơn hàng: <strong>{{ $order ? $order->order_code : 'Không xác định' }}</strong></p>
        <a href="/checkout" class="btn btn-warning mt-3">Thử lại</a>
    </div>
</div>
@endsection 