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
                    <span class="breadcrumb-current">Sản phẩm yêu thích (<span id="favorites-display">0</span> sản phẩm)</span>
                </div>
            </div>
        </div>

        <!-- Start page content -->
        <div id="page-content" class="page-wrapper section">

            <!-- FAVORITES SECTION START -->
            <div class="shop-section mb-80">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="shop-content">

                                <!-- Favorites Content -->
                                <div id="favorites-container">
                                    @if(isset($favorites) && count($favorites) > 0)
                                        <div class="favorites-grid">
                                            @foreach($favorites as $favorite)
                                                @if($favorite->product)
                                                <div class="favorite-card">
                                                    <div class="card-image">
                                                        <div class="image-placeholder"></div>
                                                        @php
                                                            // Kiểm tra flash sale cho link
                                                            $variant = $favorite->product->variants->first();
                                                            $flashSaleProduct = null;
                                                            if ($variant) {
                                                                $flashSaleProduct = \App\Models\FlashSaleProduct::whereHas('flashSale', function($query) {
                                                                    $query->where('is_active', true)
                                                                          ->where('start_time', '<=', now())
                                                                          ->where('end_time', '>', now());
                                                                })
                                                                ->where('product_variant_id', $variant->id)
                                                                ->where('remaining_stock', '>', 0)
                                                                ->first();
                                                            }
                                                            $productUrl = $flashSaleProduct ? route('flash-sale.product.detail', $favorite->product->slug) : route('product.detail', $favorite->product->slug);
                                                        @endphp
                                                        <a href="{{ $productUrl }}">
                                                            @if($favorite->product->variants && $favorite->product->variants->first() && $favorite->product->variants->first()->image)
                                                                <img class="product-image" src="{{ asset($favorite->product->variants->first()->image) }}" alt="{{ $favorite->product->name }}" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0;" />
                                                            @else
                                                                <img class="product-image" src="{{ asset('frontend/img/product/1.jpg') }}" alt="{{ $favorite->product->name }}" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0;" />
                                                            @endif
                                                        </a>

                                                        @php
                                                            // Kiểm tra flash sale cho badge
                                                            $variant = $favorite->product->variants->first();
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
                                                    <div class="card-content">
                                                        <h3 class="product-name">
                                                            <a href="{{ route('product.detail', $favorite->product->slug) }}">{{ Str::limit($favorite->product->name, 60) }}</a>
                                                        </h3>
                                                        <div class="product-price">
                                                            @if($favorite->product->variants && $favorite->product->variants->first())
                                                                @php
                                                                    $variant = $favorite->product->variants->first();
                                                                    // Kiểm tra xem sản phẩm có đang flash sale không
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
                                                                    <div class="flash-sale-price">₫{{ number_format($flashSaleProduct->sale_price, 0, ',', '.') }}</div>
                                                                    <div class="original-price">₫{{ number_format($flashSaleProduct->original_price, 0, ',', '.') }}</div>
                                                                    <div class="flash-sale-discount">
                                                                        -{{ round((($flashSaleProduct->original_price - $flashSaleProduct->sale_price) / $flashSaleProduct->original_price) * 100) }}%
                                                                    </div>
                                                                @elseif($variant->price_sale && $variant->price_sale < $variant->price)
                                                                    <div class="current-price">₫{{ number_format($variant->price_sale, 0, ',', '.') }}</div>
                                                                    <div class="old-price">₫{{ number_format($variant->price, 0, ',', '.') }}</div>
                                                                @else
                                                                    <div class="current-price">₫{{ number_format($variant->price, 0, ',', '.') }}</div>
                                                                @endif
                                                            @else
                                                                <span class="current-price">Liên hệ</span>
                                                            @endif
                                                        </div>

                                                        {{-- Product Actions --}}
                                                        <div class="product-actions">
                                                           
                                                            <button class="action-btn remove-favorite-btn" data-favorite-id="{{ $favorite->id }}" title="Xóa khỏi yêu thích">
                                                                <i class="zmdi zmdi-delete"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <div class="empty-favorites">
                                                <i class="zmdi zmdi-favorite-outline" style="font-size: 80px; color: #ddd;"></i>
                                                <h3 class="mt-3">Chưa có sản phẩm yêu thích</h3>
                                                <p class="text-muted">Bạn chưa thêm sản phẩm nào vào danh sách yêu thích.</p>
                                                <a href="{{ route('products') }}" class="btn btn-primary mt-3">
                                                    <i class="zmdi zmdi-shopping-cart"></i> Mua sắm ngay
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FAVORITES SECTION END -->
        </div>
        <!-- End page content -->

