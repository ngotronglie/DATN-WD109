          <!-- ========== App Menu Start ========== -->
          <div class="main-nav">
               <!-- Sidebar Logo -->
               <div class="logo-box">
                    <a href="index.html" class="logo-dark">
                         <img src="{{ asset('dashboard/assets/images/logo-sm.png') }}" class="logo-sm" alt="logo sm">
                         <img src="{{ asset('dashboard/assets/images/logo-dark.png') }}" class="logo-lg" alt="logo dark">
                    </a>

                    <a href="index.html" class="logo-light">
                         <img src="{{ asset('dashboard/assets/images/logo-sm.png') }}" class="logo-sm" alt="logo sm">
                         <img src="{{ asset('dashboard/assets/images/logo-light.png') }}" class="logo-lg" alt="logo light">
                    </a>
               </div>

               <!-- Menu Toggle Button (sm-hover) -->
               <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
                    <iconify-icon icon="solar:hamburger-menu-broken" class="button-sm-hover-icon"></iconify-icon>
               </button>

               <div class="scrollbar" data-simplebar>
                    <ul class="navbar-nav" id="navbar-nav">
                         <li class="menu-title">Menu</li>

                         <!-- Dashboard -->
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:home-2-broken"></iconify-icon>
                                   </span>
                                   <span class="nav-text">Trang Chủ</span>
                              </a>
                         </li>

                         <!-- User Management -->
                         <li class="nav-item">
                              <a class="nav-link menu-arrow" href="#sidebarUsers" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarUsers">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:users-group-rounded-broken"></iconify-icon>
                                   </span>
                                   <span class="nav-text">Quản Lý Người Dùng</span>
                              </a>
                              <div class="collapse" id="sidebarUsers">
                                   <ul class="nav sub-navbar-nav">
                                        <li class="sub-nav-item">
                                             <a class="sub-nav-link" href="{{ route('admin.users.index') }}">Người Dùng</a>
                                        </li>
                                        <li class="sub-nav-item">
                                             <a class="sub-nav-link" href="{{ route('admin.roles.index') }}">Vai Trò</a>
                                        </li>
                                   </ul>
                              </div>
                         </li>

                         <!-- Product Management -->
                         <li class="nav-item">
                              <a class="nav-link menu-arrow" href="#sidebarProducts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProducts">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:box-broken"></iconify-icon>
                                   </span>
                                   <span class="nav-text">Quản Lý Sản Phẩm</span>
                              </a>
                              <div class="collapse" id="sidebarProducts">
                                   <ul class="nav sub-navbar-nav">
                                        <li class="sub-nav-item">
                                             <a class="sub-nav-link" href="{{ route('admin.products.index') }}">Sản Phẩm</a>
                                        </li>
                                        <li class="sub-nav-item">
                                             <a class="sub-nav-link" href="{{ route('admin.categories.index') }}">Danh Mục</a>
                                        </li>
                                        <li class="sub-nav-item">
                                             <a class="sub-nav-link" href="{{ route('admin.product-variants.index') }}">Biến Thể Sản Phẩm</a>
                                        </li>
                                        <li class="sub-nav-item">
                                             <a class="sub-nav-link" href="{{ route('admin.colors.index') }}">Màu Sắc</a>
                                        </li>
                                        <li class="sub-nav-item">
                                             <a class="sub-nav-link" href="{{ route('admin.capacities.index') }}">Dung Lượng</a>
                                        </li>
                                   </ul>
                              </div>
                         </li>

                         <!-- Order Management -->
                         <li class="nav-item">
                              <a class="nav-link menu-arrow" href="#sidebarOrders" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarOrders">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:cart-3-broken"></iconify-icon>
                                   </span>
                                   <span class="nav-text">Quản Lý Đơn Hàng</span>
                              </a>
                              <div class="collapse" id="sidebarOrders">
                                   <ul class="nav sub-navbar-nav">
                                        <li class="sub-nav-item">
                                             <a class="sub-nav-link" href="{{ route('admin.orders.index') }}">Đơn Hàng</a>
                                        </li>
                                        <li class="sub-nav-item">
                                             <a class="sub-nav-link" href="{{ route('admin.carts.index') }}">Giỏ Hàng</a>
                                        </li>
                                   </ul>
                              </div>
                         </li>

                         <!-- Content Management -->
                         <li class="nav-item">
                              <a class="nav-link menu-arrow" href="#sidebarContent" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarContent">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:document-broken"></iconify-icon>
                                   </span>
                                   <span class="nav-text">Quản Lý Nội Dung</span>
                              </a>
                              <div class="collapse" id="sidebarContent">
                                   <ul class="nav sub-navbar-nav">
                                        <li class="sub-nav-item">
                                             <a class="sub-nav-link" href="{{ route('admin.blogs.index') }}">Bài Viết</a>
                                        </li>
                                        <li class="sub-nav-item">
                                             <a class="sub-nav-link" href="{{ route('admin.tags.index') }}">Thẻ Bài Viết</a>
                                        </li>
                                        <li class="sub-nav-item">
                                             <a class="sub-nav-link" href="{{ route('admin.comments.index') }}">Bình Luận</a>
                                        </li>
                                        <li class="sub-nav-item">
                                             <a class="sub-nav-link" href="{{ route('admin.banners.index') }}">Banner</a>
                                        </li>
                                   </ul>
                              </div>
                         </li>

                         <!-- Marketing -->
                         <li class="nav-item">
                              <a class="nav-link menu-arrow" href="#sidebarMarketing" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMarketing">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:gift-broken"></iconify-icon>
                                   </span>
                                   <span class="nav-text">Marketing</span>
                              </a>
                              <div class="collapse" id="sidebarMarketing">
                                   <ul class="nav sub-navbar-nav">
                                        <li class="sub-nav-item">
                                             <a class="sub-nav-link" href="{{ route('admin.vouchers.index') }}">Mã Giảm Giá</a>
                                        </li>
                                        <li class="sub-nav-item">
                                             <a class="sub-nav-link" href="{{ route('admin.favorites.index') }}">Sản Phẩm Yêu Thích</a>
                                        </li>
                                   </ul>
                              </div>
                         </li>

                         <!-- Settings -->
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.settings.index') }}">
                                   <span class="nav-icon">
                                        <iconify-icon icon="solar:settings-broken"></iconify-icon>
                                   </span>
                                   <span class="nav-text">Cài Đặt</span>
                              </a>
                         </li>
                    </ul>
               </div>
          </div>
          <!-- ========== App Menu End ========== -->
