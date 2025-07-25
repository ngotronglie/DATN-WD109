@extends('index.clientdashboard')

@section('content')

    <!-- Form đăng ký -->
    <!-- Form đăng ký -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4 fw-bold">📝 Đăng ký tài khoản</h2>

            <form method="POST" action="{{ route('auth.register') }}">
                @csrf

                <div class="form-floating mb-3">
                    <input id="name" class="form-control rounded-pill" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Họ tên">
                    <label for="name">Họ tên</label>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="email" class="form-control rounded-pill" type="email" name="email" value="{{ old('email') }}" required placeholder="Email">
                    <label for="email">Email</label>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="password" class="form-control rounded-pill" type="password" name="password" required placeholder="Mật khẩu">
                    <label for="password">Mật khẩu</label>
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="password_confirmation" class="form-control rounded-pill" type="password" name="password_confirmation" required placeholder="Xác nhận mật khẩu">
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="address" class="form-control rounded-pill" type="text" name="address" value="{{ old('address') }}" placeholder="Địa chỉ">
                    <label for="address">Địa chỉ</label>
                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="phone_number" class="form-control rounded-pill" type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="Số điện thoại">
                    <label for="phone_number">Số điện thoại</label>
                    @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="date_of_birth" class="form-control rounded-pill" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" placeholder="Ngày sinh">
                    <label for="date_of_birth">Ngày sinh</label>
                    @error('date_of_birth') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <input type="hidden" name="role" value="user">

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a class="text-decoration-none" href="{{ route('auth.login') }}">🔑 Đã có tài khoản?</a>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">🚀 Đăng ký</button>
                </div>
            </form>

            @if(session('verify_notice'))
            <div class="alert alert-info mt-4">
                ✅ Đăng ký thành công. Một liên kết xác minh đã được gửi tới <strong>{{ session('verify_notice') }}</strong>.
            </div>
            @endif
        </div>
    </div>
</div>

@endsection