{{-- Product Variant Popup --}}
<div id="variantPopup" class="variant-popup-overlay">
    <div class="variant-popup-content">
        <div class="popup-header">
            <h3>Chọn phân loại hàng</h3>
            <button class="popup-close" onclick="closeVariantPopup()">
                <i class="zmdi zmdi-close"></i>
            </button>
        </div>

        <div class="popup-body">
            <div class="product-info">
                <div class="product-image">
                    <img id="popupProductImage" src="" alt="">
                </div>
                <div class="product-details">
                    <h4 id="popupProductName"></h4>
                    <div class="product-price">
                        <span id="popupProductPrice" class="current-price"></span>
                        <span id="popupOriginalPrice" class="original-price"></span>
                    </div>
                    <div class="stock-info">
                        Kho: <span id="popupStock">0</span>
                    </div>
                </div>
            </div>

            <div class="variant-options">
                <div class="color-section" id="colorSection" style="display: none;">
                    <label>Màu sắc:</label>
                    <div class="color-options" id="colorOptions"></div>
                </div>

                <div class="capacity-section" id="capacitySection" style="display: none;">
                    <label>Dung lượng:</label>
                    <div class="capacity-options" id="capacityOptions"></div>
                </div>
            </div>

            <div class="quantity-section">
                <label>Số lượng:</label>
                <div class="quantity-controls">
                    <button type="button" class="qty-btn" onclick="decreaseQuantity()">-</button>
                    <input type="number" id="quantity" value="1" min="1" max="999">
                    <button type="button" class="qty-btn" onclick="increaseQuantity()">+</button>
                </div>
            </div>
        </div>

        <div class="popup-footer">
            <button class="add-to-cart-btn" onclick="addSelectedVariantToCart()" id="addToCartBtn">
                <i class="zmdi zmdi-shopping-cart"></i>
                Thêm vào giỏ hàng
            </button>
        </div>
    </div>
</div>
@endsection

@section('script-client')
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
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Favorites Grid */
.favorites-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    margin-top: 20px;
}

.favorite-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.favorite-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.card-image {
    position: relative;
    aspect-ratio: 1;
    overflow: hidden;
}

.image-placeholder {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
    z-index: 1;
}

@keyframes shimmer {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

.card-image .product-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100% !important;
    height: 100% !important;
    object-fit: cover;
    transition: all 0.3s ease;
    opacity: 0;
    z-index: 2;
    max-width: 100% !important;
    max-height: 100% !important;
    min-width: 100% !important;
    min-height: 100% !important;
}

.card-image .product-image.loaded {
    opacity: 1;
}

@keyframes fadeInImage {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.favorite-card:hover .card-image .product-image {
    transform: scale(1.05);
}

/* Product Actions */
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
    width: 32px;
    height: 32px;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    color: #666;
    text-align: center;
    transition: all 0.2s ease;
    text-decoration: none;
    font-size: 14px;
    cursor: pointer;
    z-index: 10;
    position: relative;
    pointer-events: auto;
}

.action-btn:hover {
    background: #f8f8f8;
    color: #333;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.action-btn.add-to-cart {
    color: #666;
    background: #fff;
}

.action-btn.add-to-cart:hover {
    color: #2ecc71;
    background: #f0fff4;
    border-color: #2ecc71;
}

.action-btn.remove-favorite-btn {
    color: #999;
}

.action-btn.remove-favorite-btn:hover {
    color: #ff4757;
    background: #fff5f5;
    border-color: #ff4757;
}

.card-content {
    padding: 12px;
}

.product-name {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 6px;
    line-height: 1.3;
    text-align: center;
}

.product-name a {
    color: #333;
    text-decoration: none;
}

.product-name a:hover {
    color: #ee4d2d;
}

.product-price {
    margin-bottom: 8px;
    text-align: center;
}

.current-price {
    font-size: 16px;
    font-weight: 600;
    color: #ee4d2d;
    margin-bottom: 4px;
}

