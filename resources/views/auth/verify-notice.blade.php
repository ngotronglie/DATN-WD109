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

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('subas/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('subas/lib/css/nivo-slider.css') }}">
    <link rel="stylesheet" href="{{ asset('subas/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('subas/css/shortcode/shortcodes.css') }}">
    <link rel="stylesheet" href="{{ asset('subas/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('subas/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('subas/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('subas/css/style-customizer.css') }}">

    <!-- Modernizr -->
    <script src="{{ asset('subas/js/vendor/modernizr-3.11.2.min.js') }}"></script>

    <!-- Optional: custom styles -->
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="bg-white shadow py-3">
        <div class="container-fluid d-flex align-items-center justify-content-between px-4">
            <!-- Logo -->
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('frontend/img/logo/logo.png') }}" alt="Logo" height="40">
                </a>
            </div>

            <!-- Menu -->
            <nav class="d-none d-lg-block">
                <ul class="nav justify-content-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Pages</a></li>
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
                <a href="{{ route('auth.login') }}">🔐 Đăng nhập</a>
                <a href="{{ route('auth.register') }}">➕ Đăng ký</a>
                <a href="{{ route('wishlist') }}">❤️ Wishlist</a>
                <a href="#"><i class="zmdi zmdi-shopping-cart-plus"></i></a>
            </div>
        </div>
    </header>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 text-center p-4">
                <div class="card-body">
                    <h2 class="text-success mb-3">🎉 Đăng ký thành công!</h2>

                    <p class="fs-5">
                        Một liên kết xác minh đã được gửi tới email:
                        <strong>
                            <a href="https://mail.google.com/mail/u/{{ $user->email }}" target="_blank" class="text-decoration-none text-primary">
                                {{ $user->email }}
                            </a>
                        </strong>
                    </p>

                    <p class="mt-4">
                        <a href="{{ route('auth.login') }}" class="btn btn-primary rounded-pill px-4 py-2">
                            👉 Quay lại trang đăng nhập
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
    <footer id="footer" class="footer-area footer-2 section bg-light mt-auto">
        <div class="footer-top footer-top-2 text-center ptb-60"></div>
        <div class="footer-bottom footer-bottom-2 text-center gray-bg py-3">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 col-md-5 mb-2 mb-md-0">
                        <p class="mb-0">WD_109 <strong>Web điện thoại</strong></p>
                    </div>
                    <div class="col-lg-4 col-md-4 mb-2 mb-md-0">
                        <ul class="footer-social list-inline mb-0">
                            <li class="list-inline-item"><a class="text-dark" href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                            <li class="list-inline-item"><a class="text-dark" href="#"><i class="zmdi zmdi-google-plus"></i></a></li>
                            <li class="list-inline-item"><a class="text-dark" href="#"><i class="zmdi zmdi-twitter"></i></a></li>
                            <li class="list-inline-item"><a class="text-dark" href="#"><i class="zmdi zmdi-rss"></i></a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4 col-md-3">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><img src="{{ asset('img/payment/1.jpg') }}" alt="" height="30"></li>
                            <li class="list-inline-item"><img src="{{ asset('img/payment/2.jpg') }}" alt="" height="30"></li>
                            <li class="list-inline-item"><img src="{{ asset('img/payment/3.jpg') }}" alt="" height="30"></li>
                            <li class="list-inline-item"><img src="{{ asset('img/payment/4.jpg') }}" alt="" height="30"></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>