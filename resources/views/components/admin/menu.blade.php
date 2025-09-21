          <!-- ========== App Menu Start ========== -->
          <div class="main-nav">
               <!-- Sidebar Logo -->
               <div class="logo-box">
                    <a href="{{ route('admin.dashboard') }}" class="logo-dark d-flex align-items-center text-decoration-none">
                         <span class="brand-text fw-bold fs-5">TechZone</span>
                    </a>
               </div>

               <!-- Menu Toggle Button (sm-hover) -->
               <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
                    <iconify-icon icon="solar:hamburger-menu-broken" class="button-sm-hover-icon"></iconify-icon>
               </button>

               <div class="scrollbar" data-simplebar>

                    <ul class="navbar-nav" id="navbar-nav">
                         <li class="menu-title">Tổng quan</li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:home-2-broken"></iconify-icon></span>
                                   <span class="nav-text">Trang chủ</span>
                              </a>
                         </li>

                         <li class="menu-title">Quản lý bán hàng</li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.orders.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:cart-large-minimalistic-broken"></iconify-icon></span>
                                   <span class="nav-text">Đơn hàng</span>
                              </a>
                         </li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.vouchers.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:ticket-broken"></iconify-icon></span>
                                   <span class="nav-text">Mã giảm giá</span>
                              </a>
                         </li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.flash-sales.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:fire-minimalistic-broken"></iconify-icon></span>
                                   <span class="nav-text">Flash Sale</span>
                              </a>
                         </li>

                         <li class="menu-title">Quản lý sản phẩm</li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.products.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:box-minimalistic-broken"></iconify-icon></span>
                                   <span class="nav-text">Sản phẩm</span>
                              </a>
                         </li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.categories.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:category-broken"></iconify-icon></span>
                                   <span class="nav-text">Danh mục</span>
                              </a>
                         </li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.product_variants.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:layers-minimalistic-broken"></iconify-icon></span>
                                   <span class="nav-text">Biến thể sản phẩm</span>
                              </a>
                         </li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.colors.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:palette-round-broken"></iconify-icon></span>
                                   <span class="nav-text">Màu sắc</span>
                              </a>
                         </li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.capacities.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:battery-minimalistic-broken"></iconify-icon></span>
                                   <span class="nav-text">Dung lượng</span>
                              </a>
                         </li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.comments.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:chat-round-dots-broken"></iconify-icon></span>
                                   <span class="nav-text">Bình luận</span>
                              </a>
                         </li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.favorites.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:heart-broken"></iconify-icon></span>
                                   <span class="nav-text">Yêu thích</span>
                              </a>
                         </li>

                         <li class="menu-title">Thống kê</li>
                         <li class="nav-item">
                             <a class="nav-link menu-arrow" href="#sidebarThongKe" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarThongKe">
                                 <span class="nav-icon"><iconify-icon icon="solar:chart-square-broken"></iconify-icon></span>
                                 <span class="nav-text">Thống kê</span>
                             </a>
                             <div class="collapse" id="sidebarThongKe">
                                 <ul class="nav sub-navbar-nav">
                                     <li class="sub-nav-item">
                                         <a class="sub-nav-link" href="{{ route('admin.thongke.sanpham') }}">Sản phẩm</a>
                                     </li>
                                     <li class="sub-nav-item">
                                         <a class="sub-nav-link" href="{{ route('admin.thongke.donhang') }}">Đơn hàng</a>
                                     </li>
                                     <li class="sub-nav-item">
                                         <a class="sub-nav-link" href="{{ route('admin.thongke.nguoidung') }}">Người dùng</a>
                                     </li>
                                    
                                     
                                 </ul>
                             </div>
                         </li>

                         <li class="menu-title">Quản lý blog</li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.blogs.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:document-text-broken"></iconify-icon></span>
                                   <span class="nav-text">Bài viết</span>
                              </a>
                         </li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.tag_blogs.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:tag-broken"></iconify-icon></span>
                                   <span class="nav-text">Thẻ blog</span>
                              </a>
                         </li>

                         <li class="menu-title">Quản lý hệ thống</li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.users.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:users-group-rounded-broken"></iconify-icon></span>
                                   <span class="nav-text">Người dùng</span>
                              </a>
                         </li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.roles.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:shield-user-broken"></iconify-icon></span>
                                   <span class="nav-text">Vai trò</span>
                              </a>
                         </li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.banners.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:gallery-broken"></iconify-icon></span>
                                   <span class="nav-text">Banner</span>
                              </a>
                         </li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('admin.contacts.index') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:mailbox-broken"></iconify-icon></span>
                                   <span class="nav-text">Liên hệ</span>
                              </a>
                         </li>
                         <li class="nav-item">
                              <a class="nav-link" href="{{ route('home') }}">
                                   <span class="nav-icon"><iconify-icon icon="solar:home-2-bold"></iconify-icon></span>
                                   <span class="nav-text">Trang chủ</span>
                              </a>
                         </li>

                    </ul>
               </div>
          </div>
          <!-- ========== App Menu End ========== -->