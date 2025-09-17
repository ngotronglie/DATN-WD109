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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold"><i class="fa-solid fa-location-dot me-2"></i>Danh sách địa chỉ</h4>
                    <a href="{{ route('account.address.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Thêm địa chỉ mới</a>
                </div>

                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @forelse($addresses as $address)
                <div class="border rounded-3 p-3 mb-3 @if($address->is_default) border-primary @endif">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-bold mb-1">{{ $address->receiver_name }} @if($address->is_default)<span class="badge bg-primary">Mặc định</span>@endif</p>
                            <p class="mb-1">📞 {{ $address->phone }}</p>
                            <p class="mb-0">📍 {{ $address->street }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->city }}</p>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('account.address_edit', $address->id) }}" class="btn btn-sm btn-outline-primary me-1">Sửa</a>
                            <form action="{{ route('account.address.delete', $address->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">Xóa</button>
                            </form>
                            @if(!$address->is_default)
                            <form action="{{ route('account.address.setDefault', $address->id) }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">Đặt làm mặc định</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted">Bạn chưa có địa chỉ nào. Hãy thêm địa chỉ mới.</p>
                @endforelse
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

    .btn-success {
        border-radius: 8px;
        font-weight: 600;
    }

    .border-primary {
        border: 2px solid #0d6efd !important;
    }
</style>
@endsection
@endsection
