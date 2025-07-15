@extends('index.clientdashboard')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar bên trái -->
        <div class="col-md-4">
            <div class="card mb-4 text-center">
                <div class="card-body">
                    <h5 class="mb-3">Tài khoản của tôi</h5>
                    <div class="mb-3">
                        @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="rounded-circle img-thumbnail" width="150" height="150" alt="Avatar">
                        @else
                        <img src="{{ asset('img/default-avatar.png') }}" class="rounded-circle img-thumbnail" width="150" height="150" alt="Avatar">
                        @endif
                    </div>
                    <p><strong>{{ Auth::user()->name }}</strong></p>
                    <hr>
                    <ul class="list-unstyled text-start">
                        <li><a href="{{ route('account.edit') }}">⚙️ Thông tin cá nhân</a></li>
                        <li><a href="{{ route('password.change') }}">🔑 Đổi mật khẩu</a></li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                🔒 Đăng xuất
                            </a>
                        </li>
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </div>
        </div>

        <!-- Nội dung đổi mật khẩu bên phải -->
        <div class="col-md-8">
            <div class="card p-4 shadow-sm">
                <h4 class="mb-4 text-primary fw-bold">🔑 Đổi mật khẩu</h4>

                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-medium">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" id="current_password"
                            class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-medium">Mật khẩu mới</label>
                        <input type="password" name="new_password" id="new_password"
                            class="form-control @error('new_password') is-invalid @enderror" required>
                        @error('new_password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label fw-medium">Xác nhận mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-4 py-2 mx-auto d-block">Cập nhật mật khẩu</button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@section('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
    }

    label {
        font-weight: 500;
    }

    .form-control {
        border-radius: 8px;
        padding: 10px 14px;
    }

    .btn-primary {
        border-radius: 8px;
        padding: 10px 25px;
        font-weight: 500;
    }

    .alert-success {
        border-radius: 8px;
    }
</style>
@endsection


@endsection