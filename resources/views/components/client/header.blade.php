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