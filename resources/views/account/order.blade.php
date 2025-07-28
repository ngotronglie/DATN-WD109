@extends('index.clientdashboard')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar trái -->
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
                        <li><a href="{{ route('account.edit') }}">⚙️ Thông tin cá nhân</a></li>
                        <li><a href="{{ route('account.order') }}" class="fw-bold text-primary">🛒 Đơn hàng</a></li>
                        <li><a href="{{ route('password.change') }}">🔑 Đổi mật khẩu</a></li>
                        <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                🔒 Đăng xuất
                            </a>
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
                                4 => 'Đã giao',
                                5 => 'Đã giao',
                                6 => 'Đã hủy',
                                7 => 'Hoàn hàng',
                                8 => 'Hoàn tiền ',
                                ];

                                $statusColors = [
                                0 => 'bg-warning text-dark', // Chờ xác nhận
                                1 => 'bg-info text-dark', // Đã xác nhận
                                2 => 'bg-primary text-white', // Đang chuẩn bị
                                3 => 'bg-primary text-white', // Đã đến tay shiper
                                4 => 'bg-success text-white', // Đã giao
                                5 => 'bg-success text-white', // Đã giao (trùng với 4)
                                6 => 'bg-danger text-white', // Đã hủy
                                7 => 'bg-secondary text-white', // Hoàn hàng
                                8 => 'bg-info text-white',
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
                                        @elseif ($order->status == 0)
                                        <form action="{{ route('order.cancel', $order->id) }}" method="POST">
                                            @csrf
                                            <select name="reason_select" class="form-control reason-select" required>
                                                <option value="">-- Chọn lý do --</option>
                                                <option value="Sản phẩm bị lỗi">Sản phẩm bị lỗi</option>
                                                <option value="Giao sai sản phẩm">Giao sai sản phẩm</option>
                                                <option value="Thay đổi nhu cầu">Thay đổi nhu cầu</option>
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