.flash-sale-price {
    font-size: 18px;
    font-weight: 700;
    color: #ff6b6b;
    margin-bottom: 4px;
}

.old-price, .original-price {
    font-size: 12px;
    color: #999;
    text-decoration: line-through;
    margin-bottom: 4px;
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
    bottom: 8px;
    left: 8px;
    background: rgba(0, 0, 0, 0.8);
    color: #fff;
    padding: 4px 6px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 500;
    z-index: 3;
    display: flex;
    align-items: center;
    gap: 3px;
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

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.btn-cancel:hover {
    background: #e9ecef !important;
}

.btn-confirm:hover {
    background: #c82333 !important;
}

/* Responsive */
@media (max-width: 768px) {
    .favorite-hero-section {
        padding: 60px 0;
    }

    .hero-title {
        font-size: 36px;
    }

    .hero-subtitle {
        font-size: 18px;
    }

    .floating-hearts {
        height: 150px;
    }

    .heart {
        font-size: 24px;
    }

    .stat-number {
        font-size: 36px;
    }
}
/* Variant Popup Styles */
.variant-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: 10000;
    display: none;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.variant-popup-overlay.show {
    display: flex;
    opacity: 1;
}

.variant-popup-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    transform: scale(0.8);
    transition: transform 0.3s ease;
}

.variant-popup-overlay.show .variant-popup-content {
    transform: scale(1);
}

.popup-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #f0f0f0;
    background: #fafafa;
    border-radius: 12px 12px 0 0;
}

.popup-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.popup-close {
    background: none;
    border: none;
    font-size: 20px;
    color: #999;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.popup-close:hover {
    background: #f0f0f0;
    color: #333;
}

.popup-body {
    padding: 25px;
}

.product-info {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f0f0f0;
}

.product-image {
    flex-shrink: 0;
}

.product-image img {
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 8px;
    border: 1px solid #f0f0f0;
}

.product-details h4 {
    margin: 0 0 10px 0;
    font-size: 16px;
    font-weight: 600;
    color: #333;
    line-height: 1.4;
}

.current-price {
    color: #ee4d2d;
    font-size: 18px;
    font-weight: 600;
    margin-right: 10px;
}

.stock-info {
    color: #666;
    font-size: 14px;
}

.variant-options {
    margin-bottom: 25px;
}

.color-section, .capacity-section {
    margin-bottom: 20px;
}

.color-section label, .capacity-section label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.color-options, .capacity-options {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.color-option, .capacity-option {
    padding: 8px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
    min-width: 60px;
    text-align: center;
    position: relative;
    z-index: 10001;
    pointer-events: auto;
    user-select: none;
}

.color-option:hover, .capacity-option:hover {
    border-color: #ee4d2d;
    background: #fff5f5;
}

.color-option.selected, .capacity-option.selected {
    border-color: #ee4d2d;
    background: #ee4d2d;
    color: white;
}

.quantity-section {
    margin-bottom: 25px;
}

.quantity-section label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0;
    width: fit-content;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    overflow: hidden;
}

.qty-btn {
    background: #f8f8f8;
    border: none;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 18px;
    font-weight: 600;
    color: #666;
    transition: all 0.2s ease;
}

.qty-btn:hover {
    background: #e0e0e0;
    color: #333;
}

.qty-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

#quantity {
    border: none;
    width: 60px;
    height: 40px;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
    background: white;
    outline: none;
}

.popup-footer {
    padding: 20px 25px;
    border-top: 1px solid #f0f0f0;
    background: #fafafa;
    border-radius: 0 0 12px 12px;
}

