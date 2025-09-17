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
        
        <div class="col-md-8">
            <div class="card p-4 shadow-sm border-0 rounded-4">
                <h4 class="fw-bold mb-4">✏️ Sửa địa chỉ</h4>

                <form action="{{ route('account.address.update', $address->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="receiver_name" class="form-label">Tên người nhận</label>
                        <input type="text" class="form-control" name="receiver_name" value="{{ old('receiver_name', $address->receiver_name) }}">
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $address->phone) }}">
                    </div>

                    <div class="mb-3">
                        <label for="street" class="form-label">Địa chỉ (số nhà, tên đường)</label>
                        <input type="text" class="form-control" name="street" value="{{ old('street', $address->street) }}">
                    </div>

                    <div class="mb-3">
                        <label for="ward" class="form-label">Phường/Xã</label>
                        <input type="text" class="form-control" name="ward" value="{{ old('ward', $address->ward) }}">
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">Tỉnh/Thành phố</label>
                        <input type="text" class="form-control" name="city" value="{{ old('city', $address->city) }}">
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" name="is_default" {{ $address->is_default ? 'checked' : '' }}>
                        <label class="form-check-label">Đặt làm địa chỉ mặc định</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    <a href="{{ route('account.address_list') }}" class="btn btn-secondary ms-2">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
