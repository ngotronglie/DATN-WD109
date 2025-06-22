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


    <!-- Main Content -->
<main class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 bg-white p-5 shadow rounded">
                <h3 class="mb-4 text-center">Đăng nhập</h3>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('login.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Mật khẩu</label>
                        <input type="password" class="form-control" name="password" required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">Đăng nhập</button>
                    </div>

                    <div class="text-center">
                        <p>Bạn chưa có tài khoản? <a href="{{ route('auth.register') }}">Đăng ký</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

</body>
</html>