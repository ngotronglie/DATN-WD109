@extends('index.clientdashboard')

@section('content')

        <!-- Shopee-style Breadcrumbs -->
        <div class="shopee-breadcrumbs">
                <div class="container">
                <div class="breadcrumb-nav">
                    <a href="{{ route('home') }}" class="breadcrumb-link">
                        <i class="zmdi zmdi-home"></i>
                        Trang chủ
                    </a>
                    <i class="zmdi zmdi-chevron-right breadcrumb-arrow"></i>
                    <span class="breadcrumb-current">Cửa hàng</span>
                </div>
            </div>
        </div>

        <!-- Main Shop Section -->
        <section class="shop-section">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-9 order-lg-2 order-1">
                            <div class="shop-content">

                            <!-- Products Grid -->
                            <div class="products-container">
                                <div class="row" id="products-grid">
                                            @foreach($products as $product)
                                                @php
                                                    $variant = $product->mainVariant;
                                                @endphp
                                        <div class="col-lg-4 col-md-6 mb-4">
                                            <div class="product-card">
                                                <div class="product-image-container">
                                                    @php
                                                        // Kiểm tra flash sale cho sản phẩm
                                                        $flashSaleProduct = null;
                                                        if ($variant) {
                                                            $flashSaleProduct = \App\Models\FlashSaleProduct::whereHas('flashSale', function($query) {
                                                                $query->where('is_active', true)
                                                                      ->where('start_time', '<=', now())
                                                                      ->where('end_time', '>', now());
                                                            })
                                                            ->where('product_variant_id', $variant->id)
                                                            ->where('remaining_stock', '>', 0)
                                                            ->with('flashSale')
                                                            ->first();
                                                        }
                                                    @endphp
                                                    <a href="{{ $flashSaleProduct ? route('flash-sale.product.detail', $product->slug) : route('product.detail', $product->slug) }}">
                                                        @if($variant && $variant->image)
                                                            <img src="{{ str_starts_with($variant->image, 'http') ? $variant->image : asset('storage/' . $variant->image) }}"
                                                                 alt="{{ $product->name }}"
                                                                 class="product-image"
                                                                 onerror="this.src='{{ asset('images/no-image.png') }}'">
                                                        @else
                                                            <img src="{{ asset('images/no-image.png') }}" alt="{{ $product->name }}" class="product-image">
                                                        @endif
                                                    </a>

                                                    @if($flashSaleProduct)
                                                        <div class="flash-sale-badge">
                                                            <i class="zmdi zmdi-flash"></i>
                                                            <span>FLASH SALE</span>
                                                        </div>
                                                        <div class="flash-sale-timer" data-end-time="{{ $flashSaleProduct->flashSale->end_time->format('Y-m-d H:i:s') }}">
                                                            <i class="zmdi zmdi-time"></i>
                                                            <span class="timer-text">00:00:00</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="product-content">
                                                    <h5 class="product-title text-center">
                                                                <a href="{{ $flashSaleProduct ? route('flash-sale.product.detail', $product->slug) : route('product.detail', $product->slug) }}">{{ $product->name }}</a>
                                                    </h5>

                                                    <!-- Price -->
                                                    <div class="price-info text-center">
                                                        @if($variant)
                                                            {{-- Sử dụng $flashSaleProduct đã được kiểm tra ở trên --}}

                                                            @if($flashSaleProduct)
                                                                <div class="flash-sale-price-container">
                                                                    <span class="flash-sale-price">₫{{ number_format($flashSaleProduct->sale_price, 0, ',', '.') }}</span>
                                                                    <span class="flash-sale-discount-inline">-{{ round((($flashSaleProduct->original_price - $flashSaleProduct->sale_price) / $flashSaleProduct->original_price) * 100) }}%</span>
                                                                </div>
                                                                <div class="original-price">₫{{ number_format($flashSaleProduct->original_price, 0, ',', '.') }}</div>
                                                            @elseif($variant->price_sale && $variant->price_sale < $variant->price)
                                                                <!-- Có giá khuyến mãi -->
                                                                <div class="current-price">₫{{ number_format($variant->price_sale, 0, ',', '.') }}</div>
                                                                <div class="original-price">₫{{ number_format($variant->price, 0, ',', '.') }}</div>
                                                            @else
                                                                <!-- Giá thường -->
                                                                <div class="current-price">₫{{ number_format($variant->price, 0, ',', '.') }}</div>
                                                            @endif
                                                        @else
                                                            <div class="current-price">Chưa có giá</div>
                                                        @endif
                                                    </div>

                                                    <!-- Action Buttons -->
                                                    <div class="product-actions">
                                                        <button class="action-btn add-to-favorite" data-product-id="{{ $product->id }}" title="Thêm vào yêu thích" onclick="addToFavorite(event, {{ $product->id }}); return false;">
                                                            <i class="zmdi zmdi-favorite"></i>
                                                        </button>
                                                        {{-- Sử dụng $flashSaleProduct đã được kiểm tra ở trên --}}
                                                        @if($flashSaleProduct)
                                                            <button class="action-btn add-to-cart" title="Chọn biến thể" onclick="goToFlashSaleDetail('{{ $product->slug }}'); return false;">
                                                                <i class="zmdi zmdi-shopping-cart"></i>
                                                            </button>
                                                        @else
                                                            <button class="action-btn add-to-cart" title="Chọn biến thể" onclick="goToProductDetail('{{ $product->slug }}'); return false;">
                                                                <i class="zmdi zmdi-shopping-cart"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Pagination -->
                            <div class="pagination-container mt-4">
                                {{ $products->links('pagination::bootstrap-4') }}
                            </div>
                            </div>
                        </div>
                        <div class="col-lg-3 order-lg-1 order-2">
                            <!-- Sidebar Filters -->
                            <div class="shop-sidebar">
                                <!-- Categories Filter -->
                                <div class="filter-widget">
                                    <h6 class="filter-title">Danh mục</h6>
                                    <div class="filter-content">
                                        <ul class="category-list">
                                            @foreach($allCategories as $cat)
                                                <li class="category-item">
                                                    <a href="{{ url('shop') . '?category=' . $cat->ID }}"
                                                       class="category-link {{ request('category') == $cat->ID ? 'active' : '' }}">
                                                        {{ $cat->Name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <!-- Price Filter -->
                                <div class="filter-widget">
                                    <h6 class="filter-title">Khoảng giá</h6>
                                    <div class="filter-content">
                                        <form method="GET" action="{{ route('shop.index') }}" class="price-filter-form">
                                            <div class="price-inputs">
                                                <input type="number" name="min_price" class="form-control"
                                                       placeholder="Từ" value="{{ request('min_price') }}" min="0">
                                                <span class="price-separator">-</span>
                                                <input type="number" name="max_price" class="form-control"
                                                       placeholder="Đến" value="{{ request('max_price') }}" min="0">
                                        </div>
                                            @foreach(request()->except('min_price', 'max_price', 'page') as $key => $value)
                                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                            @endforeach
                                            <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">Áp dụng</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Recent Products -->
                                <div class="filter-widget">
                                    <h6 class="filter-title">Sản phẩm gần đây</h6>
                                    <div class="filter-content">
                                        <div class="recent-products">
                                            @foreach($products->take(4) as $product)
                                                @php
                                                    $variant = $product->mainVariant;
                                                @endphp
                                                <div class="recent-product-item">
                                                    <div class="recent-product-image">
                                                            <a href="{{ route('shop.show', $product->id) }}">
                                                                @if($variant && $variant->image)
                                                                <img src="{{ str_starts_with($variant->image, 'http') ? $variant->image : asset('storage/' . $variant->image) }}"
                                                                     alt="{{ $product->name }}"
                                                                     onerror="this.src='{{ asset('images/no-image.png') }}'">
                                                                @else
                                                                <img src="{{ asset('images/no-image.png') }}" alt="{{ $product->name }}">
                                                                @endif
                                                            </a>
                                                        </div>
                                                    <div class="recent-product-info">
                                                        <h6 class="recent-product-title">
                                                                    <a href="{{ route('shop.show', $product->id) }}">{{ $product->name }}</a>
                                                                </h6>
                                                        <div class="recent-product-price">
                                                            @if($variant)
                                                                @php
                                                                    // Kiểm tra flash sale cho sidebar
                                                                    $flashSaleProduct = \App\Models\FlashSaleProduct::whereHas('flashSale', function($query) {
                                                                        $query->where('is_active', true)
                                                                              ->where('start_time', '<=', now())
                                                                              ->where('end_time', '>', now());
                                                                    })
                                                                    ->where('product_variant_id', $variant->id)
                                                                    ->where('remaining_stock', '>', 0)
                                                                    ->first();
                                                                @endphp

                                                                @if($flashSaleProduct)
                                                                    ₫{{ number_format($flashSaleProduct->sale_price, 0, ',', '.') }}
                                                                @elseif($variant->price_sale && $variant->price_sale < $variant->price)
                                                                    ₫{{ number_format($variant->price_sale, 0, ',', '.') }}
                                                                @else
                                                                    ₫{{ number_format($variant->price, 0, ',', '.') }}
                                                                @endif
                                                            @else
                                                                Chưa có giá
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End page content -->

@endsection

@section('script-client')
<script>
// Cache cho trạng thái yêu thích
const favoriteCache = new Map();

// Hiển thị modal thông báo ở giữa màn hình
function showModal(message, type = 'info') {
    const icons = {
        success: 'zmdi-check-circle success-icon',
        error: 'zmdi-close-circle error-icon',
        warning: 'zmdi-alert-triangle warning-icon',
        info: 'zmdi-info info-icon'
    };

    const titles = {
        success: 'Thành công!',
        error: 'Có lỗi!',
        warning: 'Cảnh báo!',
        info: 'Thông báo'
    };

    const modalHtml = `
        <div class="modal-overlay" id="shopNotificationModal">
            <div class="modal-content">
                <button class="modal-close" onclick="closeShopModal()">
                    <i class="zmdi zmdi-close"></i>
                </button>
                <i class="zmdi ${icons[type]} modal-icon"></i>
                <h3 class="modal-title">${titles[type]}</h3>
                <p class="modal-message">${message}</p>
            </div>
        </div>
    `;

    // Xóa modal cũ nếu có
    const oldModal = document.getElementById('shopNotificationModal');
    if (oldModal) {
        oldModal.remove();
    }

    // Thêm modal mới
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Hiển thị animation
    setTimeout(() => {
        const modal = document.getElementById('shopNotificationModal');
        if (modal) {
            modal.classList.add('show');
        }
    }, 100);

    // Tự động đóng sau 3 giây
    setTimeout(function() {
        closeShopModal();
    }, 3000);
}

// Đóng modal
function closeShopModal() {
    const modal = document.getElementById('shopNotificationModal');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.remove(), 300);
    }
}