.add-to-cart-btn {
    width: 100%;
    background: linear-gradient(135deg, #ee4d2d, #ff6b47);
    color: white;
    border: none;
    padding: 15px 20px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.add-to-cart-btn:hover {
    background: linear-gradient(135deg, #d73211, #ee4d2d);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(238, 77, 45, 0.3);
}

.add-to-cart-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .variant-popup-content {
        width: 95%;
        margin: 20px;
    }

    .popup-header, .popup-body, .popup-footer {
        padding: 15px 20px;
    }

    .product-info {
        flex-direction: column;
        text-align: center;
    }

    .product-image img {
        width: 100px;
        height: 100px;
        margin: 0 auto;
    }

    .color-options, .capacity-options {
        justify-content: center;
    }

    .quantity-controls {
        margin: 0 auto;
    }
}
</style>

<script>
    function showNotification(message, type) {
        // Xóa thông báo cũ nếu có
        $('.custom-notification').remove();

        const bgColor = type === 'success' ? '#28a745' : '#dc3545';
        const icon = type === 'success' ? 'zmdi-check-circle' : 'zmdi-alert-circle';

        const notificationHtml = `
            <div class="custom-notification" style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${bgColor};
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 9999;
                max-width: 300px;
                display: flex;
                align-items: center;
                animation: slideInRight 0.3s ease;
            ">
                <i class="zmdi ${icon}" style="font-size: 20px; margin-right: 10px;"></i>
                <span>${message}</span>
            </div>
        `;

        $('body').append(notificationHtml);

        // Tự động ẩn sau 5 giây
        setTimeout(function() {
            $('.custom-notification').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }
$(document).ready(function() {
    // Xử lý loading ảnh sản phẩm
    $('.product-image').each(function() {
        const img = $(this);
        const placeholder = img.closest('.card-image').find('.image-placeholder');

        // Đặt kích thước ngay lập tức để tránh flash
        img.css({
            'width': '100%',
            'height': '100%',
            'object-fit': 'cover',
            'position': 'absolute',
            'top': '0',
            'left': '0',
            'opacity': '0'
        });

        // Nếu ảnh đã load
        if (this.complete && this.naturalWidth > 0) {
            img.css('opacity', '1');
            placeholder.hide();
        } else {
            // Xử lý khi ảnh load xong
            img.on('load', function() {
                $(this).css('opacity', '1');
                placeholder.fadeOut(200);
            });

            // Xử lý khi ảnh lỗi
            img.on('error', function() {
                $(this).css('opacity', '1');
                placeholder.fadeOut(200);
            });
        }
    });

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
    if ($('.flash-sale-timer').length > 0) {
        updateFlashSaleTimers();
        setInterval(updateFlashSaleTimers, 1000);
    }

    // Xóa sản phẩm khỏi favorites
    $(document).on('click', '.remove-favorite-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        console.log('Remove button clicked!'); // Debug log

        const favoriteId = $(this).data('favorite-id');
        const productItem = $(this).closest('.favorite-card');
        const productName = $(this).closest('.favorite-card').find('.product-name a').text();

        console.log('Favorite ID:', favoriteId); // Debug log

        // Hiển thị modal xác nhận
        showConfirmModal(
            'Xóa khỏi danh sách yêu thích',
            `Bạn có chắc chắn muốn xóa sản phẩm "<strong>${productName}</strong>" khỏi danh sách yêu thích?`,
            function() {
                // Thực hiện xóa khi xác nhận
                $.ajax({
                    url: '/favorites/' + favoriteId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        productItem.fadeOut(300, function() {
                            $(this).remove();
                            updateFavoritesCount();

                            // Kiểm tra nếu không còn sản phẩm nào
                            if ($('.favorite-card').length === 0) {
                                location.reload();
                            }
                        });

                        // Hiển thị thông báo thành công
                        showNotification('Đã xóa sản phẩm khỏi danh sách yêu thích', 'success');
                    },
                    error: function(xhr) {
                        showNotification('Có lỗi xảy ra khi xóa sản phẩm', 'error');
                    }
                });
            }
        );
    });


    // Cập nhật số lượng favorites
    function updateFavoritesCount() {
        const count = $('.favorite-card').length;
        $('#favorites-display').text(count);
    }

    // Hiển thị thông báo


    // Hiển thị modal xác nhận
    function showConfirmModal(title, message, onConfirm) {
        // Xóa modal cũ nếu có
        $('.custom-confirm-modal').remove();

        const modalHtml = `
            <div class="custom-confirm-modal" style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 10000;
                display: flex;
                align-items: center;
                justify-content: center;
                animation: fadeIn 0.3s ease;
            ">
                <div style="
                    background: white;
                    padding: 30px;
                    border-radius: 12px;
                    max-width: 400px;
                    width: 90%;
                    text-align: center;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                ">
                    <div style="margin-bottom: 20px;">
                        <i class="zmdi zmdi-alert-circle" style="font-size: 50px; color: #e74c3c;"></i>
                    </div>
                    <h4 style="margin-bottom: 15px; color: #333;">${title}</h4>
                    <p style="margin-bottom: 25px; color: #666; line-height: 1.5;">${message}</p>
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <button class="btn-cancel" style="
                            padding: 10px 20px;
                            border: 1px solid #ddd;
                            background: #f8f9fa;
                            color: #666;
                            border-radius: 6px;
                            cursor: pointer;
                            transition: all 0.3s;
                        ">Hủy</button>
                        <button class="btn-confirm" style="
                            padding: 10px 20px;
                            border: none;
                            background: #e74c3c;
                            color: white;
                            border-radius: 6px;
                            cursor: pointer;
                            transition: all 0.3s;
                        ">Xóa</button>
                    </div>
                </div>
            </div>
        `;

        $('body').append(modalHtml);

        // Xử lý sự kiện click
        $('.btn-cancel').on('click', function() {
            $('.custom-confirm-modal').fadeOut(300, function() {
                $(this).remove();
            });
        });

        $('.btn-confirm').on('click', function() {
            $('.custom-confirm-modal').fadeOut(300, function() {
                $(this).remove();
            });
            if (onConfirm) onConfirm();
        });

        // Đóng modal khi click bên ngoài
        $('.custom-confirm-modal').on('click', function(e) {
            if (e.target === this) {
                $(this).fadeOut(300, function() {
                    $(this).remove();
                });
            }
        });
    }

    // Khởi tạo số lượng favorites
    updateFavoritesCount();

    // Biến lưu trữ thông tin sản phẩm và biến thể
    window.currentProductData = null;
    window.selectedVariant = null;
    window.selectedColorId = null;
    window.selectedCapacityId = null;
});

// Hiển thị popup chọn biến thể
function showVariantPopup(event, productId, productName, productImage) {
    event.preventDefault();
    event.stopPropagation();

    @guest
    showNotification('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!', 'error');
    return;
    @endguest

    // Lưu ảnh sản phẩm để sử dụng làm fallback
    window.fallbackProductImage = productImage;

    // Gọi API để lấy thông tin biến thể
    fetch(`/api/product-variants-popup/${productId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('API Response:', data); // Debug log
            if (data.success) {
                const variants = data.data.variants;
                console.log('Variants:', variants); // Debug log
                console.log('Colors:', data.data.colors); // Debug log
                console.log('Capacities:', data.data.capacities); // Debug log

                // Nếu chỉ có 1 biến thể, thêm trực tiếp vào giỏ hàng
                if (variants.length === 1) {
                    addSingleVariantToCart(variants[0]);
                } else {
                    // Nếu có nhiều biến thể, hiển thị popup
                    showVariantSelectionPopup(data.data);
                }
            } else {
                throw new Error(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Có lỗi khi tải thông tin sản phẩm!', 'error');
        });
}

// Thêm sản phẩm có 1 biến thể trực tiếp vào giỏ hàng
function addSingleVariantToCart(variant) {
    showNotification('Đang thêm sản phẩm vào giỏ hàng...', 'info');

    // Tạo form data
    const formData = new FormData();
    formData.append('product_variant_id', variant.id);
    formData.append('quantity', 1);
    formData.append('_token', '{{ csrf_token() }}');

    // Gửi request
    fetch('/api/add-to-cart', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Đã thêm sản phẩm vào giỏ hàng!', 'success');
        } else {
            showNotification(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
        }
    })

    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
    });

}

// Hiển thị popup chọn biến thể cho sản phẩm có nhiều biến thể
function showVariantSelectionPopup(data) {
    console.log('Showing popup with data:', data); // Debug log
    
    // Hiển thị popup
    const popup = document.getElementById('variantPopup');
    console.log('Popup element:', popup); // Debug log
    
    if (popup) {
        popup.style.display = 'flex';
        setTimeout(() => {
            popup.classList.add('show');
            console.log('Popup should be visible now'); // Debug log
        }, 10);

        // Hiển thị thông tin sản phẩm và biến thể
        window.currentProductData = data;
        displayProductVariants(data);
    } else {
        console.error('Popup element not found!');
    }
}

// Hiển thị thông tin sản phẩm và biến thể
function displayProductVariants(data) {
    console.log('displayProductVariants called with:', data); // Debug log
    
    // Hiển thị thông tin sản phẩm
    const productImage = document.getElementById('popupProductImage');
    const productName = document.getElementById('popupProductName');
    
    console.log('Product image element:', productImage); // Debug log
    console.log('Product name element:', productName); // Debug log

    // Hiển thị ảnh sản phẩm
    let imageUrl = data.product.product_image || window.fallbackProductImage || 'https://via.placeholder.com/150x150?text=No+Image';

    productImage.src = imageUrl;
    productImage.alt = data.product.product_name || 'Ảnh sản phẩm';
    productImage.style.display = 'block';

    // Xử lý lỗi khi load ảnh
    productImage.onerror = function() {
        // Thử fallback image trước
        if (this.src !== window.fallbackProductImage && window.fallbackProductImage) {
            this.src = window.fallbackProductImage;
        } else {
            this.src = 'https://via.placeholder.com/150x150?text=No+Image';
            this.alt = 'Không có ảnh';
        }
    };

    // Hiển thị tên sản phẩm
    if (data.product.product_name) {
        productName.textContent = data.product.product_name;
        productName.title = data.product.product_name;
    } else {
        productName.textContent = 'Tên sản phẩm';
    }

    // Hiển thị giá sản phẩm ban đầu
    const currentPrice = data.product.product_price_discount > 0 ?
        data.product.product_price_discount : data.product.product_price;
    document.getElementById('popupProductPrice').textContent = '₫' + new Intl.NumberFormat('vi-VN').format(currentPrice);

    // Hiển thị giá gốc nếu có giảm giá
    const originalPriceEl = document.getElementById('popupOriginalPrice');
    if (data.product.product_price_discount > 0 && data.product.product_price_discount < data.product.product_price) {
        originalPriceEl.textContent = '₫' + new Intl.NumberFormat('vi-VN').format(data.product.product_price);
        originalPriceEl.style.display = 'inline';
    } else {
        originalPriceEl.style.display = 'none';
    }

    // Reset selections
    window.selectedColorId = null;
    window.selectedCapacityId = null;
    window.selectedVariant = null;

    // Hiển thị màu sắc
    const colorSection = document.getElementById('colorSection');
    const colorOptions = document.getElementById('colorOptions');
    
    console.log('Color section element:', colorSection); // Debug log
    console.log('Color options element:', colorOptions); // Debug log
    console.log('Colors data:', data.colors); // Debug log

    if (data.colors && data.colors.length > 0) {
        console.log('Displaying colors:', data.colors.length); // Debug log
        colorSection.style.display = 'block';
        colorOptions.innerHTML = '';

        data.colors.forEach((color, index) => {
            console.log(`Creating color option ${index}:`, color); // Debug log
            const colorDiv = document.createElement('div');
            colorDiv.className = 'color-option';
            colorDiv.textContent = color.name;
            colorDiv.setAttribute('data-color-id', color.id);
            colorDiv.style.cursor = 'pointer'; // Đảm bảo có cursor pointer
            colorDiv.style.pointerEvents = 'auto'; // Đảm bảo có thể click
            
            // Thêm event listener thay vì onclick
            colorDiv.addEventListener('click', function(e) {
                console.log('Color clicked:', color.name, color.id); // Debug log
                e.preventDefault();
                e.stopPropagation();
                selectColor(color.id, colorDiv);
            });
            
            colorOptions.appendChild(colorDiv);
            console.log('Color option created and added:', colorDiv); // Debug log
        });

        // Tự động chọn màu đầu tiên nếu chỉ có 1 màu
        if (data.colors.length === 1) {
            console.log('Auto-selecting first color'); // Debug log
            selectColor(data.colors[0].id, colorOptions.firstChild);
        }
    } else {
        console.log('No colors to display'); // Debug log
        colorSection.style.display = 'none';
    }

    // Hiển thị dung lượng
    const capacitySection = document.getElementById('capacitySection');
    const capacityOptions = document.getElementById('capacityOptions');

    console.log('Capacity section element:', capacitySection); // Debug log
    console.log('Capacity options element:', capacityOptions); // Debug log
    console.log('Capacities data:', data.capacities); // Debug log

    if (data.capacities && data.capacities.length > 0) {
        console.log('Displaying capacities:', data.capacities.length); // Debug log
        capacitySection.style.display = 'block';
        capacityOptions.innerHTML = '';

        data.capacities.forEach((capacity, index) => {
            console.log(`Creating capacity option ${index}:`, capacity); // Debug log
            const capacityDiv = document.createElement('div');
            capacityDiv.className = 'capacity-option';
            capacityDiv.textContent = capacity.name;
            capacityDiv.setAttribute('data-capacity-id', capacity.id);
            capacityDiv.style.cursor = 'pointer'; // Đảm bảo có cursor pointer
            capacityDiv.style.pointerEvents = 'auto'; // Đảm bảo có thể click
            
            // Thêm event listener thay vì onclick
            capacityDiv.addEventListener('click', function(e) {
                console.log('Capacity clicked:', capacity.name, capacity.id); // Debug log
                e.preventDefault();
                e.stopPropagation();
                selectCapacity(capacity.id, capacityDiv);
            });
            
            capacityOptions.appendChild(capacityDiv);
            console.log('Capacity option created and added:', capacityDiv); // Debug log
        });

        // Tự động chọn dung lượng đầu tiên nếu chỉ có 1 dung lượng
        if (data.capacities.length === 1) {
            console.log('Auto-selecting first capacity'); // Debug log
            selectCapacity(data.capacities[0].id, capacityOptions.firstChild);
        }
    } else {
        console.log('No capacities to display'); // Debug log
        capacitySection.style.display = 'none';
    }

    // Nếu chỉ có 1 biến thể, tự động chọn
    if (data.variants.length === 1) {
        window.selectedVariant = data.variants[0];
        updateProductInfo();
    }

    // Reset quantity
    document.getElementById('quantity').value = 1;

    // Enable add to cart button
    document.getElementById('addToCartBtn').disabled = false;
    document.getElementById('addToCartBtn').innerHTML = '<i class="zmdi zmdi-shopping-cart"></i> Thêm vào giỏ hàng';
}

// Chọn màu sắc
function selectColor(colorId, element) {
    console.log('selectColor called with:', colorId, element); // Debug log
    window.selectedColorId = colorId;

    // Remove selected class from all color options
    document.querySelectorAll('.color-option').forEach(el => el.classList.remove('selected'));
    element.classList.add('selected');
    
    console.log('Color selected, element classes:', element.className); // Debug log
    console.log('Selected color ID:', window.selectedColorId); // Debug log

    findMatchingVariant();
}

// Chọn dung lượng
function selectCapacity(capacityId, element) {
    console.log('selectCapacity called with:', capacityId, element); // Debug log
    window.selectedCapacityId = capacityId;

    // Remove selected class from all capacity options
    document.querySelectorAll('.capacity-option').forEach(el => el.classList.remove('selected'));
    element.classList.add('selected');
    
    console.log('Capacity selected, element classes:', element.className); // Debug log
    console.log('Selected capacity ID:', window.selectedCapacityId); // Debug log

    findMatchingVariant();
}

// Tìm biến thể phù hợp
function findMatchingVariant() {
    console.log('findMatchingVariant called'); // Debug log
    console.log('Selected color ID:', window.selectedColorId); // Debug log
    console.log('Selected capacity ID:', window.selectedCapacityId); // Debug log
    
    if (!window.currentProductData || !window.currentProductData.variants) {
        console.log('No product data or variants available'); // Debug log
        return;
    }

    window.selectedVariant = window.currentProductData.variants.find(variant => {
        const colorMatch = !window.selectedColorId || variant.color_id == window.selectedColorId;
        const capacityMatch = !window.selectedCapacityId || variant.capacity_id == window.selectedCapacityId;
        console.log(`Checking variant ${variant.id}: color match = ${colorMatch}, capacity match = ${capacityMatch}`); // Debug log
        return colorMatch && capacityMatch;
    });

    console.log('Selected variant:', window.selectedVariant); // Debug log
    updateProductInfo();
}

// Cập nhật thông tin giá và kho
function updateProductInfo() {
    console.log('updateProductInfo called with variant:', window.selectedVariant); // Debug log
    
    if (window.selectedVariant) {
        // Cập nhật giá
        const currentPrice = window.selectedVariant.price_sale || window.selectedVariant.price;
        console.log('Updating price to:', currentPrice); // Debug log
        document.getElementById('popupProductPrice').textContent = '₫' + new Intl.NumberFormat('vi-VN').format(currentPrice);

        // Hiển thị giá gốc nếu có giá sale
        const originalPriceEl = document.getElementById('popupOriginalPrice');
        if (window.selectedVariant.price_sale && window.selectedVariant.price_sale < window.selectedVariant.price) {
            originalPriceEl.textContent = '₫' + new Intl.NumberFormat('vi-VN').format(window.selectedVariant.price);
            originalPriceEl.style.display = 'inline';
            console.log('Showing original price:', window.selectedVariant.price); // Debug log
        } else {
            originalPriceEl.style.display = 'none';
            console.log('Hiding original price'); // Debug log
        }

        // Cập nhật số lượng kho
        const stock = window.selectedVariant.quantity || 0;
        console.log('Updating stock to:', stock); // Debug log
        document.getElementById('popupStock').textContent = stock;

        // Cập nhật max quantity
        const quantityInput = document.getElementById('quantity');
        quantityInput.max = stock;

        // Disable add to cart nếu hết hàng
        const addToCartBtn = document.getElementById('addToCartBtn');
        if (stock <= 0) {
            addToCartBtn.disabled = true;
            addToCartBtn.innerHTML = 'Hết hàng';
            console.log('Product out of stock, disabling add to cart button'); // Debug log
        } else {
            addToCartBtn.disabled = false;
            addToCartBtn.innerHTML = '<i class="zmdi zmdi-shopping-cart"></i> Thêm vào giỏ hàng';
            console.log('Product in stock, enabling add to cart button'); // Debug log
        }
    } else {
        console.log('No variant selected, cannot update product info'); // Debug log
    }
}

// Tăng số lượng
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value) || 1;
    const maxValue = parseInt(quantityInput.max) || 999;

    if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
    }
}

// Giảm số lượng
function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value) || 1;

    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

// Thêm biến thể đã chọn vào giỏ hàng
function addSelectedVariantToCart() {
    if (!window.selectedVariant) {
        showNotification('Vui lòng chọn phân loại sản phẩm!', 'error');
        return;
    }

    const quantity = parseInt(document.getElementById('quantity').value) || 1;

    if (quantity > window.selectedVariant.quantity) {
        showNotification('Số lượng vượt quá kho hàng!', 'error');
        return;
    }

    // Disable button và hiển thị loading
    const addToCartBtn = document.getElementById('addToCartBtn');
    addToCartBtn.disabled = true;
    addToCartBtn.innerHTML = '<div class="loading-spinner"></div> Đang thêm...';

    // Tạo form data
    const formData = new FormData();
    formData.append('product_variant_id', window.selectedVariant.id);
    formData.append('quantity', quantity);
    formData.append('_token', '{{ csrf_token() }}');

    // Gửi request
    fetch('/api/add-to-cart', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Đã thêm sản phẩm vào giỏ hàng!', 'success');
            closeVariantPopup();
        } else {
            showNotification(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
            addToCartBtn.disabled = false;
            addToCartBtn.innerHTML = '<i class="zmdi zmdi-shopping-cart"></i> Thêm vào giỏ hàng';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
        addToCartBtn.disabled = false;
        addToCartBtn.innerHTML = '<i class="zmdi zmdi-shopping-cart"></i> Thêm vào giỏ hàng';
    });
}

// Đóng popup
function closeVariantPopup() {
    const popup = document.getElementById('variantPopup');
    popup.classList.remove('show');
    setTimeout(() => {
        popup.style.display = 'none';
        // Reset data
        window.currentProductData = null;
        window.selectedVariant = null;
        window.selectedColorId = null;
        window.selectedCapacityId = null;
    }, 300);
}

// Đóng popup khi click outside
document.addEventListener('click', function(event) {
    const popup = document.getElementById('variantPopup');
    const popupContent = document.querySelector('.variant-popup-content');

    if (popup && popup.classList.contains('show') && !popupContent.contains(event.target)) {
        closeVariantPopup();
    }
});
</script>
@endsection
