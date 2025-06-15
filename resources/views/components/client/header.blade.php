<header class="header-area header-wrapper">
    <!-- header-top-bar -->
    <div class="header-top-bar plr-185">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 d-none d-md-block">
                    <div class="call-us">
                        <p class="mb-0 roboto">0123456789</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="top-link clearfix">
                        <ul class="link f-right">
                            @auth
                            <li>
                                <i class="zmdi zmdi-account"></i>
                                Xin chào, {{ Auth::user()->name }}
                            </li>
                            <li>
                                <a href="{{ route('account') }}">
                                    <i class="zmdi zmdi-settings"></i>
                                    Tài khoản
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="zmdi zmdi-lock-outline"></i>
                                    Đăng xuất
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>

                            @if (Auth::user()->role_id == 2)
                            <li>
                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="zmdi zmdi-view-dashboard"></i>
                                    Admin Dashboard
                                </a>
                            </li>
                            @endif
                            @else
                            <li>
                                <a href="{{ route('login') }}">
                                    <i class="zmdi zmdi-lock"></i>
                                    Đăng nhập
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('register') }}">
                                    <i class="zmdi zmdi-account-add"></i>
                                    Đăng ký
                                </a>
                            </li>
                            @endauth

                            <li>
                                <a href="{{ route('wishlist') }}">
                                    <i class="zmdi zmdi-favorite"></i>
                                    Wish List (0)
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Phần header giữa (logo, menu, giỏ hàng...) giữ nguyên -->
</header>