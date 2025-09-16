@extends('index.clientdashboard')

@section('content')

<!-- BREADCRUMBS SETCTION START -->
<div class="breadcrumbs section plr-200 mb-80" style="margin-top: 15px;">
    <div class="breadcrumbs overlay-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumbs-inner">
                        <ul class=" d-flex align-items-center gap-2">
                            <li><a href="{{ route('home') }}">Trang chủ</a></li>
                            <li><span class="text-muted">/</span></li>
                            <li><a href="{{ route('flash-sales') }}">Flash Sale</a></li>
                            <li><span class="text-muted">/</span></li>
                            <li>{{ $product->name }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BREADCRUMBS SETCTION END -->

<!-- Start page content -->
<section id="page-content" class="page-wrapper section">

    <!-- SHOP SECTION START -->
    <div class="shop-section mb-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 order-lg-2 order-1">
                    <!-- single-product-area start -->
                    <div class="single-product-area mb-80">

                        <form class="form-submit" action="">
                            <div class="row">
                                <!-- imgs-zoom-area start -->
                                <div class="col-lg-8">
                                    <div class="imgs-zoom-area mb-3">
                                        <a href="{{ asset($variants[0]->image ?? '') }}" data-lightbox="product-gallery" data-title="{{ $product->name }}">
                                            <img id="product-image" src="{{ isset($variants[0]) ? asset($variants[0]->image) : '' }}" data-zoom-image="{{ isset($variants[0]) ? asset($variants[0]->image) : '' }}" alt="">

                                        </a>
                                        <!-- Flash Sale Badge -->
                                        <div class="flash-sale-badge">
                                            <span class="badge-text">⚡ FLASH SALE</span>
                                            <span class="discount-percent">-{{ $flashSaleProduct->getDiscountPercentage() }}%</span>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($variants as $variant)
                                        <div class="thumb-wrapper">
                                            <img src="{{ asset($variant->image) }}"
                                                class="thumb-image"
                                                onclick="document.getElementById('product-image').src = '{{ asset($variant->image) }}'">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- imgs-zoom-area end -->
                                <!-- single-product-info start -->
                                <div class="col-lg-4">
                                    <div class="single-product-info">
                                        <!-- Flash Sale Countdown -->
                                        <div class="flash-sale-countdown mb-3">
                                            <div class="countdown-header">
                                                <h4 class="text-danger mb-2">⚡ {{ $flashSale->name }}</h4>
                                                <p class="text-muted mb-2">Kết thúc sau:</p>
                                            </div>
                                            <div class="countdown-timer" data-end-time="{{ $flashSale->end_time->toISOString() }}">
                                                <div class="countdown-item">
                                                    <span id="countdown-hours">00</span>
                                                    <small>Giờ</small>
                                                </div>
                                                <div class="countdown-item">
                                                    <span id="countdown-minutes">00</span>
                                                    <small>Phút</small>
                                                </div>
                                                <div class="countdown-item">
                                                    <span id="countdown-seconds">00</span>
                                                    <small>Giây</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-9">
                                                <h3 class="text-black-1" id="product-name">{{ $product->name }}</h3>
                                                <h6 class="brand-name-2" id="category-name">{{ $product->category->Name ?? '' }}</h6>
                                            </div>
                                            <div class="col-md-3">
                                                <i class="zmdi zmdi-eye"></i> {{ $product->view_count }} lượt xem
                                            </div>
                                        </div>
                                        <!--  hr -->
                                        <hr>
                                        <!-- single-pro-color-rating -->
                                        <div class="single-pro-color-rating clearfix mb-2">
                                            <div class="sin-pro-color f-left">
                                                <span class="color-title f-left">Màu sắc:</span>
                                                <div class="color-radio-group" style="display:inline-block; margin-left:10px;">
                                                    @foreach($colors as $color)
                                                    <label class="color-radio-label">
                                                        <input type="radio" name="color" value="{{ $color->id }}" @if($loop->first) checked @endif>
                                                        <span class="color-radio-custom"></span>
                                                        {{ $color->name }}
                                                    </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="single-pro-color-rating clearfix mb-2">
                                            <div class="sin-pro-color f-left">
                                                <span class="color-title f-left">Dung lượng:</span>
                                                <div class="capacity-radio-group" style="display:inline-block; margin-left:10px;">
                                                    @foreach($capacities as $capacity)
                                                    <label class="capacity-radio-label">
                                                        <input type="radio" name="capacity" value="{{ $capacity->id }}" @if($loop->first) checked @endif>
                                                        <span class="capacity-radio-custom"></span>
                                                        {{ $capacity->name }}
                                                    </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- single-pro-price -->
                                        <div class="single-pro-price">
                                            <div class="price-box">
                                                <div class="flash-sale-price mb-2">
                                                    <span class="flash-price text-danger" id="flash-price">₫{{ number_format($flashSaleProduct->sale_price, 0, ',', '.') }}</span>
                                                    <span class="original-price text-muted" id="original-price">₫{{ number_format($flashSaleProduct->original_price, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="savings-info">
                                                    <span class="savings-text">Tiết kiệm: ₫{{ number_format($flashSaleProduct->original_price - $flashSaleProduct->sale_price, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- single-pro-price end -->

                                        <!-- Flash Sale Stock Progress -->
                                        <div class="flash-sale-stock mb-3">
                                            <div class="stock-info-header">
                                                <span class="stock-sold">🔥 {{ $flashSaleProduct->initial_stock - $flashSaleProduct->remaining_stock }} đã mua</span>
                                                <span class="stock-remaining">Còn lại: {{ $flashSaleProduct->remaining_stock }}</span>
                                            </div>
                                            @php
                                                $soldPercentage = (($flashSaleProduct->initial_stock - $flashSaleProduct->remaining_stock) / $flashSaleProduct->initial_stock) * 100;
                                            @endphp
                                            <div class="stock-progress-bar">
                                                <div class="stock-progress" style="width: {{ $soldPercentage }}%"></div>
                                            </div>
                                        </div>

                                        <!-- single-pro-quantity -->
                                        <div class="single-pro-quantity mb-3">
                                            <div class="quantity-input-group">
                                                <label class="quantity-label">Số lượng:</label>
                                                <div class="quantity-controls">
                                                    <button type="button" class="quantity-btn" onclick="decreaseQuantity()">-</button>
                                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $flashSaleProduct->remaining_stock }}" class="quantity-input">
                                                    <button type="button" class="quantity-btn" onclick="increaseQuantity()">+</button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- single-pro-quantity end -->

                                        <!-- single-pro-btn -->
                                        <div class="single-pro-btn">
                                            <button type="button" class="btn btn-danger btn-lg w-100 mb-2" onclick="addToCart()">
                                                <i class="zmdi zmdi-shopping-cart"></i> Mua ngay Flash Sale
                                            </button>
                                            <button type="button" class="btn btn-outline-danger w-100" onclick="addToWishlist()">
                                                <i class="zmdi zmdi-favorite"></i> Thêm vào yêu thích
                                            </button>
                                        </div>
                                        <!-- single-pro-btn end -->

                                        <!-- single-pro-social -->
                                        <div class="single-pro-social">
                                            <span>Chia sẻ:</span>
                                            <a href="#"><i class="zmdi zmdi-facebook"></i></a>
                                            <a href="#"><i class="zmdi zmdi-twitter"></i></a>
                                            <a href="#"><i class="zmdi zmdi-pinterest"></i></a>
                                            <a href="#"><i class="zmdi zmdi-instagram"></i></a>
                                        </div>
                                        <!-- single-pro-social end -->
                                    </div>
                                </div>
                                <!-- single-product-info end -->
                            </div>
                        </form>
                    </div>
                    <!-- single-product-area end -->

                    <!-- product-details-tab start -->
                    <div class="product-details-tab">
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Mô tả sản phẩm</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Đánh giá (0)</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                        <div class="product-description">
                                            {!! $product->description !!}
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                        <div class="product-reviews">
                                            <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- product-details-tab end -->
                </div>
                <!-- sidebar start -->
                <div class="col-lg-3 order-lg-1 order-2">
                    <div class="sidebar">
                        <div class="widget">
                            <h3 class="widget-title">Danh mục sản phẩm</h3>
                            <ul class="widget-list">
                                @foreach($categories as $category)
                                    @if($category->slug)
                                        <li><a href="{{ route('category', $category->slug) }}">{{ $category->Name }}</a></li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- sidebar end -->
            </div>
        </div>
    </div>
    <!-- SHOP SECTION END -->
</section>

<!-- Flash Sale Styles -->
<style>
.flash-sale-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: linear-gradient(45deg, #ee4d2d, #ff6b35);
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-weight: bold;
    box-shadow: 0 2px 8px rgba(238, 77, 45, 0.3);
}

.badge-text {
    font-size: 12px;
    display: block;
}

.discount-percent {
    font-size: 16px;
    font-weight: 800;
}

.flash-sale-countdown {
    background: linear-gradient(135deg, #ff6b35, #ee4d2d);
    color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(238, 77, 45, 0.2);
}

.countdown-header h4 {
    color: white;
    margin: 0;
    font-weight: 700;
}

.countdown-timer {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 15px;
}

.countdown-item {
    background: rgba(255, 255, 255, 0.2);
    padding: 10px 15px;
    border-radius: 8px;
    text-align: center;
    min-width: 60px;
}

.countdown-item span {
    display: block;
    font-size: 24px;
    font-weight: 800;
    line-height: 1;
}

.countdown-item small {
    font-size: 12px;
    opacity: 0.9;
}

.flash-sale-price {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.flash-price {
    font-size: 28px;
    font-weight: 800;
}

.original-price {
    font-size: 18px;
    text-decoration: line-through;
}

.savings-info {
    background: #d4edda;
    color: #155724;
    padding: 8px 12px;
    border-radius: 6px;
    font-weight: 600;
    margin-top: 10px;
}

.flash-sale-stock {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.stock-info-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-weight: 600;
}

.stock-sold {
    color: #ee4d2d;
}

.stock-remaining {
    color: #6c757d;
}

.stock-progress-bar {
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.stock-progress {
    height: 100%;
    background: linear-gradient(90deg, #ee4d2d, #ff6b35);
    transition: width 0.3s ease;
}

.quantity-input-group {
    display: flex;
    align-items: center;
    gap: 15px;
}

.quantity-label {
    font-weight: 600;
    margin: 0;
}

.quantity-controls {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
}

.quantity-btn {
    background: #f8f9fa;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.2s;
}

.quantity-btn:hover {
    background: #e9ecef;
}

.quantity-input {
    border: none;
    text-align: center;
    width: 60px;
    padding: 8px;
    font-weight: 600;
}

.quantity-input:focus {
    outline: none;
}

@media (max-width: 768px) {
    .countdown-timer {
        gap: 10px;
    }
    
    .countdown-item {
        padding: 8px 10px;
        min-width: 50px;
    }
    
    .countdown-item span {
        font-size: 20px;
    }
    
    .flash-price {
        font-size: 24px;
    }
    
    .original-price {
        font-size: 16px;
    }
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown Timer
    const countdownTimer = document.querySelector('.countdown-timer');
    if (countdownTimer) {
        const endTime = new Date(countdownTimer.dataset.endTime).getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance < 0) {
                document.getElementById('countdown-hours').textContent = '00';
                document.getElementById('countdown-minutes').textContent = '00';
                document.getElementById('countdown-seconds').textContent = '00';
                return;
            }
            
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById('countdown-hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('countdown-minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('countdown-seconds').textContent = seconds.toString().padStart(2, '0');
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
});

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

function addToCart() {
    // Logic để thêm vào giỏ hàng
    alert('Đã thêm vào giỏ hàng!');
}

function addToWishlist() {
    // Logic để thêm vào yêu thích
    alert('Đã thêm vào yêu thích!');
}
</script>

@endsection
