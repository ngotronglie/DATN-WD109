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
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"> Pages</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
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