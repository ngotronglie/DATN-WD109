{{-- Flash Sale Component --}}
@props(['flashSales' => collect(), 'limit' => 8])

@if($flashSales->isNotEmpty())
<section class="flash-sale-section py-5">
    <div class="container">
        {{-- Header Section --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="flash-sale-header d-flex justify-content-between align-items-center">
                    <div class="flash-sale-title">
                        <h2 class="section-title mb-2">
                            <i class="fas fa-bolt text-warning me-2"></i>
                            Flash Sale
                            <span class="badge bg-danger ms-2 pulse">HOT</span>
                        </h2>
                        <p class="text-muted mb-0">Giá sốc chỉ có hôm nay - Số lượng có hạn!</p>
                    </div>
                    
                    {{-- Countdown Timer --}}
                    @if($flashSales->first())
                        <div class="countdown-timer">
                            <div class="timer-label text-center mb-2">
                                <small class="text-muted">Kết thúc trong</small>
                            </div>
                            <div class="timer-display d-flex" data-end-time="{{ $flashSales->first()->end_time->toISOString() }}">
                                <div class="timer-unit">
                                    <span class="timer-number" id="hours">00</span>
                                    <small class="timer-text">Giờ</small>
                                </div>
                                <div class="timer-separator">:</div>
                                <div class="timer-unit">
                                    <span class="timer-number" id="minutes">00</span>
                                    <small class="timer-text">Phút</small>
                                </div>
                                <div class="timer-separator">:</div>
                                <div class="timer-unit">
                                    <span class="timer-number" id="seconds">00</span>
                                    <small class="timer-text">Giây</small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Products Grid --}}
        <div class="row">
            @foreach($flashSales as $flashSale)
                @foreach($flashSale->flashSaleProductsByPriority->take($limit) as $flashProduct)
                    @if($flashProduct->hasStock() && $flashProduct->productVariant)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="flash-sale-card h-100">
                                {{-- Product Image --}}
                                <div class="product-image-wrapper position-relative">
                                    <img src="{{ $flashProduct->productVariant->image ? asset('storage/' . $flashProduct->productVariant->image) : asset('images/no-image.png') }}" 
                                         alt="{{ $flashProduct->productVariant->product->name }}" 
                                         class="product-image">
                                    
                                    {{-- Discount Badge --}}
                                    <div class="discount-badge">
                                        -{{ $flashProduct->getDiscountPercentage() }}%
                                    </div>
                                    
                                    {{-- Stock Progress --}}
                                    <div class="stock-progress">
                                        @php
                                            $soldPercentage = (($flashProduct->initial_stock - $flashProduct->remaining_stock) / $flashProduct->initial_stock) * 100;
                                        @endphp
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" style="width: {{ $soldPercentage }}%"></div>
                                        </div>
                                        <small class="stock-text">
                                            Đã bán {{ $flashProduct->initial_stock - $flashProduct->remaining_stock }}/{{ $flashProduct->initial_stock }}
                                        </small>
                                    </div>
                                </div>

                                {{-- Product Info --}}
                                <div class="product-info p-3">
                                    <h5 class="product-name mb-2">
                                        <a href="{{ route('product.detail', $flashProduct->productVariant->product->id) }}" 
                                           class="text-decoration-none text-dark">
                                            {{ $flashProduct->productVariant->product->name }}
                                        </a>
                                    </h5>
                                    
                                    {{-- Product Variant Info --}}
                                    <div class="variant-info mb-2">
                                        @if($flashProduct->productVariant->color)
                                            <span class="badge bg-light text-dark me-1">{{ $flashProduct->productVariant->color->name }}</span>
                                        @endif
                                        @if($flashProduct->productVariant->capacity)
                                            <span class="badge bg-light text-dark">{{ $flashProduct->productVariant->capacity->name }}</span>
                                        @endif
                                    </div>

                                    {{-- Price Section --}}
                                    <div class="price-section mb-3">
                                        <div class="flash-price">
                                            <span class="current-price">{{ number_format($flashProduct->sale_price, 0, ',', '.') }}₫</span>
                                            <span class="original-price">{{ number_format($flashProduct->original_price, 0, ',', '.') }}₫</span>
                                        </div>
                                        <div class="saving-amount">
                                            Tiết kiệm: <span class="text-success fw-bold">{{ number_format($flashProduct->getSavingAmount(), 0, ',', '.') }}₫</span>
                                        </div>
                                    </div>

                                    {{-- Stock Info --}}
                                    <div class="stock-info mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Còn lại:</span>
                                            <span class="stock-count {{ $flashProduct->remaining_stock <= 5 ? 'text-danger' : 'text-success' }}">
                                                {{ $flashProduct->remaining_stock }} sản phẩm
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="action-buttons">
                                        @if($flashProduct->remaining_stock > 0)
                                            <button class="btn btn-danger btn-flash-buy w-100 mb-2" 
                                                    data-variant-id="{{ $flashProduct->productVariant->id }}"
                                                    data-flash-price="{{ $flashProduct->sale_price }}">
                                                <i class="fas fa-shopping-cart me-2"></i>
                                                Mua ngay
                                            </button>
                                            <button class="btn btn-outline-secondary btn-add-cart w-100" 
                                                    data-variant-id="{{ $flashProduct->productVariant->id }}"
                                                    data-flash-price="{{ $flashProduct->sale_price }}">
                                                <i class="fas fa-cart-plus me-2"></i>
                                                Thêm vào giỏ
                                            </button>
                                        @else
                                            <button class="btn btn-secondary w-100" disabled>
                                                <i class="fas fa-times me-2"></i>
                                                Hết hàng
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>

        {{-- View All Button --}}
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('flash-sales') }}" class="btn btn-outline-danger btn-lg">
                    <i class="fas fa-eye me-2"></i>
                    Xem tất cả Flash Sale
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Styles --}}
<style>
.flash-sale-section {
    background: linear-gradient(135deg, #fff5f5 0%, #ffe6e6 100%);
    position: relative;
    overflow: hidden;
}

.flash-sale-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,0,0,0.05) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #dc3545;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Countdown Timer */
.countdown-timer {
    background: linear-gradient(45deg, #dc3545, #fd7e14);
    color: white;
    padding: 15px 20px;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
    position: relative;
    z-index: 2;
}

.timer-display {
    gap: 10px;
    align-items: center;
}

.timer-unit {
    text-align: center;
    background: rgba(255,255,255,0.2);
    padding: 8px 12px;
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

.timer-number {
    font-size: 1.5rem;
    font-weight: 700;
    display: block;
    line-height: 1;
}

.timer-text {
    font-size: 0.7rem;
    opacity: 0.9;
}

.timer-separator {
    font-size: 1.5rem;
    font-weight: 700;
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.3; }
}

/* Product Cards */
.flash-sale-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.flash-sale-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.product-image-wrapper {
    overflow: hidden;
    height: 250px;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.flash-sale-card:hover .product-image {
    transform: scale(1.1);
}

.discount-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: linear-gradient(45deg, #dc3545, #fd7e14);
    color: white;
    padding: 8px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.9rem;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    z-index: 2;
}

.stock-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 8px 15px;
}

.stock-progress .progress {
    height: 4px;
    background: rgba(255,255,255,0.3);
    margin-bottom: 5px;
}

.stock-text {
    font-size: 0.75rem;
}

.product-name {
    font-size: 1.1rem;
    font-weight: 600;
    line-height: 1.3;
    height: 2.6rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.product-name a:hover {
    color: #dc3545 !important;
}

.variant-info .badge {
    font-size: 0.75rem;
    border: 1px solid #dee2e6;
}

/* Price Section */
.price-section {
    border-bottom: 1px solid #f8f9fa;
    padding-bottom: 15px;
}

.current-price {
    font-size: 1.4rem;
    font-weight: 700;
    color: #dc3545;
    margin-right: 10px;
}

.original-price {
    font-size: 1rem;
    color: #6c757d;
    text-decoration: line-through;
}

.saving-amount {
    font-size: 0.9rem;
    margin-top: 5px;
}

.stock-count {
    font-weight: 600;
}

/* Action Buttons */
.btn-flash-buy {
    background: linear-gradient(45deg, #dc3545, #fd7e14);
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-flash-buy:hover {
    background: linear-gradient(45deg, #c82333, #e8650e);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
}

.btn-add-cart {
    transition: all 0.3s ease;
}

.btn-add-cart:hover {
    background: #6c757d;
    border-color: #6c757d;
    color: white;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .flash-sale-header {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .countdown-timer {
        width: 100%;
        max-width: 300px;
    }
    
    .timer-number {
        font-size: 1.2rem;
    }
    
    .product-image-wrapper {
        height: 200px;
    }
}

@media (max-width: 576px) {
    .timer-display {
        gap: 5px;
    }
    
    .timer-unit {
        padding: 6px 8px;
    }
    
    .timer-number {
        font-size: 1rem;
    }
    
    .current-price {
        font-size: 1.2rem;
    }
}
</style>

{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown Timer
    const timerDisplay = document.querySelector('.timer-display');
    if (timerDisplay) {
        const endTime = new Date(timerDisplay.dataset.endTime).getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance < 0) {
                document.getElementById('hours').textContent = '00';
                document.getElementById('minutes').textContent = '00';
                document.getElementById('seconds').textContent = '00';
                return;
            }
            
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
    
    // Add to Cart functionality
    document.querySelectorAll('.btn-add-cart').forEach(button => {
        button.addEventListener('click', function() {
            const variantId = this.dataset.variantId;
            const flashPrice = this.dataset.flashPrice;
            
            // Add to cart logic here
            addToCart(variantId, 1, flashPrice);
        });
    });
    
    // Buy Now functionality
    document.querySelectorAll('.btn-flash-buy').forEach(button => {
        button.addEventListener('click', function() {
            const variantId = this.dataset.variantId;
            const flashPrice = this.dataset.flashPrice;
            
            // Buy now logic here
            buyNow(variantId, flashPrice);
        });
    });
});

// Add to cart function (customize based on your cart system)
function addToCart(variantId, quantity = 1, price = null) {
    // Implementation depends on your cart system
    console.log('Adding to cart:', { variantId, quantity, price });
    
    // Example AJAX call
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            variant_id: variantId,
            quantity: quantity,
            flash_price: price
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('Đã thêm vào giỏ hàng!', 'success');
            // Update cart count if needed
            updateCartCount();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra!', 'error');
    });
}

// Buy now function
function buyNow(variantId, price) {
    // Add to cart first, then redirect to checkout
    addToCart(variantId, 1, price);
    
    // Redirect to checkout after a short delay
    setTimeout(() => {
        window.location.href = '/checkout';
    }, 1000);
}

// Notification function
function showNotification(message, type = 'info') {
    // Implementation depends on your notification system
    // This is a simple example
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Update cart count function
function updateCartCount() {
    // Implementation depends on your cart system
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = data.count;
            }
        });
}
</script>
@endif