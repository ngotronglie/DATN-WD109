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
                            @php $seenColorIds = []; @endphp
                            @foreach($variants as $variant)
                                @if(!in_array($variant->color_id, $seenColorIds))
                                    @php $seenColorIds[] = $variant->color_id; @endphp
                                    <div class="thumbnail-item" onclick="changeMainImage('{{ asset($variant->image) }}')">
                                        <img src="{{ asset($variant->image) }}" alt="Thumbnail" class="thumbnail-image">
                                    </div>
                                @endif
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
                            <div class="countdown-timer-compact" data-end-time="{{ $flashSale->end_time->toIso8601String() }}">
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
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="999" class="qty-input">
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

/* On-sale marker for variant options */
.option-text.on-sale {
    position: relative;
    border-color: #ffae9a;
}
.option-text.on-sale::after {
    content: '⚡';
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ee4d2d;
    color: #fff;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    box-shadow: 0 2px 6px rgba(238,77,45,.35);
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

/* Hide unavailable capacity options for selected color */
.variant-option.hidden {
    display: none;
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

// Flash sale combinations map (only combos included in this flash sale)
const flashSaleCombinations = {
    @if(isset($flashSale))
        @foreach(($flashSale->flashSaleProducts ?? []) as $fsp)
            @php
                $pv = $fsp->productVariant;
            @endphp
            @if($pv && $pv->product_id === $product->id)
            '{{ $pv->color_id }}_{{ $pv->capacity_id }}': {
                variant_id: {{ $pv->id }},
                sale_price: {{ (float) $fsp->sale_price }},
                original_price: {{ (float) ($fsp->original_price ?? $pv->price ?? $pv->price_sale ?? $fsp->sale_price) }}
            },
            @endif
        @endforeach
    @endif
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
        const isOnSale = !!flashSaleCombinations[combinationKey];
        
        const label = input.nextElementSibling;
        const wrapper = input.closest('.variant-option') || input.parentElement;
        
        if (isAvailable) {
            input.disabled = false;
            label.style.opacity = '1';
            label.style.cursor = 'pointer';
            label.classList.remove('disabled');
            if (wrapper) wrapper.classList.remove('hidden');
            hasAvailableCapacity = true;
        } else {
            input.disabled = true;
            label.style.opacity = '0.4';
            label.style.cursor = 'not-allowed';
            label.classList.add('disabled');
            if (wrapper) wrapper.classList.add('hidden');
        }

        // Mark on-sale
        if (isOnSale && !input.disabled) {
            label.classList.add('on-sale');
        } else {
            label.classList.remove('on-sale');
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

// Update color options based on selected capacity (to show on-sale markers)
function updateColorOptions() {
    const selectedCapacity = document.querySelector('input[name="capacity"]:checked');
    if (!selectedCapacity) return;
    const capacityId = selectedCapacity.value;
    const colorInputs = document.querySelectorAll('input[name="color"]');
    colorInputs.forEach(input => {
        const colorId = input.value;
        const combinationKey = colorId + '_' + capacityId;
        const label = input.nextElementSibling;
        const isOnSale = !!flashSaleCombinations[combinationKey];
        if (isOnSale && !input.disabled) {
            label.classList.add('on-sale');
        } else {
            label.classList.remove('on-sale');
        }
    });
}

// Hide colors that have no capacity at all
function filterColorOptions() {
    const colorInputs = Array.from(document.querySelectorAll('input[name="color"]'));
    const capacityInputs = Array.from(document.querySelectorAll('input[name="capacity"]'));
    let selectedColor = document.querySelector('input[name="color"]:checked');
    let selectedWrapper = selectedColor ? (selectedColor.closest('.variant-option') || selectedColor.parentElement) : null;
    let selectedVisible = selectedWrapper ? !selectedWrapper.classList.contains('hidden') : false;

    colorInputs.forEach(input => {
        const colorId = input.value;
        const wrapper = input.closest('.variant-option') || input.parentElement;
        const hasAny = capacityInputs.some(cap => availableCombinations[colorId + '_' + cap.value]);
        if (hasAny) {
            if (wrapper) wrapper.classList.remove('hidden');
        } else {
            if (wrapper) wrapper.classList.add('hidden');
        }
    });

    if (!selectedVisible) {
        const firstVisible = colorInputs.find(inp => {
            const w = inp.closest('.variant-option') || inp.parentElement;
            return w && !w.classList.contains('hidden');
        });
        if (firstVisible) {
            firstVisible.checked = true;
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
            
            // Update quantity input max value
            document.getElementById('quantity').setAttribute('max', variant.stock);
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
            updateColorOptions();
            updateProductVariant();
        });
    });
    
    // Capacity change
    document.querySelectorAll('input[name="capacity"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updateColorOptions();
            updateProductVariant();
        });
    });
    
    // Initialize capacity options on page load
    filterColorOptions();
    updateCapacityOptions();
    updateColorOptions();
    
    // Initialize product variant on page load
    updateProductVariant();

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
</script>

@endsection
