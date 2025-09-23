@extends('index.clientdashboard')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar bên trái -->
        <div class="col-md-4">
            <div class="card mb-4 text-center shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h5 class="mb-3 fw-bold">👤 Tài khoản của tôi</h5>
                    <div class="mb-3">
                        @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                            class="rounded-circle img-thumbnail shadow-sm"
                            width="120" height="120" alt="Avatar">
                        @else
                        <img src="{{ asset('img/default-avatar.png') }}"
                            class="rounded-circle img-thumbnail shadow-sm"
                            width="120" height="120" alt="Avatar">
                        @endif
                    </div>
                    <p class="fw-semibold">{{ Auth::user()->name }}</p>
                    <hr>
                    <ul class="list-unstyled text-start">
                        <li class="mb-2">
                            <a href="{{ route('account.edit') }}"
                                class="d-block px-3 py-2 rounded text-decoration-none @if(request()->routeIs('account.edit')) bg-primary text-white fw-bold @endif">
                                ⚙️ Thông tin cá nhân
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('account.address_list') }}"
                                class="d-block px-3 py-2 rounded text-decoration-none @if(request()->routeIs('account.address_list')) bg-primary text-white fw-bold @endif">
                                📍 Địa chỉ
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('account.order') }}"
                                class="d-block px-3 py-2 rounded text-decoration-none @if(request()->routeIs('account.order')) bg-primary text-white fw-bold @endif">
                                🛒 Đơn hàng
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('password.change') }}"
                                class="d-block px-3 py-2 rounded text-decoration-none @if(request()->routeIs('password.change')) bg-primary text-white fw-bold @endif">
                                🔑 Đổi mật khẩu
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="d-block px-3 py-2 rounded text-decoration-none text-danger fw-bold">
                                🔒 Đăng xuất
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Nội dung bên phải -->
        <div class="col-md-8">
            <div class="card p-4 shadow-sm border-0 rounded-4">
                <h4 class="mb-4 fw-bold"><i class="fa-solid fa-location-dot me-2"></i>Thêm địa chỉ mới</h4>

                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('account.address.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="receiver_name">Tên người nhận:</label>
                        <input type="text" name="receiver_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone">Số điện thoại:</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="street">Số nhà, tên đường:</label>
                        <input type="text" name="street" class="form-control" required>
                    </div>
                    

                    <div class="mb-3">
                        <label for="ward">Phường/Xã:</label>
                        <select name="ward" id="ward" class="form-select" required>
                            <option value="">-- Chọn phường/xã --</option>
                        </select>
                    </div>
                                        <div class="mb-3">
                        <label for="district">Quận huyện:</label>
                        <input type="text" name="district" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="city">Tỉnh/Thành phố:</label>
                        <select name="city" id="province" class="form-select" required>
                            <option value="">-- Chọn tỉnh --</option>
                            @foreach($provinces as $province)
                            <option value="{{ $province->ten_tinh }}" data-id="{{ $province->id }}">{{ $province->ten_tinh }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="is_default">
                        <label class="form-check-label" for="is_default">
                            Đặt làm địa chỉ mặc định
                        </label>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-4 py-2">💾 Lưu địa chỉ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const provinceSelect = document.getElementById('province');
        const wardSelect = document.getElementById('ward');

        provinceSelect.addEventListener('change', function () {
            const provinceId = this.options[this.selectedIndex].getAttribute('data-id');

            fetch(`/address/wards/${provinceId}`)
                .then(res => res.json())
                .then(data => {
                    wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
                    data.forEach(function (ward) {
                        wardSelect.innerHTML += `<option value="${ward.ten_phuong_xa}">${ward.ten_phuong_xa}</option>`;
                    });
                });
        });
    });
</script>


@section('styles')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    label {
        font-weight: 600;
    }

    .btn-primary {
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
    }

    .alert-success {
        font-weight: 500;
        border-radius: 8px;
    }
</style>
@endsection
@endsection