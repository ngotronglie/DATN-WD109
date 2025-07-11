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
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Sản phẩm</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Bài viết</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Trang</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">Liên hệ</a></li>
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

    <!-- Main Content -->
    <main>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h3 class="text-center mb-4">🔐 Quên mật khẩu</h3>

                            @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                            @endif

                            <form action="{{ route('password.email') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email của bạn</label>
                                    <input type="email" id="email" name="email" class="form-control rounded-pill px-4 py-3 border-2 shadow-sm" placeholder="Nhập email..." required>
                                    @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary rounded-pill ">
                                        📧 Gửi link đặt lại mật khẩu
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
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
