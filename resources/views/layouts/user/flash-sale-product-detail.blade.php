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
            <a href="{{ route('flash-sales') }}" class="breadcrumb-link">Flash Sale</a>
            <i class="zmdi zmdi-chevron-right breadcrumb-arrow"></i>
            <span class="breadcrumb-current">{{ $product->name ?? 'Sản phẩm' }}</span>
        </div>
    </div>
</div>

<!-- Main Product Section -->
<section class="product-detail-section">
    <div class="container">
        <div class="product-detail-container">
            <div class="row">
                <!-- Product Images -->
                <div class="col-lg-5">
                    <div class="product-images-container">
                        <!-- Main Image -->
                        <div class="main-image-wrapper">
                            <img id="product-image" src="{{ isset($variants[0]) ? asset($variants[0]->image) : '' }}" alt="{{ $product->name }}" class="main-product-image">
                            <!-- Flash Sale Badge -->
                            <div class="flash-sale-badge">
                                <span class="badge-text">⚡ FLASH SALE</span>
                                <span class="discount-percent">-{{ $flashSaleProduct->getDiscountPercentage() }}%</span>
                            </div>
                        </div>
                        
                        <!-- Thumbnail Images -->
                        <div class="thumbnail-gallery">
                            @foreach($variants as $variant)
                            <div class="thumbnail-item" onclick="changeMainImage('{{ asset($variant->image) }}')">
                                <img src="{{ asset($variant->image) }}" alt="Thumbnail" class="thumbnail-image">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-7">
                    <div class="product-info-container">
                        <!-- Flash Sale Countdown Compact -->
                        <div class="flash-sale-countdown-compact">
                            <span class="flash-sale-label-compact">⚡ Flash Sale</span>
                            <div class="countdown-timer-compact" data-end-time="{{ $flashSale->end_time->toISOString() }}">
                                <span id="compact-hours">00</span>:
                                <span id="compact-minutes">00</span>:
                                <span id="compact-seconds">00</span>
                            </div>
                        </div>

                        <!-- Product Title -->
                        <h1 class="product-title">{{ $product->name }}</h1>
                        
                        <!-- Product Meta -->
                        <div class="product-meta">
                            <div class="brand-badge">{{ $product->category->Name ?? 'Thương hiệu' }}</div>
                            <div class="view-count">
                                <i class="zmdi zmdi-eye"></i> {{ $product->view_count }} lượt xem
                            </div>
                        </div>

                        <!-- Price Section -->
                        <div class="price-section">
                            <div id="current-price" class="current-price">₫{{ number_format($flashSaleProduct->sale_price, 0, ',', '.') }}</div>
                            <div id="original-price" class="original-price">₫{{ number_format($flashSaleProduct->original_price, 0, ',', '.') }}</div>
                            <div id="savings-badge" class="savings-badge">Tiết kiệm ₫{{ number_format($flashSaleProduct->original_price - $flashSaleProduct->sale_price, 0, ',', '.') }}</div>
                        </div>

                        <!-- Stock Progress -->
                        <div class="stock-progress-section">
                            <div class="stock-header">
                                <span class="sold-count">🔥 {{ $flashSaleProduct->initial_stock - $flashSaleProduct->remaining_stock }} đã mua</span>
                                <span class="remaining-count">Còn lại: {{ $flashSaleProduct->remaining_stock }}</span>
                            </div>
                            @php
                                $soldPercentage = (($flashSaleProduct->initial_stock - $flashSaleProduct->remaining_stock) / $flashSaleProduct->initial_stock) * 100;
                            @endphp
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $soldPercentage }}%"></div>
                            </div>
                        </div>

                        <!-- Variant Selection -->
                        <div class="variant-selection">
                            <!-- Color Selection -->
                            <div class="variant-group">
                                <label class="variant-label">Màu sắc:</label>
                                <div class="variant-options">
                                    @foreach($colors as $color)
                                    <label class="variant-option">
                                        <input type="radio" name="color" value="{{ $color->id }}" @if($loop->first) checked @endif>
                                        <span class="option-text">{{ $color->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Capacity Selection -->
                            <div class="variant-group">
                                <label class="variant-label">Dung lượng:</label>
                                <div class="variant-options">
                                    @foreach($capacities as $capacity)
                                    <label class="variant-option">
                                        <input type="radio" name="capacity" value="{{ $capacity->id }}" @if($loop->first) checked @endif>
                                        <span class="option-text">{{ $capacity->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Quantity Selection -->
                        <div class="quantity-section">
                            <label class="quantity-label">Số lượng:</label>
                            <div class="quantity-controls">
                                <button type="button" class="qty-btn" onclick="decreaseQuantity()">-</button>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $flashSaleProduct->remaining_stock }}" class="qty-input">
                                <button type="button" class="qty-btn" onclick="increaseQuantity()">+</button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button type="button" class="btn-buy-now" onclick="buyNow()">
                                <i class="zmdi zmdi-shopping-cart"></i>
                                Mua ngay
                            </button>
                            <button type="button" class="btn-add-cart" onclick="addToCart()">
                                <i class="zmdi zmdi-shopping-cart"></i>
                                Thêm vào giỏ
                            </button>
                            <button type="button" class="btn-favorite" onclick="addToWishlist()">
                                <i class="zmdi zmdi-favorite"></i>
                            </button>
                        </div>

                        <!-- Social Share -->
                        <div class="social-share">
                            <span class="share-label">Chia sẻ:</span>
                            <div class="share-buttons">
                                <a href="#" class="share-btn facebook"><i class="zmdi zmdi-facebook"></i></a>
                                <a href="#" class="share-btn twitter"><i class="zmdi zmdi-twitter"></i></a>
                                <a href="#" class="share-btn pinterest"><i class="zmdi zmdi-pinterest"></i></a>
                                <a href="#" class="share-btn instagram"><i class="zmdi zmdi-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Details Tabs -->
<section class="product-tabs-section">
    <div class="container">
        <div class="product-tabs-container">
            <div class="tabs-navigation">
                <button class="tab-btn active" onclick="switchTab('description')">Mô tả sản phẩm</button>
                <button class="tab-btn" onclick="switchTab('reviews')">Đánh giá (0)</button>
            </div>
            
            <div class="tab-content">
                <div id="description-tab" class="tab-panel active">
                    <div class="product-description">
                        {!! $product->description !!}
                    </div>
                </div>
                
                <div id="reviews-tab" class="tab-panel">
                    <div class="product-reviews">
                        <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Flash Sale Products Section - Shopee Style -->
@if($otherFlashSaleProducts->count() > 0)
<section class="shopee-flash-sale-section">
    <div class="container">
        <!-- Shopee-style Header -->
        <div class="shopee-section-header">
            <div class="header-left">
                <div class="flash-sale-logo">
                    <span class="flash-text">FLASH SALE</span>
                    <div class="lightning-bolt">⚡</div>
                </div>
                <div class="countdown-header" data-end-time="{{ $otherFlashSaleProducts->first()->flashSale->end_time->toISOString() }}">
                    <span class="countdown-label">KẾT THÚC TRONG</span>
                    <div class="countdown-display">
                        <span class="time-box" id="header-hours">00</span>
                        <span class="separator">:</span>
                        <span class="time-box" id="header-minutes">00</span>
                        <span class="separator">:</span>
                        <span class="time-box" id="header-seconds">00</span>
                    </div>
                </div>
            </div>
            <div class="header-right">
                <a href="{{ route('flash-sales') }}" class="view-all-btn">
                    Xem tất cả
                    <i class="zmdi zmdi-chevron-right"></i>
                </a>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="shopee-flash-grid">
            @foreach($otherFlashSaleProducts->take(6) as $flashProduct)
                @php
                    $fsProduct = $flashProduct->productVariant->product;
                    $fsVariant = $fsProduct->variants->first();
                    $soldCount = $flashProduct->initial_stock - $flashProduct->remaining_stock;
                    $soldPercentage = ($soldCount / $flashProduct->initial_stock) * 100;
                @endphp
                <div class="shopee-flash-card" onclick="window.location.href='{{ route('flash-sale.product.detail', $fsProduct->slug) }}'">
                    <!-- Image Container -->
                    <div class="shopee-image-container">
                        <img src="{{ asset($fsVariant->image ?? '') }}" alt="{{ $fsProduct->name }}" class="shopee-product-image">
                        
                        <!-- Discount Badge -->
                        @if($flashProduct->getDiscountPercentage() > 0)
                        <div class="shopee-discount-badge">
                            <span class="discount-percent">{{ $flashProduct->getDiscountPercentage() }}%</span>
                            <span class="discount-text">GIẢM</span>
                        </div>
                        @endif
                        
                        <!-- Hot Label -->
                        @if($soldPercentage > 50)
                        <div class="hot-label">
                            <span>🔥 HOT</span>
                        </div>
                        @endif
                        
                        <!-- Favorite Button -->
                        <button class="shopee-favorite-btn" onclick="event.stopPropagation(); toggleFavorite({{ $fsProduct->id }})">
                            <i class="zmdi zmdi-favorite-outline"></i>
                        </button>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="shopee-product-info">
                        <h3 class="shopee-product-title">{{ Str::limit($fsProduct->name, 40) }}</h3>
                        
                        <!-- Price Section -->
                        <div class="shopee-price-section">
                            @if($fsVariant && $fsVariant->price_sale)
                                <span class="current-price">₫{{ number_format($fsVariant->price_sale, 0, ',', '.') }}</span>
                                <span class="old-price">₫{{ number_format($fsVariant->price, 0, ',', '.') }}</span>
                            @else
                                <span class="current-price">₫{{ number_format($fsVariant->price ?? 0, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        
                        <!-- Sales Count -->
                        <div class="flash-sales-count">
                            Đã bán {{ $soldCount }}
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="shopee-progress-container">
                            <div class="shopee-progress-bar">
                                <div class="progress-track">
                                    <div class="progress-fill" style="width: {{ min($soldPercentage, 100) }}%"></div>
                                    <div class="progress-thumb" style="left: {{ min($soldPercentage, 100) }}%">
                                        <div class="fire-icon">🔥</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Rating & Location -->
                        <div class="shopee-meta">
                            <div class="rating-section">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= 4 ? 'filled' : '' }}">★</span>
                                    @endfor
                                </div>
                                <span class="rating-count">({{ rand(100, 999) }})</span>
                            </div>
                            <div class="location">TP. Hồ Chí Minh</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Related Products Section - Shopee Style -->
@if($relatedProducts->count() > 0)
<section class="shopee-related-section">
    <div class="container">
        <!-- Section Header -->
        <div class="shopee-related-header">
            <h2 class="related-title">CÓ THỂ BẠN CŨNG THÍCH</h2>
            <div class="title-underline"></div>
        </div>
        
        <!-- Products Grid -->
        <div class="shopee-related-grid">
            @foreach($relatedProducts->take(10) as $relatedProduct)
                @php
                    $relatedVariant = $relatedProduct->variants->first();
                    $hasDiscount = $relatedVariant && $relatedVariant->sale_price < $relatedVariant->price;
                    $discountPercent = $hasDiscount ? round((($relatedVariant->price - $relatedVariant->sale_price) / $relatedVariant->price) * 100) : 0;
                @endphp
                <div class="shopee-related-card" onclick="window.location.href='{{ route('product.detail', $relatedProduct->slug) }}'">
                    <!-- Image Container -->
                    <div class="shopee-related-image">
                        <img src="{{ asset($relatedVariant->image ?? '') }}" alt="{{ $relatedProduct->name }}" class="related-product-image">
                        
                        
                        
                        <!-- Favorite Button -->
                        <button class="related-favorite-btn" onclick="event.stopPropagation(); toggleFavorite({{ $relatedProduct->id }})">
                            <i class="zmdi zmdi-favorite-outline"></i>
                        </button>
                        
                    </div>
                    
                    <!-- Product Info -->
                    <div class="shopee-related-info">
                        <h3 class="related-product-title">{{ Str::limit($relatedProduct->name, 50) }}</h3>
                        
                        <!-- Price Section -->
                        <div class="related-price-section">
                            @if($relatedVariant)
                                @if($relatedVariant->price_sale && $relatedVariant->price_sale < $relatedVariant->price)
                                    <div class="price-row">
                                        <span class="current-price">₫{{ number_format($relatedVariant->price_sale, 0, ',', '.') }}</span>
                                        <span class="old-price">₫{{ number_format($relatedVariant->price, 0, ',', '.') }}</span>
                                    </div>
                                @else
                                    <div class="price-row">
                                        <span class="current-price">₫{{ number_format($relatedVariant->price, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            @else
                                <div class="price-row">
                                    <span class="current-price">₫0</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Sales Info -->
                        <div class="related-meta">
                            @php
                                // Tính tổng lượt xem sản phẩm làm chỉ số "đã bán"
                                $viewCount = $relatedProduct->view_count ?? 0;
                                $estimatedSold = max(0, intval($viewCount * 0.1)); // 10% conversion rate
                            @endphp
                            @if($estimatedSold > 0)
                                <div class="sales-count">Đã bán {{ $estimatedSold }}</div>
                            @endif
                        </div>
                        
                        
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Recently Viewed Section -->
<section class="recently-viewed-section" id="recently-viewed" style="display: none;">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <span class="history-icon">👁️</span>
                Sản Phẩm Đã Xem
                <span class="section-subtitle">Lịch sử duyệt web của bạn</span>
            </h2>
        </div>
        
        <div class="recently-viewed-grid" id="recently-viewed-grid">
            <!-- Sẽ được populate bởi JavaScript -->
        </div>
    </div>
</section>

<!-- Modern Shopee-like Styles -->
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

/* Main Product Section */
.product-detail-section {
    background: #fff;
    padding: 20px 0;
}

.product-detail-container {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

/* Product Images */
.product-images-container {
    padding: 20px;
}

.main-image-wrapper {
    position: relative;
    background: #f8f8f8;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 15px;
}

.main-product-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 8px;
}

.flash-sale-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: linear-gradient(45deg, #ee4d2d, #ff6b35);
    color: white;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
    box-shadow: 0 2px 8px rgba(238, 77, 45, 0.3);
}

.badge-text {
    display: block;
    font-size: 10px;
}

.discount-percent {
    font-size: 14px;
    font-weight: 800;
}

.thumbnail-gallery {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.thumbnail-item {
    width: 60px;
    height: 60px;
    border-radius: 6px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: border-color 0.2s;
}

.thumbnail-item:hover {
    border-color: #ee4d2d;
}

.thumbnail-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Product Info */
.product-info-container {
    padding: 20px;
}

/* Flash Sale Countdown Compact */
.flash-sale-countdown-compact {
    background: linear-gradient(135deg, #ff6b35, #ee4d2d);
    color: white;
    padding: 12px 16px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(238, 77, 45, 0.2);
}

.flash-sale-label-compact {
    font-size: 14px;
    font-weight: 600;
}

.countdown-timer-compact {
    font-size: 16px;
    font-weight: 700;
    font-family: 'Courier New', monospace;
}

.countdown-timer-compact span {
    background: rgba(255, 255, 255, 0.2);
    padding: 4px 6px;
    border-radius: 3px;
    margin: 0 1px;
}

/* Product Title */
.product-title {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
    line-height: 1.3;
}

/* Product Meta */
.product-meta {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.brand-badge {
    background: #ee4d2d;
    color: white;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.view-count {
    color: #666;
    font-size: 14px;
}

/* Price Section */
.price-section {
    margin-bottom: 20px;
}

.current-price {
    font-size: 32px;
    font-weight: 700;
    color: #ee4d2d;
    margin-bottom: 5px;
}

.original-price {
    font-size: 18px;
    color: #999;
    text-decoration: line-through;
    margin-bottom: 8px;
}

.savings-badge {
    background: #d4edda;
    color: #155724;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    display: inline-block;
}

/* Stock Progress */
.stock-progress-section {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.stock-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 14px;
}

.sold-count {
    color: #ee4d2d;
    font-weight: 600;
}

.remaining-count {
    color: #666;
}

.progress-bar {
    height: 6px;
    background: #e9ecef;
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #ee4d2d, #ff6b35);
    transition: width 0.3s ease;
}

/* Variant Selection */
.variant-selection {
    margin-bottom: 20px;
}

.variant-group {
    margin-bottom: 15px;
}

.variant-label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.variant-options {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.variant-option {
    position: relative;
    cursor: pointer;
}

.variant-option input[type="radio"] {
    display: none;
}

.option-text {
    display: block;
    padding: 8px 16px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fff;
    transition: all 0.2s;
    font-size: 14px;
}

.variant-option input[type="radio"]:checked + .option-text {
    border-color: #ee4d2d;
    background: #fff5f5;
    color: #ee4d2d;
}

.variant-option input[type="radio"]:disabled + .option-text {
    opacity: 0.4;
    cursor: not-allowed;
    background: #f8f9fa;
    color: #999;
    border-color: #e9ecef;
}

.variant-option input[type="radio"]:disabled + .option-text:hover {
    background: #f8f9fa;
    color: #999;
    border-color: #e9ecef;
}

/* Quantity Section */
.quantity-section {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.quantity-label {
    font-weight: 600;
    color: #333;
}

.quantity-controls {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    overflow: hidden;
}

.qty-btn {
    background: #f8f9fa;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.2s;
    width: 40px;
    height: 40px;
}

.qty-btn:hover {
    background: #e9ecef;
}

.qty-input {
    border: none;
    text-align: center;
    width: 60px;
    height: 40px;
    font-weight: 600;
    font-size: 16px;
}

.qty-input:focus {
    outline: none;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}

.btn-buy-now {
    flex: 1;
    background: #ee4d2d;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-buy-now:hover {
    background: #d73502;
}

.btn-add-cart {
    flex: 1;
    background: #fff;
    color: #ee4d2d;
    border: 1px solid #ee4d2d;
    padding: 12px 20px;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-add-cart:hover {
    background: #ee4d2d;
    color: white;
}

.btn-favorite {
    background: #fff;
    color: #666;
    border: 1px solid #ddd;
    padding: 12px;
    border-radius: 4px;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.2s;
    min-width: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-favorite:hover {
    background: #fff5f5;
    color: #ee4d2d;
    border-color: #ee4d2d;
}

/* Social Share */
.social-share {
    display: flex;
    align-items: center;
    gap: 10px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
}

.share-label {
    font-size: 14px;
    color: #666;
}

.share-buttons {
    display: flex;
    gap: 8px;
}

.share-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: white;
    font-size: 14px;
    transition: transform 0.2s;
}

.share-btn:hover {
    transform: scale(1.1);
}

.share-btn.facebook { background: #3b5998; }
.share-btn.twitter { background: #1da1f2; }
.share-btn.pinterest { background: #bd081c; }
.share-btn.instagram { background: #e4405f; }

/* Product Tabs */
.product-tabs-section {
    background: #fff;
    padding: 20px 0;
    margin-top: 20px;
}

.product-tabs-container {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.tabs-navigation {
    display: flex;
    border-bottom: 1px solid #e0e0e0;
}

.tab-btn {
    background: none;
    border: none;
    padding: 15px 25px;
    font-size: 16px;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    transition: all 0.2s;
    border-bottom: 2px solid transparent;
}

.tab-btn.active {
    color: #ee4d2d;
    border-bottom-color: #ee4d2d;
}

.tab-btn:hover {
    color: #ee4d2d;
}

.tab-content {
    padding: 20px;
}

.tab-panel {
    display: none;
}

.tab-panel.active {
    display: block;
}

.product-description {
    line-height: 1.6;
    color: #333;
}

.product-reviews {
    text-align: center;
    color: #666;
    padding: 40px 0;
}

/* New Sections Styles */

/* Section Headers */
.section-header {
    text-align: center;
    margin-bottom: 40px;
}

.section-title {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.section-title .flash-icon,
.section-title .related-icon,
.section-title .history-icon {
    font-size: 32px;
    animation: pulse 2s infinite;
}

.section-subtitle {
    display: block;
    font-size: 14px;
    color: #666;
    font-weight: 400;
    margin-left: 44px;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* Shopee Flash Sale Section */
.shopee-flash-sale-section {
    background: linear-gradient(135deg, #ee4d2d, #ff6b35);
    padding: 30px 0;
    margin-top: 40px;
    position: relative;
    overflow: hidden;
}

.shopee-flash-sale-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="flash" patternUnits="userSpaceOnUse" width="20" height="20"><path d="M10 0L15 10L10 20L5 10Z" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23flash)"/></svg>');
    opacity: 0.3;
}

.shopee-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 25px;
    position: relative;
    z-index: 2;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.flash-sale-logo {
    display: flex;
    align-items: center;
    gap: 8px;
}

.flash-text {
    font-size: 24px;
    font-weight: 800;
    color: white;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    letter-spacing: 1px;
}

.lightning-bolt {
    font-size: 28px;
    animation: flash 1.5s infinite;
}

@keyframes flash {
    0%, 50%, 100% { opacity: 1; transform: scale(1); }
    25%, 75% { opacity: 0.7; transform: scale(1.1); }
}

.countdown-header {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.countdown-label {
    color: white;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 5px;
    opacity: 0.9;
}

.countdown-display {
    display: flex;
    align-items: center;
    gap: 2px;
}

.time-box {
    background: rgba(255,255,255,0.9);
    color: #ee4d2d;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 700;
    font-size: 16px;
    min-width: 32px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.separator {
    color: white;
    font-weight: 700;
    font-size: 16px;
    margin: 0 2px;
}

.view-all-btn {
    background: rgba(255,255,255,0.2);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
    border: 1px solid rgba(255,255,255,0.3);
}

.view-all-btn:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-1px);
    color: white;
}

.shopee-flash-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    position: relative;
    z-index: 2;
}

.shopee-flash-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}

.shopee-flash-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.shopee-image-container {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.shopee-product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.shopee-flash-card:hover .shopee-product-image {
    transform: scale(1.05);
}

.shopee-discount-badge {
    position: absolute;
    top: 0;
    right: 0;
    background: #ffbf00;
    color: #ee4d2d;
    padding: 4px 8px;
    font-size: 11px;
    font-weight: 700;
    border-bottom-left-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.discount-percent {
    display: block;
    font-size: 13px;
}

.discount-text {
    font-size: 9px;
}

.hot-label {
    position: absolute;
    top: 8px;
    left: 8px;
    background: linear-gradient(45deg, #ff4757, #ff3838);
    color: white;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 600;
    animation: pulse 2s infinite;
}

.shopee-favorite-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(0,0,0,0.5);
    color: white;
    border: none;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    opacity: 0;
}

.shopee-flash-card:hover .shopee-favorite-btn {
    opacity: 1;
}

.shopee-favorite-btn:hover {
    background: #ee4d2d;
    transform: scale(1.1);
}

.shopee-product-info {
    padding: 12px;
}

.shopee-product-title {
    font-size: 14px;
    color: #333;
    margin-bottom: 8px;
    line-height: 1.3;
    height: 36px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.shopee-price-section .current-price {
    color: #ee4d2d;
    font-size: 16px;
    font-weight: 600;
    margin-right: 6px;
}

.shopee-price-section .original-price {
    color: #999;
    font-size: 12px;
    text-decoration: line-through;
}

.shopee-progress-container {
    margin: 8px 0;
}

.progress-label {
    margin-bottom: 4px;
}

.sold-label {
    color: #ee4d2d;
    font-size: 11px;
    font-weight: 600;
}

.shopee-progress-bar {
    position: relative;
}

.progress-track {
    height: 12px;
    background: #f5f5f5;
    border-radius: 6px;
    overflow: hidden;
    position: relative;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #ee4d2d, #ff6b35);
    border-radius: 6px;
    transition: width 0.3s ease;
}

.progress-thumb {
    position: absolute;
    top: -2px;
    transform: translateX(-50%);
    width: 16px;
    height: 16px;
    background: white;
    border: 2px solid #ee4d2d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.fire-icon {
    font-size: 8px;
}

.shopee-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 8px;
    font-size: 12px;
}

.rating-section {
    display: flex;
    align-items: center;
    gap: 4px;
}

.stars .star {
    color: #ddd;
    font-size: 12px;
}

.stars .star.filled {
    color: #ffce3d;
}

.rating-count {
    color: #999;
}

.location {
    color: #999;
    font-size: 11px;
}

/* Shopee Related Products Section */
.shopee-related-section {
    background: #f5f5f5;
    padding: 40px 0;
    margin-top: 20px;
}

.shopee-related-header {
    text-align: center;
    margin-bottom: 30px;
}

.related-title {
    font-size: 20px;
    font-weight: 700;
    color: #333;
    margin-bottom: 8px;
    letter-spacing: 0.5px;
}

.title-underline {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #ee4d2d, #ff6b35);
    margin: 0 auto;
    border-radius: 2px;
}

.shopee-related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
    gap: 12px;
}

.shopee-related-card {
    background: white;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
    cursor: pointer;
    position: relative;
}

.shopee-related-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.shopee-related-image {
    position: relative;
    height: 190px;
    overflow: hidden;
}

.related-product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.shopee-related-card:hover .related-product-image {
    transform: scale(1.03);
}



.related-favorite-btn {
    position: absolute;
    top: 6px;
    right: 6px;
    background: rgba(0,0,0,0.4);
    color: white;
    border: none;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    opacity: 0;
    font-size: 12px;
}

.shopee-related-card:hover .related-favorite-btn {
    opacity: 1;
}

.related-favorite-btn:hover {
    background: #ee4d2d;
    transform: scale(1.1);
}


.shopee-related-info {
    padding: 8px;
    text-align: center;
}

.related-product-title {
    font-size: 13px;
    color: #333;
    margin-bottom: 6px;
    line-height: 1.3;
    height: 32px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.related-price-section .current-price {
    color: #ee4d2d;
    font-size: 14px;
    font-weight: 600;
    margin-right: 4px;
}

.related-price-section .old-price {
    color: #999;
    font-size: 11px;
    text-decoration: line-through;
}

.related-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 4px 0;
    font-size: 11px;
}


.sales-count {
    color: #999;
    font-size: 10px;
}

.flash-sales-count {
    color: #666;
    font-size: 11px;
    margin: 4px 0;
    font-weight: 500;
}

.shipping-info {
    margin: 4px 0;
    font-size: 10px;
}

.free-ship {
    color: #00bfa5;
    font-weight: 500;
}

.fast-delivery {
    color: #ff9800;
    font-weight: 500;
}


/* Recently Viewed Section */
.recently-viewed-section {
    background: #f8f9fa;
    padding: 60px 0;
    margin-top: 40px;
}

.recently-viewed-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .product-title {
        font-size: 20px;
    }
    
    .current-price {
        font-size: 28px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .main-product-image {
        height: 300px;
    }
    
    .flash-sale-countdown-compact {
        flex-direction: column;
        gap: 8px;
        text-align: center;
    }
    
    .product-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .section-title {
        font-size: 24px;
        flex-direction: column;
        gap: 8px;
    }
    
    .section-subtitle {
        margin-left: 0;
    }
    
    .flash-sale-grid,
    .related-products-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
    
    .recently-viewed-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
}
</style>

<!-- JavaScript -->
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

// Prompt: ask user to login with explicit button
function showLoginPrompt(redirectPath, purpose) {
    const overlay = document.createElement('div');
    overlay.style.position = 'fixed';
    overlay.style.inset = '0';
    overlay.style.background = 'rgba(0,0,0,0.45)';
    overlay.style.zIndex = '10000';
    overlay.style.display = 'flex';
    overlay.style.alignItems = 'center';
    overlay.style.justifyContent = 'center';

    const box = document.createElement('div');
    box.style.background = '#fff';
    box.style.borderRadius = '10px';
    box.style.boxShadow = '0 10px 30px rgba(0,0,0,0.2)';
    box.style.padding = '20px 22px';
    box.style.maxWidth = '440px';
    box.style.width = 'calc(100% - 40px)';
    box.style.textAlign = 'center';

    const title = document.createElement('div');
    title.textContent = (purpose === 'favorite')
        ? 'Bạn cần đăng nhập để thêm vào yêu thích'
        : (purpose === 'cart'
            ? 'Bạn cần đăng nhập để thêm vào giỏ hàng'
            : 'Bạn cần đăng nhập để tiếp tục mua hàng');
    title.style.fontWeight = '700';
    title.style.fontSize = '16px';
    title.style.marginBottom = '8px';

    const desc = document.createElement('div');
    desc.textContent = (purpose === 'favorite')
        ? 'Đăng nhập để lưu sản phẩm vào danh sách yêu thích.'
        : (purpose === 'cart'
            ? 'Đăng nhập để thêm sản phẩm vào giỏ hàng.'
            : 'Vui lòng đăng nhập để chuyển đến trang thanh toán.');
    desc.style.color = '#555';
    desc.style.marginBottom = '16px';

    const actions = document.createElement('div');
    actions.style.display = 'flex';
    actions.style.gap = '10px';
    actions.style.justifyContent = 'center';

    const loginBtn = document.createElement('a');
    const target = typeof redirectPath === 'string' && redirectPath.startsWith('/') ? redirectPath : '/checkout';
    loginBtn.href = '/login?redirect=' + encodeURIComponent(target);
    loginBtn.textContent = 'Đăng nhập';
    loginBtn.style.background = '#ee4d2d';
    loginBtn.style.color = '#fff';
    loginBtn.style.padding = '10px 16px';
    loginBtn.style.borderRadius = '6px';
    loginBtn.style.textDecoration = 'none';
    loginBtn.style.fontWeight = '600';

    const cancelBtn = document.createElement('button');
    cancelBtn.textContent = 'Để sau';
    cancelBtn.style.background = '#f1f3f5';
    cancelBtn.style.border = '1px solid #dee2e6';
    cancelBtn.style.color = '#333';
    cancelBtn.style.padding = '10px 16px';
    cancelBtn.style.borderRadius = '6px';
    cancelBtn.style.cursor = 'pointer';
    cancelBtn.onclick = () => document.body.removeChild(overlay);

    actions.appendChild(loginBtn);
    actions.appendChild(cancelBtn);
    box.appendChild(title);
    box.appendChild(desc);
    box.appendChild(actions);
    overlay.appendChild(box);
    document.body.appendChild(overlay);
}
document.addEventListener('DOMContentLoaded', function() {
    // Countdown Timer for compact version
    const countdownTimer = document.querySelector('.countdown-timer-compact');
    if (countdownTimer) {
        const endTime = new Date(countdownTimer.dataset.endTime).getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance < 0) {
                document.getElementById('compact-hours').textContent = '00';
                document.getElementById('compact-minutes').textContent = '00';
                document.getElementById('compact-seconds').textContent = '00';
                return;
            }
            
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById('compact-hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('compact-minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('compact-seconds').textContent = seconds.toString().padStart(2, '0');
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
});

// Change main image function
function changeMainImage(imageSrc) {
    document.getElementById('product-image').src = imageSrc;
}

// Quantity controls
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const maxQuantity = parseInt(quantityInput.getAttribute('max'));
    const currentQuantity = parseInt(quantityInput.value);
    
    if (currentQuantity < maxQuantity) {
        quantityInput.value = currentQuantity + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentQuantity = parseInt(quantityInput.value);
    
    if (currentQuantity > 1) {
        quantityInput.value = currentQuantity - 1;
    }
}

// Tab switching
function switchTab(tabName) {
    // Remove active class from all tabs and panels
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));
    
    // Add active class to clicked tab and corresponding panel
    event.target.classList.add('active');
    document.getElementById(tabName + '-tab').classList.add('active');
}

// Get variant data from server via AJAX
function getVariantData(colorId, capacityId) {
    return fetch('{{ route("get.variant") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: {{ $product->id }},
            color_id: colorId,
            capacity_id: capacityId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            return {
                image: data.variant.image,
                price: data.variant.sale_price || {{ $flashSaleProduct->sale_price }},
                originalPrice: data.variant.original_price || {{ $flashSaleProduct->original_price }},
                stock: data.variant.quantity || {{ $flashSaleProduct->remaining_stock }}
            };
        }
        return null;
    })
    .catch(error => {
        console.error('Error fetching variant:', error);
        return null;
    });
}

// Available combinations (for disabling options)
const availableCombinations = {
    @foreach($variants as $variant)
    '{{ $variant->color_id }}_{{ $variant->capacity_id }}': true,
    @endforeach
};

// Map to find variantId by color-capacity
const variantIdMap = {
    @foreach($variants as $variant)
    '{{ $variant->color_id }}_{{ $variant->capacity_id }}': {{ $variant->id }},
    @endforeach
};

// Auth flag from server
const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

// Update capacity options based on selected color
function updateCapacityOptions() {
    const selectedColor = document.querySelector('input[name="color"]:checked').value;
    const capacityInputs = document.querySelectorAll('input[name="capacity"]');
    const currentCapacity = document.querySelector('input[name="capacity"]:checked');
    let hasAvailableCapacity = false;
    
    capacityInputs.forEach(input => {
        const capacityId = input.value;
        const combinationKey = selectedColor + '_' + capacityId;
        const isAvailable = availableCombinations[combinationKey];
        
        const label = input.nextElementSibling;
        
        if (isAvailable) {
            input.disabled = false;
            label.style.opacity = '1';
            label.style.cursor = 'pointer';
            label.classList.remove('disabled');
            hasAvailableCapacity = true;
        } else {
            input.disabled = true;
            label.style.opacity = '0.4';
            label.style.cursor = 'not-allowed';
            label.classList.add('disabled');
        }
    });
    
    // If current capacity is not available, select the first available one
    if (currentCapacity && currentCapacity.disabled) {
        const firstAvailable = document.querySelector('input[name="capacity"]:not(:disabled)');
        if (firstAvailable) {
            firstAvailable.checked = true;
        }
    }
}

// Update product when variant changes
async function updateProductVariant() {
    const selectedColor = document.querySelector('input[name="color"]:checked');
    const selectedCapacity = document.querySelector('input[name="capacity"]:checked');
    
    if (!selectedColor || !selectedCapacity) {
        console.log('No color or capacity selected');
        return;
    }
    
    console.log('Fetching variant for:', selectedColor.value, selectedCapacity.value);
    
    try {
        const variant = await getVariantData(selectedColor.value, selectedCapacity.value);
        
        if (variant) {
            console.log('Found variant:', variant);
            
            // Update image
            document.getElementById('product-image').src = variant.image;
            
            // Update price
            document.getElementById('current-price').textContent = '₫' + (variant.price ?? variant.sale_price).toLocaleString('vi-VN');
            document.getElementById('original-price').textContent = '₫' + (variant.originalPrice ?? variant.price ?? variant.sale_price).toLocaleString('vi-VN');
            
            // Update savings
            const vPrice = (variant.price ?? variant.sale_price) * 1;
            const vOriginal = (variant.originalPrice ?? variant.price ?? variant.sale_price) * 1;
            const savings = Math.max(0, vOriginal - vPrice);
            document.getElementById('savings-badge').textContent = 'Tiết kiệm ₫' + savings.toLocaleString('vi-VN');
            
            // Update stock
            document.querySelector('.remaining-count').textContent = 'Còn lại: ' + variant.stock;
            document.getElementById('quantity').setAttribute('max', variant.stock);
            
            // Update stock progress
            const soldCount = document.querySelector('.sold-count').textContent.match(/\d+/)[0];
            const totalStock = parseInt(soldCount) + variant.stock;
            const soldPercentage = (parseInt(soldCount) / totalStock) * 100;
            document.querySelector('.progress-fill').style.width = soldPercentage + '%';
        } else {
            console.log('Variant not found');
        }
    } catch (error) {
        console.error('Error updating variant:', error);
    }
}

// Add event listeners to variant options
document.addEventListener('DOMContentLoaded', function() {
    // Color change
    document.querySelectorAll('input[name="color"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updateCapacityOptions();
            updateProductVariant();
        });
    });
    
    // Capacity change
    document.querySelectorAll('input[name="capacity"]').forEach(radio => {
        radio.addEventListener('change', updateProductVariant);
    });
    
    // Initialize capacity options on page load
    updateCapacityOptions();
    
    // Initialize product variant on page load
    updateProductVariant();

    // Initialize countdown timers for flash sale cards
    initializeFlashSaleCountdowns();
    
    // Initialize header countdown
    initializeHeaderCountdown();
    
    // Save current product to recently viewed
    saveToRecentlyViewed();
    
    // Load and display recently viewed products
    loadRecentlyViewed();

    // Auto-add favorite after login if flagged
    try {
        if (isLoggedIn) {
            const pendingFav = localStorage.getItem('post_login_favorite');
            if (pendingFav) {
                const payload = JSON.parse(pendingFav);
                if (payload && Number(payload.product_id) === Number({{ $product->id }})) {
                    // Clear flag first to avoid loops
                    localStorage.removeItem('post_login_favorite');
                    // Perform favorite add silently
                    fetch('/favorites', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ product_id: {{ $product->id }} })
                    })
                    .then(res => res.json().catch(() => ({})))
                    .then(data => {
                        const favBtn = document.querySelector('.btn-favorite');
                        if (favBtn) { favBtn.classList.add('active'); }
                        if (data && data.success) {
                            showCenterNotice('Đã thêm vào danh sách yêu thích!', 'success');
                        } else if (data && data.message) {
                            showCenterNotice(data.message, 'success');
                        } else {
                            showCenterNotice('Đã thêm vào danh sách yêu thích!', 'success');
                        }
                    })
                    .catch(() => {});
                }
            }
        }
    } catch (_) {}
    // Auto-add favorite after login if flagged
    try {
        if (isLoggedIn) {
            const pendingFav = localStorage.getItem('post_login_favorite');
            if (pendingFav) {
                const payload = JSON.parse(pendingFav);
                if (payload && Number(payload.product_id) === Number({{ $product->id }})) {
                    localStorage.removeItem('post_login_favorite');
                    fetch('/favorites', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ product_id: {{ $product->id }} })
                    })
                    .then(res => res.json().catch(() => ({})))
                    .then(data => {
                        const favBtn = document.querySelector('.btn-favorite');
                        if (favBtn) { favBtn.classList.add('active'); }
                        showCenterNotice('Đã thêm vào danh sách yêu thích!', 'success');
                    })
                    .catch(() => {});
                }
            }
            // Auto-add to cart after login if flagged
            const pendingCart = localStorage.getItem('post_login_cart');
            if (pendingCart) {
                const c = JSON.parse(pendingCart);
                localStorage.removeItem('post_login_cart');
                if (c && c.variant_id) {
                    fetch('/api/add-to-cart', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            product_id: c.product_id,
                            product_variant_id: c.variant_id,
                            color_id: c.color_id,
                            capacity_id: c.capacity_id,
                            quantity: c.quantity || 1,
                            is_flash_sale: !!c.is_flash_sale,
                            flash_sale_id: c.flash_sale_id,
                            flash_sale_price: c.flash_sale_price
                        })
                    })
                    .then(res => res.json().catch(() => ({})))
                    .then(data => {
                        showCenterNotice('Đã thêm vào giỏ hàng!', 'success');
                    })
                    .catch(() => {});
                }
            }
        }
    } catch (_) {}

});

// Add to cart function
async function addToCart() {
    const quantity = parseInt(document.getElementById('quantity').value || '1', 10);
    const colorInput = document.querySelector('input[name="color"]:checked');
    const capacityInput = document.querySelector('input[name="capacity"]:checked');
    if (!colorInput || !capacityInput) {
        showCenterNotice('Vui lòng chọn đầy đủ màu sắc và dung lượng.', 'error');
        return;
    }

    const colorId = colorInput.value;
    const capacityId = capacityInput.value;
    const key = `${colorId}_${capacityId}`;
    const variantId = variantIdMap[key];

    if (!isLoggedIn) {
        // Lưu ý định thêm giỏ hàng để tự động thực hiện sau đăng nhập
        try {
            localStorage.setItem('post_login_cart', JSON.stringify({
                product_id: {{ $product->id }},
                variant_id: variantId,
                color_id: colorId,
                capacity_id: capacityId,
                quantity: quantity,
                is_flash_sale: true,
                flash_sale_id: {{ $flashSale->id }},
                flash_sale_price: {{ $flashSaleProduct->sale_price }},
                ts: Date.now()
            }));
        } catch (_) {}
        const currentPath = window.location.pathname + window.location.search;
        showLoginPrompt(currentPath, 'cart');
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
                product_id: {{ $product->id }},
                product_variant_id: variantId,
                color_id: colorId,
                capacity_id: capacityId,
                quantity: quantity,
                is_flash_sale: true,
                flash_sale_id: {{ $flashSale->id }},
                flash_sale_price: {{ $flashSaleProduct->sale_price }}
            })
        });
        if (res.status === 401) {
            const currentPath = window.location.pathname + window.location.search;
            showLoginPrompt(currentPath, 'cart');
            return;
        }
        const data = await res.json();
        if (data && data.success) {
            showCenterNotice('Đã thêm vào giỏ hàng!', 'success');
        } else {
            showCenterNotice(data?.message || 'Không thể thêm vào giỏ hàng.', 'error');
        }
    } catch (e) {
        showCenterNotice('Có lỗi xảy ra. Vui lòng thử lại.', 'error');
    }
}

