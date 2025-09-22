@extends('index.admindashboard')

@section('content')
<h2>Yêu cầu hoàn tiền (thanh toán online)</h2>
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
            <th>Minh chứng</th>
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
            <td>
                @if($refund->proof_image)
                    <a href="{{ asset('storage/' . $refund->proof_image) }}" target="_blank">Xem</a>
                @else
                    <span class="text-muted">Chưa có</span>
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
                @php $order = $refund->order; @endphp
                {{-- Luôn có nút Xem --}}
                <a href="{{ route('admin.refunds.detail', $refund->id) }}" class="btn btn-info btn-sm">Xem</a>

                @if(!$refund->refund_completed_at && $order)
                    @if((int)$order->status === 11)
                        {{-- Đang yêu cầu hoàn hàng: duyệt hoàn hàng hoặc từ chối --}}
                        <form action="{{ route('admin.refunds.approveReturn', $refund->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Duyệt yêu cầu hoàn hàng?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Duyệt đơn hàng</button>
                        </form>
                        <form action="{{ route('admin.refunds.reject', ['id' => $refund->id]) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Từ chối yêu cầu này?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Từ chối</button>
                        </form>
                    @elseif(in_array((int)$order->status, [7,8]))
                        {{-- Đã duyệt hoàn hàng (7) hoặc đã nhận hàng hoàn (8): cho phép duyệt hoàn tiền --}}
                        <form action="{{ route('admin.refunds.approve', $refund->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Xác nhận đã hoàn tiền cho khách?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Duyệt hoàn</button>
                        </form>
                    @endif
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $refunds->links() }}
@endsection