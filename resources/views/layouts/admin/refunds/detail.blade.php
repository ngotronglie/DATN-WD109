@extends('index.admindashboard')

@section('content')
<h2>Chi tiết yêu cầu hoàn tiền/hoàn hàng - Đơn #{{ $refund->order_id }}</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<table class="table table-bordered">
    @if($refund->bank_name || $refund->bank_number || $refund->account_name)
    <tr>
        <th>Ngân hàng</th>
        <td>{{ $refund->bank_name }}</td>
    </tr>
    <tr>
        <th>Số tài khoản</th>
        <td>{{ $refund->bank_number }}</td>
    </tr>
    <tr>
        <th>Tên tài khoản</th>
        <td>{{ $refund->account_name }}</td>
    </tr>
    @endif
    <tr>
        <th>Lý do</th>
        <td>{{ $refund->reason }}</td>
    </tr>
 <tr>
        <th>Ảnh minh chứng khách gửi</th>
        <td>
            @if($refund->image)
            <img src="{{ asset('storage/' . $refund->image) }}" width="150">
            @else
            Không có
            @endif
        </td>
   
    <tr>
        <th>Ảnh minh chứng admin</th>
        <td>
            @if($refund->proof_image)
            <img src="{{ asset('storage/' . $refund->proof_image) }}" width="150">
            @else
            Chưa có
            @endif
        </td>
    </tr>
    <tr>
        <th>Trạng thái</th>
        <td>
            @if($refund->refund_completed_at)
            <span class="text-success">Đã hoàn lúc {{ $refund->refund_completed_at }} bởi {{ $refund->refunded_by }}</span>
            @else
            <span class="text-warning">Chưa hoàn</span>
            @endif
        </td>
    </tr>
    @if(!$refund->refund_completed_at)
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title mb-3">Tải lên ảnh xác nhận đã hoàn (admin)</h5>
        <form action="{{ route('admin.refunds.uploadProof', $refund->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <input type="file" name="proof_image" id="proof_image" class="form-control" accept="image/*" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Tải lên</button>
                </div>
            </div>
        </form>
    </div>
 </div>
@endif
</table>



@php
    $order = $refund->order;
@endphp

@if($order && (int)$order->status === 11)
<div class="d-flex gap-2">
    {{-- Bước 2: Duyệt yêu cầu hoàn hàng (chuyển đơn sang trạng thái 7) --}}
    <form action="{{ route('admin.refunds.approveReturn', $refund->id) }}" method="POST" onsubmit="return confirm('Duyệt yêu cầu hoàn hàng? Đơn sẽ chuyển sang trạng thái: Hoàn hàng đã được duyệt.')">
        @csrf
        <button class="btn btn-success">Duyệt yêu cầu hoàn hàng</button>
    </form>
    {{-- Từ chối yêu cầu hoàn hàng --}}
    <form action="{{ route('admin.refunds.reject', ['id' => $refund->id]) }}" method="POST" onsubmit="return confirm('Từ chối yêu cầu hoàn hàng?')">
        @csrf
        <button class="btn btn-danger">Từ chối</button>
    </form>
</div>
@endif

<a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mt-3">← Quay lại danh sách</a>
<style>
    h2 {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 25px;
    }

    table.table-bordered {
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    table th {
        width: 25%;
        background-color: #f8f9fa;
        font-weight: 600;
        vertical-align: middle;
    }

    table td {
        vertical-align: middle;
        font-size: 16px;
    }

    img {
        max-width: 100%;
        height: auto;
        border-radius: 6px;
        border: 1px solid #dee2e6;
    }

    .text-success {
        font-weight: bold;
    }

    .text-warning {
        font-weight: bold;
    }

    form input[type="file"] {
        font-size: 14px;
    }

    .btn-primary {
        font-size: 14px;
        padding: 5px 12px;
    }

    .btn-secondary {
        font-size: 15px;
        padding: 8px 16px;
    }
</style>

@endsection