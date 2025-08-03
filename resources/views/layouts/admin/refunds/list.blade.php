@extends('index.admindashboard')

@section('content')
<h2>Yêu cầu hoàn tiền</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Đơn hàng</th>
            <th>Ngân hàng</th>
            <th>Số tài khoản</th>
            <th>Lý do</th>
            <th>Ảnh</th>
            <th>Ngày yêu cầu</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($refunds as $refund)
        <tr>
            <td>#{{ $refund->order_id }}</td>
            <td>{{ $refund->bank_name }}</td>
            <td>{{ $refund->bank_number }}</td>
            <td>{{ $refund->reason }}</td>
            <td>
                @if($refund->image)
                <img src="{{ asset('storage/' . $refund->image) }}" width="80">
                @endif
            </td>
            <td>{{ $refund->refund_requested_at }}</td>
            <td>
                @if($refund->refund_completed_at)
                <span class="text-success">Đã hoàn - {{ $refund->refunded_by }}</span>
                @else
                <span class="text-warning">Chưa hoàn</span>
                @endif
            </td>
            <td>
                @if(!$refund->refund_completed_at)
                <a href="{{ route('admin.refunds.detail', $refund->id) }}" class="btn btn-info btn-sm">Xem chi tiết</a>
                @csrf
                <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Xác nhận đã hoàn tiền?')">
                    Duyệt hoàn
                </button>
                </form>

                @endif

            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $refunds->links() }}
@endsection