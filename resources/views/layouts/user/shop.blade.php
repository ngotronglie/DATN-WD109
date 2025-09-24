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
                            <!-- Shop Options -->
                            <div class="shop-options mb-4">
                                <div class="row align-items-center justify-content-end">
                                    <div class="col-md-4">
                                        <div class="shop-controls">
                                            <form method="GET" action="{{ route('shop.index') }}" id="sortForm" class="sort-form">
                                                <div class="sort-wrapper">
                                                    <label for="sort-select" class="sort-label">
                                                        <i class="zmdi zmdi-sort"></i>
                                                        Sắp xếp:
                                                    </label>
                                                    <select name="sort" id="sort-select" onchange="document.getElementById('sortForm').submit()" class="form-select sort-select">
                                                        <option value="">Mặc định</option>
                                                        <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Tên A-Z</option>
                                                        <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Tên Z-A</option>
                                                    </select>
                                                </div>
                                                @foreach(request()->except('sort', 'page') as $key => $value)
                                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                                @endforeach
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                                            <a href="{{ route('shop.show', $product->id) }}">
                                                                @if($variant && $variant->image)
                                                            <img src="{{ str_starts_with($variant->image, 'http') ? $variant->image : asset('storage/' . $variant->image) }}"
                                                                 alt="{{ $product->name }}"
                                                                 class="product-image"
                                                                 onerror="this.src='{{ asset('images/no-image.png') }}'">
                                                                @else
                                                            <img src="{{ asset('images/no-image.png') }}" alt="{{ $product->name }}" class="product-image">
                                                                @endif
                                                            </a>

                                                            {{-- Flash Sale Badge --}}
                                                            @if($variant && $variant->price_sale && $variant->price_sale < $variant->price)
                                                                <div class="flash-sale-badge">
                                                                    <i class="zmdi zmdi-flash"></i>
                                                                    FLASH SALE
                                                                </div>
                                                            @endif

                                                            {{-- Discount Badge --}}
                                                            @if($variant && $variant->price_sale && $variant->price_sale < $variant->price)
                                                                <div class="discount-tag">
                                                                    -{{ round((($variant->price - $variant->price_sale) / $variant->price) * 100) }}%
                                                                </div>
                                                            @endif
                                                        </div>

                                                <div class="product-content">
                                                    <h5 class="product-title text-center">
                                                                <a href="{{ route('shop.show', $product->id) }}">{{ $product->name }}</a>
                                                    </h5>

                                                    <!-- Price -->
                                                    <div class="price-info text-center">
                                                                @if($variant)
                                                            @if($variant->price_sale && $variant->price_sale < $variant->price)
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
                                                        <button class="action-btn wishlist-btn" onclick="addToWishlist({{ $product->id }})" title="Yêu thích">
                                                            <i class="zmdi zmdi-favorite"></i>
                                                        </button>
                                                        
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

                                                            {{-- Flash Sale Badge cho sidebar --}}
                                                            @if($variant && $variant->price_sale && $variant->price_sale < $variant->price)
                                                                <div class="flash-sale-badge-small">
                                                                    <i class="zmdi zmdi-flash"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    <div class="recent-product-info">
                                                        <h6 class="recent-product-title">
                                                                    <a href="{{ route('shop.show', $product->id) }}">{{ $product->name }}</a>
                                                                </h6>
                                                        <div class="recent-product-price">
                                                                @if($variant)
                                                                @if($variant->price_sale && $variant->price_sale < $variant->price)
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
// Center Notification (toast-like)
function showCenterNotice(message, type = 'success') {
    const wrapper = document.createElement('div');
    wrapper.style.position = 'fixed';
    wrapper.style.top = '50%';
    wrapper.style.left = '50%';
    wrapper.style.transform = 'translate(-50%, -50%)';
    wrapper.style.zIndex = '9999';
    wrapper.style.display = 'flex';
    wrapper.style.alignItems = 'center';
    wrapper.style.justifyContent = 'center';
    wrapper.style.pointerEvents = 'none';

    const box = document.createElement('div');
    box.style.minWidth = '300px';
    box.style.maxWidth = '420px';
    box.style.background = '#fff';
    box.style.borderRadius = '10px';
    box.style.boxShadow = '0 10px 30px rgba(0,0,0,0.15)';
    box.style.padding = '16px 18px';
    box.style.fontSize = '15px';
    box.style.fontWeight = '600';
    box.style.textAlign = 'center';
    box.style.border = type === 'success' ? '1px solid #28a745' : '1px solid #dc3545';
    box.style.color = type === 'success' ? '#155724' : '#721c24';
    box.style.background = type === 'success' ? '#d4edda' : '#f8d7da';

    box.textContent = message;
    wrapper.appendChild(box);
    document.body.appendChild(wrapper);

    setTimeout(() => wrapper.remove(), 2000);
}

// Auth flag from server
const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

