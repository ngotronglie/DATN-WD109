  <!-- Header -->
  <header class="bg-dark shadow" style="padding: 0; min-height: 30px;">
      <div class="container-fluid d-flex align-items-center justify-content-between px-2">
          <!-- Logo -->
          <div class="logo">
              <a href="{{ route('home') }}">
                  <img src="{{ asset('frontend/img/logo/logo.png') }}" alt="Logo" height="25">
              </a>
          </div>
          <!-- Menu -->
          <nav class="d-none d-lg-block">
              <ul class="nav justify-content-center">
                  <li class="nav-item"><a class="nav-link" href="{{ route('home') }}" style="color: #ffffff !important;">Trang chủ</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ route('shop.index') }}" style="color: #ffffff !important;">Sản phẩm</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ route('blog') }}" style="color: #ffffff !important;">Bài viết</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}" style="color: #ffffff !important;">Liên hệ</a></li>
                  @auth
                  @if (Auth::user()->role_id == 2)
                  <li class="nav-item"><a class="nav-link" href="{{ route('admin.categories.index') }}" style="color: #ffffff !important;">Quản
                          trị</a></li>
                  @endif
                  @endauth
              </ul>
          </nav>
          <!-- Auth Links -->
          <div class="d-flex align-items-center gap-3">

              @auth
              <!-- Avatar dropdown -->
              <div class="dropdown">
                  <a href="#" class="d-flex align-items-center dropdown-toggle px-2 py-1" id="userDropdown"
                      data-bs-toggle="dropdown" aria-expanded="false">
                      <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('img/default-avatar.png') }}"
                          alt="Avatar" class="rounded-circle" width="40" height="40">
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                      <li>
                          <a class="dropdown-item py-3 px-4 fs-5" href="{{ route('account.edit') }}">
                              ⚙️ Tài khoản
                          </a>
                      </li>
                      <li>
                          <a class="dropdown-item py-3 px-4 fs-5" href="#"
                              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                              🔒 Đăng xuất
                          </a>
                      </li>
                  </ul>
              </div>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

              @else
              <a href="{{ route('auth.login') }}" class="fs-5">🔐 Đăng nhập</a>
              <a href="{{ route('auth.register') }}" class="fs-5">➕ Đăng ký</a>
              @endauth

              <!-- Icon Yêu thích -->
              <a href="{{ route('wishlist') }}" class="fs-4" title="Yêu thích">❤️</a>

              <!-- Icon Giỏ hàng -->
              <a href="{{ route('cart') }}" class="fs-3" title="Giỏ hàng">
                  <i class="zmdi zmdi-shopping-cart-plus"></i>
              </a>
          </div>
      </div>
  </header>

  <style>
/* ===== HEADER ===== */
header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: #121212;
    height: 70px; /* Tăng thêm chiều cao header */
    padding: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
}

/* Logo */
.logo img {
    height: 22px; /* Tăng thêm kích thước logo */
}

/* Menu chính */
.nav-link {
    font-family: 'Poppins', sans-serif;
    font-size: 15px !important;
    font-weight: 500 !important;
    color: #fff !important;
    padding: 0 14px !important;
    line-height: 60px !important;
    position: relative;
    transition: all 0.3s ease-in-out !important;
    overflow: hidden;
}

/* Hiệu ứng gạch chân khi hover */
.nav-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 15px;
    left: 50%;
    background-color: #00aaff;
    transition: all 0.3s ease-in-out;
    transform: translateX(-50%);
}

/* Hiệu ứng khi hover */
.nav-link:hover {
    color: #00aaff !important;
    transform: translateY(-1px);
}

.nav-link:hover::after {
    width: 60%;
}

/* Hiệu ứng active */
.nav-link.active {
    color: #00aaff !important;
}

.nav-link.active::after {
    width: 60%;
    background-color: #00aaff;
}

/* Auth links */
header .fs-5 {
    font-size: 14.5px !important;
    line-height: 60px; /* Căn giữa theo header mới */
    padding: 0 12px;
}

/* Icon */
header a[title="Yêu thích"],
header a[title="Giỏ hàng"] {
    font-size: 18px !important;
    line-height: 60px; /* Căn giữa theo header mới */
    padding: 0 10px;
    transition: all 0.2s ease;
}

header a[title="Yêu thích"]:hover,
header a[title="Giỏ hàng"]:hover {
    color: #00aaff !important;
    transform: scale(1.1);
}





  </style>