@extends('index.admindashboard')

@section('content')
<h2>Yêu cầu hoàn hàng</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Đơn hàng</th>
            <th>Lý do</th>
            <th>Ảnh</th>
            <th>Ngày yêu cầu</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($returns as $refund)
        <tr>
            <td>#{{ $refund->order_id }}</td>
            <td>{{ $refund->reason }}</td>
            <td>
                @if($refund->image)
                <img src="{{ asset('storage/' . $refund->image) }}" width="80">
                @endif
            </td>
            <td>{{ $refund->refund_requested_at }}</td>
            <td>
                @if($refund->received_back_at)
                <span class="text-success">Đã nhận lại hàng</span>
                @else
                <span class="text-warning">Chờ xác nhận</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.refunds.detail', $refund->id) }}" class="btn btn-info btn-sm">Xem chi tiết</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $returns->links() }}
@endsection