// Add to cart function
async function addToCart(productId) {
    if (!isLoggedIn) {
        showCenterNotice('Vui lòng đăng nhập để thêm vào giỏ hàng', 'error');
        return;
    }

    try {
        const res = await fetch('/api/add-to-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        });

        const data = await res.json();
        if (data && data.success) {
            showCenterNotice('Đã thêm vào giỏ hàng!', 'success');
            try {
                if (typeof window.setCartBadge === 'function' && data.cart_count !== undefined) {
                    window.setCartBadge(data.cart_count);
                } else if (typeof window.refreshCartBadgeByApi === 'function') {
                    window.refreshCartBadgeByApi();
                }
            } catch (_) {}
        } else {
            showCenterNotice(data?.message || 'Không thể thêm vào giỏ hàng.', 'error');
        }
    } catch (e) {
        console.error('Error adding to cart:', e);
        showCenterNotice('Có lỗi xảy ra. Vui lòng thử lại.', 'error');
    }
}

// Add to wishlist function
async function addToWishlist(productId) {
    if (!isLoggedIn) {
        showCenterNotice('Vui lòng đăng nhập để thêm vào yêu thích', 'error');
        return;
    }

    try {
        const res = await fetch('/favorites', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ product_id: productId })
        });

        const data = await res.json();
        if (data && data.success) {
            showCenterNotice('Đã thêm vào danh sách yêu thích!', 'success');
        } else {
            showCenterNotice(data?.message || 'Có lỗi xảy ra.', 'error');
        }
    } catch (e) {
        console.error('Error adding to wishlist:', e);
        showCenterNotice('Có lỗi xảy ra. Vui lòng thử lại.', 'error');
    }
}

// Quick view function
function quickView(productId) {
    // Implement quick view modal here
    showCenterNotice('Tính năng xem nhanh đang được phát triển', 'info');
}

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



/* Shop Controls */
.shop-controls {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    height: 100%;
}

.sort-form {
    width: 100%;
}

.sort-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 8px 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}

.sort-wrapper:hover {
    border-color: #ee4d2d;
    box-shadow: 0 2px 8px rgba(238, 77, 45, 0.15);
}

.sort-label {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #666;
    font-size: 14px;
    font-weight: 500;
    margin: 0;
    white-space: nowrap;
}

.sort-label i {
    color: #ee4d2d;
    font-size: 16px;
}

.sort-select {
    border: none;
    background: transparent;
    padding: 4px 8px;
    font-size: 14px;
    font-weight: 500;
    color: #333;
    cursor: pointer;
    outline: none;
    min-width: 140px;
}

.sort-select:focus {
    box-shadow: none;
    border: none;
    outline: none;
}

.sort-select option {
    padding: 8px 12px;
    background: #fff;
    color: #333;
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
    height: 100%;
    border: 1px solid #f0f0f0;
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
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.product-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-actions {
    opacity: 1;
}

.action-btn {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: none;
    background: rgba(255,255,255,0.9);
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.action-btn:hover {
    background: #ee4d2d;
    color: #fff;
    transform: scale(1.1);
}

.product-content {
    padding: 15px;
}

.product-title {
    font-size: 14px;
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: 8px;
    height: 2.8em;
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
    margin-top: auto;
    text-align: center;
}

.current-price {
    font-size: 16px;
    font-weight: 700;
    color: #ee4d2d;
    margin-bottom: 2px;
}

.original-price {
    font-size: 12px;
    color: #999;
    text-decoration: line-through;
    margin-bottom: 2px;
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
}

/* Flash Sale Badge */
.flash-sale-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: linear-gradient(135deg, #ff1744, #ff5722);
    color: white;
    font-size: 10px;
    font-weight: 700;
    padding: 4px 8px;
    border-radius: 12px;
    z-index: 3;
    display: flex;
    align-items: center;
    gap: 3px;
    box-shadow: 0 2px 8px rgba(255, 23, 68, 0.4);
    animation: flashSalePulse 2s infinite ease-in-out;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    max-width: 80px;
    white-space: nowrap;
}

.flash-sale-badge i {
    font-size: 12px;
    animation: flashSaleFlash 1.5s infinite ease-in-out;
}

/* Flash Sale Badge Animations */
@keyframes flashSalePulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 2px 8px rgba(255, 23, 68, 0.4);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(255, 23, 68, 0.6);
    }
}

@keyframes flashSaleFlash {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* Discount Tag */
.discount-tag {
    position: absolute;
    top: 8px;
    right: 8px;
    background: #ff6b00;
    color: white;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 6px;
    border-radius: 8px;
    z-index: 2;
    box-shadow: 0 2px 6px rgba(255, 107, 0, 0.3);
}

/* Adjust discount tag position when flash sale badge is present */
.product-image-container:has(.flash-sale-badge) .discount-tag {
    top: 40px;
}

/* Flash Sale Badge cho sidebar - phiên bản nhỏ */
.flash-sale-badge-small {
    position: absolute;
    top: 2px;
    left: 2px;
    background: linear-gradient(135deg, #ff1744, #ff5722);
    color: white;
    font-size: 8px;
    font-weight: 700;
    padding: 2px 4px;
    border-radius: 6px;
    z-index: 3;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 4px rgba(255, 23, 68, 0.4);
    animation: flashSalePulse 2s infinite ease-in-out;
    width: 16px;
    height: 16px;
}

.flash-sale-badge-small i {
    font-size: 10px;
    animation: flashSaleFlash 1.5s infinite ease-in-out;
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


/* Responsive */
@media (max-width: 768px) {
    .shop-options {
        padding: 15px;
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

}
</style>
@endsection
