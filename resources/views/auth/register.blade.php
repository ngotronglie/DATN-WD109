<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $title ?? 'E-commerce' }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('subas/img/icon/favicon.png') }}">

    <!-- All CSS Files -->
    <link rel="stylesheet" href="{{ asset('subas/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('subas/lib/css/nivo-slider.css') }}">
    <link rel="stylesheet" href="{{ asset('subas/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('subas/css/shortcode/shortcodes.css') }}">
    <link rel="stylesheet" href="{{ asset('subas/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('subas/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('subas/css/custom.css') }}">

    <!-- Style Customizer (nên xoá nếu không cần dùng) -->
    <link rel="stylesheet" href="{{ asset('subas/css/style-customizer.css') }}">
    <link href="#" data-style="styles" rel="stylesheet">

    <!-- Modernizr JS -->
    <script src="{{ asset('subas/js/vendor/modernizr-3.11.2.min.js') }}"></script>
</head>

<body>
    <!-- Header -->
    <header class="bg-white shadow py-3">
    <div class="container-fluid d-flex align-items-center justify-content-between px-4">
        <!-- Logo -->
        <div class="logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('img/logo/logo.png') }}" alt="Logo" height="40">
            </a>
        </div>

        <!-- Menu -->
        <nav class="d-none d-lg-block">
            <ul class="nav justify-content-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Sản phẩm</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Giới thiệu</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Liên hệ</a></li>
                @auth
                    @if (Auth::user()->role_id == 2)
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.categories.index') }}">Quản trị</a></li>
                    @endif
                @endauth
            </ul>
        </nav>

        <!-- Auth Links -->
        <div class="d-flex align-items-center gap-3">
            @auth
                <span>👤 {{ Auth::user()->name }}</span>
                <a href="{{ route('account') }}">⚙️ Tài khoản</a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🔒 Đăng xuất</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            @else
                <a href="{{ route('auth.login') }}">🔐 Đăng nhập</a>
                <a href="{{ route('auth.register') }}">➕ Đăng ký</a>
            @endauth
            <a href="{{ route('wishlist') }}">❤️ Wishlist</a>
            <a href="#"><i class="zmdi zmdi-shopping-cart-plus"></i></a>
        </div>
    </div>
</header>

    <!-- Form đăng ký -->
<!-- Form đăng ký -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Đăng ký tài khoản</h2>
            <form method="POST" action="{{ route('auth.register') }}">
                @csrf

                <div class="form-floating mb-3">
                    <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Họ tên">
                    <label for="name">Họ tên</label>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required placeholder="Email">
                    <label for="email">Email</label>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="password" class="form-control" type="password" name="password" required placeholder="Mật khẩu">
                    <label for="password">Mật khẩu</label>
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required placeholder="Xác nhận mật khẩu">
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="address" class="form-control" type="text" name="address" value="{{ old('address') }}" placeholder="Địa chỉ">
                    <label for="address">Địa chỉ</label>
                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="phone_number" class="form-control" type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="Số điện thoại">
                    <label for="phone_number">Số điện thoại</label>
                    @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-floating mb-3">
                    <input id="date_of_birth" class="form-control" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" placeholder="Ngày sinh">
                    <label for="date_of_birth">Ngày sinh</label>
                    @error('date_of_birth') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <input type="hidden" name="role" value="user">

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a class="text-decoration-none" href="{{ route('auth.login') }}">Đã có tài khoản?</a>
                    <button type="submit" class="btn btn-primary px-4">Đăng ký</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>