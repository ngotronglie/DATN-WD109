@extends('index.clientdashboard')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar bên trái -->
        <div class="col-md-4">
            <div class="card mb-4 text-center shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h5 class="mb-3 fw-bold">Tài khoản của tôi</h5>
                    <div class="mb-3">
                        @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="rounded-circle img-thumbnail" width="150" height="150" alt="Avatar">
                        @else
                        <img src="{{ asset('img/default-avatar.png') }}" class="rounded-circle img-thumbnail" width="150" height="150" alt="Avatar">
                        @endif
                    </div>
                    <p class="fw-semibold">{{ Auth::user()->name }}</p>
                    <hr>
                    <ul class="list-unstyled text-start">
                        <li class="mb-2">
                            <a href="{{ route('account.edit') }}" class="d-block px-3 py-2 rounded text-decoration-none @if(request()->routeIs('account.edit')) bg-light fw-bold @endif">
                                ⚙️ Thông tin cá nhân
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('account.address_list') }}" class="d-block px-3 py-2 rounded text-decoration-none @if(request()->routeIs('account.address_list')) bg-light fw-bold text-primary @endif">
                                <i class="fa-solid fa-location-dot me-2"></i> Địa chỉ
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('account.order') }}" class="d-block px-3 py-2 rounded text-decoration-none @if(request()->routeIs('account.order')) bg-light fw-bold text-primary @endif">
                                🛒 Đơn hàng
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('password.change') }}" class="d-block px-3 py-2 rounded text-decoration-none @if(request()->routeIs('password.change')) bg-light fw-bold @endif">
                                🔑 Đổi mật khẩu
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="d-block px-3 py-2 rounded text-decoration-none text-danger">
                                🔒 Đăng xuất
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </li>
                    </ul>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </div>
        </div>

        <!-- Nội dung bên phải -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-4">Đơn hàng của bạn</h4>

                    <div class="table-responsive">
                        <table class="table table-bordered rounded">
                            <thead class="table-light">
                                <tr class="text-center align-middle">
                                    <th>Mã đơn</th>
                                    <th>Ngày đặt</th>
                                    <th>Trạng thái</th>
                                    <th>Yêu cầu</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                @php
                                $statusLabels = [
                                0 => 'Chờ xác nhận',
                                1 => 'Đã xác nhận',
                                2 => 'Đang chuẩn bị',
                                3 => 'Đã đến tay shiper',
                                4 => 'đang giao đến',
                                5 => 'Đã giao',
                                6 => 'Đã hủy',
                                7 => 'xác nhận yêu cầu Hoàn hàng',
                                8 => 'Hoàn hàng',
                                9 => 'Hoàn tiền thành công',
                                10 => 'không xác nhận yêu cầu hoàn hàng',
                                ];
                                $statusColors = [
                                0 => 'bg-warning text-dark', // Chờ xác nhận
                                1 => 'bg-info text-dark', // Đã xác nhận
                                2 => 'bg-primary text-white', // Đang chuẩn bị
                                3 => 'bg-primary text-white', // Đã đến tay shiper
                                4 => 'bg-warning text-dark', // Đang giao đến
                                5 => 'bg-success text-white', // Đã giao
                                6 => 'bg-danger text-white', // Đã hủy
                                7 => 'bg-secondary text-white', // Xác nhận yêu cầu hoàn hàng
                                8 => 'bg-info text-white', // Hoàn hàng
                                9 => 'bg-success text-dark', // Hoàn tiền
                                10 => 'bg-dark text-white', // Không xác nhận yêu cầu hoàn hàng
                                ];

                                $status = $order->status;
                                $statusText = $statusLabels[$status] ?? 'Không xác định';
                                $badgeClass = $statusColors[$status] ?? 'bg-light text-dark';
                                @endphp
                                <tr class="text-center align-middle">
                                    <td>
                                        <a href="{{ route('user.orders.show', $order->id) }}"
                                            class="text-decoration-underline text-primary fw-semibold">
                                            {{ $order->order_code }}
                                        </a>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge {{ $badgeClass }} px-3 py-1">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($order->return_requested)
                                        <span class="text-warning fw-semibold">Đã yêu cầu</span>

                                        @elseif(in_array($order->status, [0,1]))

                                        <form action="{{ route('order.cancel', $order->id) }}" method="POST">
                                            @csrf
                                            <select name="reason_select" class="form-control reason-select" required>
                                                <option value="">-- Chọn lý do --</option>
                                                <option value="Đổi địa chỉ nhận hàng">Đổi địa chỉ nhận hàng</option>
                                                <option value="Thời gian giao hàng quá lâu">Thời gian giao hàng quá lâu</option>
                                                <option value="Không còn nhu cầu sử dụng">Không còn nhu cầu sử dụng</option>
                                                <option value="Tìm được giá tốt hơn">Tìm được giá tốt hơn</option>
                                                <option value="Khác">Khác</option>
                                            </select>

                                            <input type="text" name="reason_input" class="form-control mt-2 reason-input" placeholder="Nhập lý do khác nếu chọn 'Khác'" style="display: none;">

                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Hủy đơn
                                            </button>

                                        </form>

                                        @else
                                        <span class="text-muted fst-italic">Không thể thực hiện</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ((int)$order->status === 6 && strtolower((string)$order->payment_method) === 'vnpay')
                                        <form action="{{ route('user.orders.reorder', $order->id) }}" method="POST" onsubmit="return confirm('Thêm lại các sản phẩm vào giỏ hàng?')">
                                            @csrf
                                            <button class="btn btn-sm btn-primary">Đặt lại hàng</button>
                                        </form>
                                        @else
                                        <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Bạn chưa có đơn hàng nào.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reasonSelects = document.querySelectorAll('.reason-select');

        reasonSelects.forEach(function(select) {
            select.addEventListener('change', function() {
                const input = this.closest('form').querySelector('.reason-input');
                if (this.value === 'Khác') {
                    input.style.display = 'block';
                } else {
                    input.style.display = 'none';
                    input.value = '';
                }
            });
        });
    });
</script>


@endsection