// Add to wishlist function
function addToWishlist() {
    const productId = {{ $product->id }};
    if (typeof isLoggedIn !== 'undefined' && !isLoggedIn) {
        // Mark intent to auto-add after login and redirect back to this page
        try { localStorage.setItem('post_login_favorite', JSON.stringify({ product_id: productId, ts: Date.now() })); } catch (_) {}
        const currentPath = window.location.pathname + window.location.search;
        showLoginPrompt(currentPath, 'favorite');
        return;
    }
    fetch('/favorites', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(async (res) => {
        if (res.status === 401) {
            const currentPath = window.location.pathname + window.location.search;
            showLoginPrompt(currentPath, 'favorite');
            return null;
        }
        return res.json();
    })
    .then((data) => {
        if (!data) return;
        if (data.success) {
            const favBtn = document.querySelector('.btn-favorite');
            if (favBtn) {
                favBtn.classList.add('active');
            }
            showCenterNotice('Đã thêm vào danh sách yêu thích!', 'success');
        } else if (data.message) {
            showCenterNotice(data.message, 'error');
        }
    })
    .catch(() => showCenterNotice('Có lỗi xảy ra. Vui lòng thử lại.', 'error'));
}

// Buy Now: add to cart then go to checkout
async function buyNow() {
    const quantity = parseInt(document.getElementById('quantity').value || '1', 10);
    const colorInput = document.querySelector('input[name="color"]:checked');
    const capacityInput = document.querySelector('input[name="capacity"]:checked');
    if (!colorInput || !capacityInput) {
        showCenterNotice('Vui lòng chọn đầy đủ màu sắc và dung lượng.', 'error');
        return;
    }

    const colorId = colorInput.value;
    const capacityId = capacityInput.value;
    const key = `${colorId}_${capacityId}`;
    const variantId = variantIdMap[key];
    if (!variantId) {
        showCenterNotice('Phiên bản sản phẩm không khả dụng.', 'error');
        return;
    }

    // Build checkout_cart item for checkout page only (no server cart)
    const priceText = document.getElementById('current-price')?.textContent || '0';
    const priceNumber = parseInt(priceText.replace(/[^\d]/g, ''), 10) || 0;
    const item = {
        variant_id: variantId,
        quantity: quantity,
        price: priceNumber,
        name: `{{ addslashes($product->name) }}`,
        color: colorInput.nextElementSibling?.textContent?.trim() || '',
        capacity: capacityInput.nextElementSibling?.textContent?.trim() || '',
        image: document.getElementById('product-image')?.src || ''
    };
    localStorage.setItem('checkout_cart', JSON.stringify([item]));
    const existingUser = localStorage.getItem('checkout_user');
    if (!existingUser) {
        localStorage.setItem('checkout_user', JSON.stringify({
            fullname: '',
            email: '',
            phone: '',
            street: '',
            ward: '',
            district: '',
            city: '',
            note: '',
            payment: 'cod'
        }));
    }
    if (!isLoggedIn) {
        // Show prompt; only redirect if user confirms (to checkout)
        showLoginPrompt('/checkout');
        return;
    }
    window.location.href = '/checkout';
}

// Initialize countdown timers for flash sale cards
function initializeFlashSaleCountdowns() {
    const countdownElements = document.querySelectorAll('.countdown-mini');
    
    countdownElements.forEach(element => {
        const endTime = new Date(element.dataset.endTime).getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance < 0) {
                element.innerHTML = '<span class="time-unit">00</span>:<span class="time-unit">00</span>:<span class="time-unit">00</span>';
                return;
            }
            
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            element.innerHTML = `
                <span class="time-unit">${hours.toString().padStart(2, '0')}</span>:
                <span class="time-unit">${minutes.toString().padStart(2, '0')}</span>:
                <span class="time-unit">${seconds.toString().padStart(2, '0')}</span>
            `;
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
}

// Save current product to recently viewed
function saveToRecentlyViewed() {
    try {
        const product = {
            id: {{ $product->id }},
            name: `{{ addslashes($product->name) }}`,
            slug: `{{ $product->slug }}`,
            image: document.getElementById('product-image')?.src || '',
            price: document.getElementById('current-price')?.textContent || '',
            timestamp: Date.now()
        };
        
        let recentlyViewed = JSON.parse(localStorage.getItem('recently_viewed') || '[]');
        
        // Remove if already exists
        recentlyViewed = recentlyViewed.filter(item => item.id !== product.id);
        
        // Add to beginning
        recentlyViewed.unshift(product);
        
        // Keep only last 10 items
        recentlyViewed = recentlyViewed.slice(0, 10);
        
        localStorage.setItem('recently_viewed', JSON.stringify(recentlyViewed));
    } catch (e) {
        console.log('Could not save to recently viewed:', e);
    }
}

// Load and display recently viewed products
function loadRecentlyViewed() {
    try {
        const recentlyViewed = JSON.parse(localStorage.getItem('recently_viewed') || '[]');
        
        if (recentlyViewed.length > 1) { // More than current product
            const section = document.getElementById('recently-viewed');
            const grid = document.getElementById('recently-viewed-grid');
            
            // Filter out current product
            const otherProducts = recentlyViewed.filter(item => item.id !== {{ $product->id }});
            
            if (otherProducts.length > 0) {
                grid.innerHTML = otherProducts.slice(0, 6).map(product => `
                    <div class="product-card" onclick="window.location.href='/product/${product.slug}'">
                        <div class="product-image-wrapper">
                            <img src="${product.image}" alt="${product.name}" class="product-image">
                            <div class="product-overlay">
                                <button class="quick-view-btn" onclick="event.stopPropagation(); window.location.href='/product/${product.slug}'">
                                    <i class="zmdi zmdi-eye"></i>
                                    Xem chi tiết
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">${product.name}</h3>
                            <div class="product-price">
                                <span class="current-price">${product.price}</span>
                            </div>
                            <div class="product-rating">
                                <div class="stars">
                                    <span class="star filled">★</span>
                                    <span class="star filled">★</span>
                                    <span class="star filled">★</span>
                                    <span class="star filled">★</span>
                                    <span class="star">★</span>
                                </div>
                                <span class="rating-text">Đã xem gần đây</span>
                            </div>
                        </div>
                    </div>
                `).join('');
                
                section.style.display = 'block';
            }
        }
    } catch (e) {
        console.log('Could not load recently viewed:', e);
    }
}

// Initialize header countdown timer
function initializeHeaderCountdown() {
    const headerCountdown = document.querySelector('.countdown-header');
    if (headerCountdown) {
        const endTime = new Date(headerCountdown.dataset.endTime).getTime();
        
        function updateHeaderCountdown() {
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance < 0) {
                document.getElementById('header-hours').textContent = '00';
                document.getElementById('header-minutes').textContent = '00';
                document.getElementById('header-seconds').textContent = '00';
                return;
            }
            
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById('header-hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('header-minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('header-seconds').textContent = seconds.toString().padStart(2, '0');
        }
        
        updateHeaderCountdown();
        setInterval(updateHeaderCountdown, 1000);
    }
}

// Toggle favorite function for Shopee-style cards
function toggleFavorite(productId) {
    if (!isLoggedIn) {
        try { 
            localStorage.setItem('post_login_favorite', JSON.stringify({ 
                product_id: productId, 
                ts: Date.now() 
            })); 
        } catch (_) {}
        const currentPath = window.location.pathname + window.location.search;
        showLoginPrompt(currentPath, 'favorite');
        return;
    }
    
    fetch('/favorites', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Find all favorite buttons for this product and update them
            const favBtns = document.querySelectorAll(`[onclick*="toggleFavorite(${productId})"]`);
            favBtns.forEach(btn => {
                const icon = btn.querySelector('i');
                if (icon) {
                    icon.className = data.action === 'added' ? 'zmdi zmdi-favorite' : 'zmdi zmdi-favorite-outline';
                }
                btn.style.background = data.action === 'added' ? '#ee4d2d' : 'rgba(0,0,0,0.5)';
            });
            
            const message = data.action === 'added' ? 'Đã thêm vào yêu thích!' : 'Đã xóa khỏi yêu thích!';
            showCenterNotice(message, 'success');
        } else {
            showCenterNotice(data.message || 'Có lỗi xảy ra!', 'error');
        }
    })
    .catch(() => showCenterNotice('Có lỗi xảy ra. Vui lòng thử lại.', 'error'));
}

// Quick add to cart function
function quickAddToCart(productId) {
    if (!isLoggedIn) {
        const currentPath = window.location.pathname + window.location.search;
        showLoginPrompt(currentPath, 'cart');
        return;
    }
    
    // For quick add, we'll use the first available variant
    fetch(`/api/product/${productId}/quick-add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ quantity: 1 })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showCenterNotice('Đã thêm vào giỏ hàng!', 'success');
        } else {
            showCenterNotice(data.message || 'Không thể thêm vào giỏ hàng!', 'error');
        }
    })
    .catch(() => {
        showCenterNotice('Sản phẩm đã được thêm vào giỏ hàng!', 'success');
    });
}

// Quick view function (placeholder)
function quickView(productId) {
    showCenterNotice('Tính năng xem nhanh đang được phát triển!', 'success');
}
</script>

@endsection
