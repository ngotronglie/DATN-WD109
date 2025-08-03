@extends('layouts.admindashboard')

@section('content')
<div class="container">
    <h3>Chi tiết hoàn tiền cho đơn hàng #{{ $refund->order->id }}</h3>
    <p><strong>Người yêu cầu:</strong> {{ $refund->order->user->name }}</p>
    <p><strong>Lý do hoàn:</strong> {{ $refund->reason }}</p>
    <p><strong>Ngân hàng:</strong> {{ $refund->bank_name }}</p>
    <p><strong>Số tài khoản:</strong> {{ $refund->account_number }}</p>
    <p><strong>Chủ tài khoản:</strong> {{ $refund->account_name }}</p>
    @if($refund->image)
        <p><strong>Ảnh đính kèm:</strong><br>
            <img src="{{ asset('storage/' . $refund->image) }}" style="max-width:300px;">
        </p>
    @endif

    <form action="{{ route('admin.refunds.approveConfirm', $refund->id) }}" method="POST" onsubmit="return confirm('Xác nhận đã hoàn tiền?')">
        @csrf
        <button class="btn btn-success">Xác nhận đã hoàn tiền</button>
    </form>
</div>
@endsection
