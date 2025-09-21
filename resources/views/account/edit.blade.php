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

        <!-- Nội dung cập nhật bên phải -->
        <div class="col-md-8">
            <div class="card p-4 shadow-sm border-0 rounded-4">
                <h4 class="mb-4 fw-bold">📝 Cập nhật thông tin tài khoản</h4>

                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name">Họ tên:</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}">
                    </div>

                    <div class="mb-3">
                        <label for="email">Email:</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="address">Địa chỉ:</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', Auth::user()->address) }}">
                    </div>

                    <div class="mb-3">
                        <label for="phone_number">Số điện thoại:</label>
                        <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', Auth::user()->phone_number) }}">
                    </div>

                    <div class="mb-4">
                        <label for="date_of_birth" class="form-label fs-5 fw-semibold">📅 Ngày sinh:</label>
                        <input type="date" name="date_of_birth" class="form-control form-control-lg"
                            value="{{ old('date_of_birth', Auth::user()->date_of_birth) }}">
                    </div>

                    <div class="mb-4">
                        <label for="avatar" class="form-label fs-5 fw-semibold">🖼️ Thay ảnh đại diện:</label>
                        <input type="file" name="avatar" class="form-control form-control-lg">
                    </div>


                    <!-- Nút lưu căn giữa -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-4 py-2 mx-auto d-block">💾 Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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