// Auth flag from server
const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

// Kiểm tra trạng thái yêu thích của tất cả sản phẩm
@auth
function checkFavoriteStatus() {
    const favoriteButtons = document.querySelectorAll('.shop-section .add-to-favorite');

    favoriteButtons.forEach(button => {
        const productId = button.getAttribute('data-product-id');
        const icon = button.querySelector('i');

        // Kiểm tra cache trước
        if (favoriteCache.has(productId)) {
            const isFavorite = favoriteCache.get(productId);
            if (isFavorite) {
                icon.style.color = '#e74c3c';
                button.setAttribute('data-favorited', 'true');
            }
            return;
        }

        // Gọi API kiểm tra trạng thái yêu thích
        fetch(`/favorites/check/${productId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Lưu vào cache
                favoriteCache.set(productId, data.is_favorite);

                if (data.is_favorite) {
                    // Nếu đã yêu thích thì đổi màu icon thành ��ỏ
                    icon.style.color = '#e74c3c';
                    button.setAttribute('data-favorited', 'true');
                }
            })
            .catch(error => {
                console.log('Lỗi khi kiểm tra trạng thái yêu thích:', error);
            });
    });
}
@endauth

// Hàm thêm vào yêu thích
function addToFavorite(event, productId) {
    event.preventDefault();
    event.stopPropagation();

    // Lấy button và icon chính xác
    const button = event.target.closest('.add-to-favorite');
    const icon = button.querySelector('i');

    // Kiểm tra xem user đã đăng nhập chưa
    @auth
    // Kiểm tra nếu đã yêu thích rồi thì hiển thị thông báo
    if (button.getAttribute('data-favorited') === 'true') {
        showModal('Sản phẩm đã có trong danh sách yêu thích!', 'info');
        return;
    }

    showModal('Đang thêm sản phẩm vào yêu thích...', 'info');

    // Tạo form data
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('_token', '{{ csrf_token() }}');

    // Gửi request
    fetch('/favorites', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Thay đổi màu icon thành đỏ
                icon.style.color = '#e74c3c';
                button.setAttribute('data-favorited', 'true');

                // Cập nhật cache
                favoriteCache.set(productId, true);

                // Hiển thị thông báo thành công
                showModal('Đã thêm sản phẩm vào danh sách yêu thích!', 'success');
            } else {
                // Kiểm tra nếu sản phẩm đã có trong yêu thích thì hiển thị thông báo thông tin
                if (data.message && data.message.includes('đã có trong danh sách yêu thích')) {
                    icon.style.color = '#e74c3c';
                    button.setAttribute('data-favorited', 'true');

                    // Cập nhật cache
                    favoriteCache.set(productId, true);

                    showModal(data.message, 'info');
                } else {
                    showModal(data.message || 'Có lỗi xảy ra!', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showModal('Có lỗi xảy ra khi thêm vào yêu thích!', 'error');
        });
    @else
    // Nếu chưa đăng nhập, chỉ hiển thị thông báo
    showModal('Vui lòng đăng nhập để thêm sản phẩm vào yêu thích!', 'warning');
    @endauth
}

// Hàm chuyển đến trang chi tiết Flash Sale
function goToFlashSaleDetail(productSlug) {
    // Chuyển đến trang flash-sale-product-detail
    window.location.href = `/flash-sale-product/${productSlug}`;
}

// Hàm chuyển đến trang chi tiết sản phẩm thường
function goToProductDetail(productSlug) {
    // Chuyển đến trang product-detail
    window.location.href = `/product/${productSlug}`;
}

// View tabs functionality
document.addEventListener('DOMContentLoaded', function() {
    const viewTabs = document.querySelectorAll('.view-tab');
    const productsGrid = document.getElementById('products-grid');

    viewTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            viewTabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');

            const view = this.dataset.view;
            if (view === 'list') {
                productsGrid.classList.add('list-view');
            } else {
                productsGrid.classList.remove('list-view');
            }
        });
    });

    // Kiểm tra trạng thái yêu thích khi trang load
    @auth
    checkFavoriteStatus();
    @endauth

    // Flash Sale Countdown Timer
    function updateFlashSaleTimers() {
        $('.flash-sale-timer').each(function() {
            const timer = $(this);
            const endTime = new Date(timer.data('end-time')).getTime();
            const now = new Date().getTime();
            const timeLeft = endTime - now;

            if (timeLeft > 0) {
                const hours = Math.floor(timeLeft / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                const timeString =
                    (hours < 10 ? '0' : '') + hours + ':' +
                    (minutes < 10 ? '0' : '') + minutes + ':' +
                    (seconds < 10 ? '0' : '') + seconds;

                timer.find('.timer-text').text(timeString);
            } else {
                timer.find('.timer-text').text('Đã kết thúc');
                timer.addClass('expired');
            }
        });
    }

    // Cập nhật timer mỗi giây
    if (document.querySelectorAll('.flash-sale-timer').length > 0) {
        updateFlashSaleTimers();
        setInterval(updateFlashSaleTimers, 1000);
    }
});
</script>

<style>
/* Shopee-style Breadcrumbs */
.shopee-breadcrumbs {
    background: #fff;
    padding: 16px 0;
    border-bottom: 1px solid #f0f0f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.breadcrumb-link {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #ee4d2d;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s;
    font-weight: 500;
}

.breadcrumb-link:hover {
    background: #fff5f5;
    color: #d73502;
}

.breadcrumb-arrow {
    color: #ccc;
    font-size: 16px;
    margin: 0 4px;
}

.breadcrumb-current {
    color: #333;
    font-weight: 600;
    padding: 4px 8px;
    background: #f8f9fa;
    border-radius: 4px;
}

/* Main Shop Section */
.shop-section {
    background: #f8f9fa;
    padding: 20px 0;
    min-height: 80vh;
}

/* Shop Options */
.shop-options {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.view-tabs {
    display: flex;
    gap: 5px;
}

.view-tab {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
    color: #6c757d;
}

.view-tab.active,
.view-tab:hover {
    background: #ee4d2d;
    border-color: #ee4d2d;
    color: #fff;
}

.shop-controls .form-select {
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 8px 12px;
}

/* Product Cards */
.products-container {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 20px;
}

.product-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    overflow: hidden;
    height: 380px; /* Fixed height for all cards */
    border: 1px solid #f0f0f0;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #ee4d2d;
}

.product-image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
    flex-shrink: 0; /* Prevent shrinking */
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
    display: block; /* Remove any inline spacing */
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

/* Product Actions - Moved to bottom */
.product-actions {
    display: flex;
    justify-content: center;
    gap: 6px;
    margin-top: 8px;
    padding: 4px 0;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    color: #666;
    text-align: center;
    transition: all 0.2s ease;
    text-decoration: none;
    font-size: 12px;
    cursor: pointer;
}

.action-btn:hover {
    background: #f8f8f8;
    color: #333;
    transform: none;
    box-shadow: none;
}

.action-btn.add-to-favorite {
    color: #999;
}

.action-btn.add-to-favorite[data-favorited="true"],
.action-btn.add-to-favorite:hover {
    color: #ff4757;
    background: #fff;
}

.action-btn.add-to-cart {
    color: #666;
    background: #fff;
}

.action-btn.add-to-cart:hover {
    color: #2ecc71;
    background: #f8f8f8;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal-overlay.show {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    background: white;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    transform: scale(0.7);
    transition: transform 0.3s ease;
}

.modal-overlay.show .modal-content {
    transform: scale(1);
}

.modal-icon {
    font-size: 60px;
    margin-bottom: 20px;
    color: #28a745;
}

.modal-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
}

.modal-message {
    font-size: 16px;
    color: #666;
    line-height: 1.5;
    margin-bottom: 25px;
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 20px;
    background: none;
    border: none;
    font-size: 24px;
    color: #999;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: #f8f9fa;
    color: #333;
}

.success-icon {
    color: #28a745;
}

.error-icon {
    color: #dc3545;
}

.warning-icon {
    color: #ffc107;
}

.info-icon {
    color: #17a2b8;
}

.product-content {
    padding: 15px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.product-title {
    font-size: 14px;
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: 12px;
    height: 40px; /* Fixed height for 2 lines */
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    text-align: center;
}

.product-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s;
}

.product-title a:hover {
    color: #ee4d2d;
}

.variant-tags {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}

.variant-tag {
    background: #f8f9fa;
    color: #6c757d;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.price-info {
    text-align: center;
    min-height: 60px; /* Fixed minimum height for price section */
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin-bottom: 8px;
}

.current-price {
    font-size: 16px;
    font-weight: 700;
    color: #ee4d2d;
    margin-bottom: 2px;
    line-height: 1.2;
}

.original-price {
    font-size: 12px;
    color: #999;
    text-decoration: line-through;
    margin-bottom: 2px;
    line-height: 1.2;
}

.savings-badge {
    background: #d4edda;
    color: #155724;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 500;
    display: inline-block;
    margin-top: 2px;
    line-height: 1.2;
}

/* Product Actions - Fixed position */
.product-actions {
    display: flex;
    justify-content: center;
    gap: 6px;
    padding: 8px 0;
    margin-top: auto; /* Push to bottom */
    flex-shrink: 0; /* Prevent shrinking */
}

/* Sidebar */
.shop-sidebar {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 20px;
}

.filter-widget {
    margin-bottom: 25px;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 20px;
}

.filter-widget:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.filter-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #ee4d2d;
    display: inline-block;
}

.category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-item {
    margin-bottom: 8px;
}

.category-link {
    display: block;
    padding: 8px 12px;
    color: #666;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.2s;
    font-size: 14px;
}

.category-link:hover,
.category-link.active {
    background: #fff5f5;
    color: #ee4d2d;
}

.price-inputs {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
}

.price-inputs .form-control {
    flex: 1;
    padding: 8px 12px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    font-size: 14px;
}

.price-separator {
    color: #666;
    font-weight: 500;
}

.recent-products {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.recent-product-item {
    display: flex;
    gap: 10px;
    padding: 8px;
    border-radius: 6px;
    transition: background 0.2s;
}

.recent-product-item:hover {
    background: #f8f9fa;
}

.recent-product-image {
    width: 50px;
    height: 50px;
    border-radius: 4px;
    overflow: hidden;
    flex-shrink: 0;
}

.recent-product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.recent-product-info {
    flex: 1;
    min-width: 0;
}

.recent-product-title {
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 4px;
    line-height: 1.3;
}

.recent-product-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s;
}

.recent-product-title a:hover {
    color: #ee4d2d;
}

.recent-product-price {
    font-size: 12px;
    font-weight: 600;
    color: #ee4d2d;
}

/* Pagination */
.pagination-container {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    text-align: center;
}

/* List View */
.products-container.list-view .product-card {
    display: flex;
    flex-direction: row;
    height: auto;
}

.products-container.list-view .product-image-container {
    width: 200px;
    height: 150px;
    flex-shrink: 0;
}

.products-container.list-view .product-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Flash Sale Styles */
.flash-sale-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: linear-gradient(135deg, #ff6b6b, #ee5a24);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 3px;
    box-shadow: 0 2px 8px rgba(238, 90, 36, 0.3);
    animation: flashPulse 2s infinite;
    z-index: 10;
}

.flash-sale-badge i {
    font-size: 12px;
}

@keyframes flashPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.flash-sale-timer {
    position: absolute;
    bottom: 12px;
    left: 8px;
    background: rgba(0, 0, 0, 0.8);
    color: #fff;
    padding: 6px 8px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 500;
    z-index: 3;
    display: flex;
    align-items: center;
    gap: 3px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(4px);
}

.flash-sale-timer i {
    font-size: 10px;
    color: #ffd700;
}

.flash-sale-price {
    color: #ff6b6b !important;
    font-weight: 700 !important;
    font-size: 18px !important;
}

.flash-sale-price-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-bottom: 2px;
}

.flash-sale-discount {
    background: #ff6b6b;
    color: white;
    padding: 2px 6px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 600;
    display: inline-block;
    margin-top: 4px;
}

.flash-sale-discount-inline {
    background: #ff6b6b;
    color: white;
    padding: 1px 4px;
    border-radius: 4px;
    font-size: 8px;
    font-weight: 600;
    display: inline-block;
    line-height: 1;
}

/* Responsive */
@media (max-width: 768px) {
    .shop-options {
        padding: 15px;
    }

    .view-tabs {
        justify-content: center;
        margin-bottom: 15px;
    }

    .shop-controls {
        text-align: center;
    }

    .product-image-container {
        height: 180px;
    }

    .shop-sidebar {
        margin-top: 20px;
        padding: 15px;
    }

    .products-container.list-view .product-card {
        flex-direction: column;
    }

    .products-container.list-view .product-image-container {
        width: 100%;
        height: 200px;
    }
}
</style>
@endsection
