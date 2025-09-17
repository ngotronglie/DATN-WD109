  <!-- Header -->
  <header class="bg-white shadow py-0">
      <div class="container-fluid d-flex align-items-center justify-content-between px-4">
          <!-- Logo -->
          <div class="logo">
              <a href="{{ route('home') }}">
                  <img src="{{ asset('frontend/img/logo/logo.png') }}" alt="Logo" height="25">
              </a>
          </div>
          <!-- Menu -->
          <nav class="d-none d-lg-block">
              <ul class="nav justify-content-center">
                  <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ route('shop.index') }}">Sản phẩm</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ route('blog') }}">Bài viết</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Liên hệ</a></li>
                  @auth
                  @if (Auth::user()->role_id == 2)
                  <li class="nav-item"><a class="nav-link" href="{{ route('admin.categories.index') }}">Quản
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
    position: sticky; /* header dính khi scroll */
    top: 0;
    z-index: 1000;
    background: #fff;
}

/* Logo */
.logo img {
    height: 40px; /* logo cao hơn 1 chút */
    transition: transform 0.3s ease;
}
.logo img:hover {
    transform: scale(1.05);
}

/* Menu chính */
nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
}
nav .nav-link {
    color: #333;
    font-size: 16px;
    font-weight: 500;
    padding: 15px 20px;
    transition: color 0.3s, border-bottom 0.3s;
}
nav .nav-link:hover {
    color: #007bff;
    border-bottom: 2px solid #007bff;
}

/* Avatar dropdown */
.dropdown-toggle img {
    border: 2px solid #eee;
    transition: transform 0.2s ease;
}
.dropdown-toggle img:hover {
    transform: scale(1.1);
}

/* Dropdown menu */
.dropdown-menu {
    border-radius: 10px;
    padding: 0;
    overflow: hidden;
}
.dropdown-menu .dropdown-item {
    font-size: 15px;
    transition: background 0.2s;
}
.dropdown-menu .dropdown-item:hover {
    background: #f8f9fa;
}

/* Auth links */
header .fs-5 {
    color: #333;
    text-decoration: none;
    transition: color 0.2s;
}
header .fs-5:hover {
    color: #007bff;
}

/* Icon yêu thích & giỏ hàng */
header a[title="Yêu thích"],
header a[title="Giỏ hàng"] {
    color: #333;
    transition: color 0.3s;
}
header a[title="Yêu thích"]:hover {
    color: red;
}
header a[title="Giỏ hàng"]:hover {
    color: #007bff;
}

  </style>