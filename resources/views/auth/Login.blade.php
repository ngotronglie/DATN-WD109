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
                <a href="{{ route('auth.login') }}">🔐 Đăng nhập</a>
                <a href="{{ route('auth.register') }}">➕ Đăng ký</a>
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
        <footer id="footer" class="footer-area footer-2 section">
            <div class="footer-top footer-top-2 text-center ptb-60">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="footer-logo">
                                <img src="img/logo/logo.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom footer-bottom-2 text-center gray-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-5">
                            <div class="copyright-text copyright-text-2">
                                <p class="copy-text"> WD_109 <strong>Web điện thoại</strong> </p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <ul class="footer-social footer-social-2">
                                <li>
                                    <a class="facebook" href="#" title="Facebook"><i
                                            class="zmdi zmdi-facebook"></i></a>
                                </li>
                                <li>
                                    <a class="google-plus" href="#" title="Google Plus"><i
                                            class="zmdi zmdi-google-plus"></i></a>
                                </li>
                                <li>
                                    <a class="twitter" href="#" title="Twitter"><i
                                            class="zmdi zmdi-twitter"></i></a>
                                </li>
                                <li>
                                    <a class="rss" href="#" title="RSS"><i class="zmdi zmdi-rss"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-3">
                            <ul class="footer-payment">
                                <li>
                                    <a href="#"><img src="img/payment/1.jpg" alt=""></a>
                                </li>
                                <li>
                                    <a href="#"><img src="img/payment/2.jpg" alt=""></a>
                                </li>
                                <li>
                                    <a href="#"><img src="img/payment/3.jpg" alt=""></a>
                                </li>
                                <li>
                                    <a href="#"><img src="img/payment/4.jpg" alt=""></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
</body>
</html>