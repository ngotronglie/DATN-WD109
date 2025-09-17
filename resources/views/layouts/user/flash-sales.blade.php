@extends('index.clientdashboard')

@section('content')
<div class="flash-sales-page">
    {{-- Page Header --}}
    <div class="page-header bg-gradient-danger py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="page-title text-white mb-2">
                        <i class="fas fa-bolt me-3"></i>
                        Flash Sale
                        <span class="badge bg-warning text-dark ms-3 pulse">HOT DEALS</span>
                    </h1>
                    <p class="page-subtitle text-white-50 mb-0">Khuyến mãi sốc - Giá không thể tin được!</p>
                </div>
                <div class="col-lg-4 text-end">
                    @if($flashSales->isNotEmpty())
                        <div class="countdown-widget">
                            <div class="countdown-label text-white-50 mb-2">Kết thúc trong</div>
                            <div class="countdown-display" data-end-time="{{ $flashSales->first()->end_time->toISOString() }}">
                                <div class="time-unit">
                                    <span class="time-number" id="page-hours">00</span>
                                    <small class="time-label">Giờ</small>
                                </div>
                                <div class="time-separator">:</div>
                                <div class="time-unit">
                                    <span class="time-number" id="page-minutes">00</span>
                                    <small class="time-label">Phút</small>
                                </div>
                                <div class="time-separator">:</div>
                                <div class="time-unit">
                                    <span class="time-number" id="page-seconds">00</span>
                                    <small class="time-label">Giây</small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Flash Sales Content --}}
    <div class="container py-5">
        @if($flashSales->isNotEmpty())
            @foreach($flashSales as $flashSale)
                <div class="flash-sale-section mb-5">
                    {{-- Flash Sale Header --}}
                    <div class="flash-sale-header mb-4">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h2 class="flash-sale-name">
                                    <i class="fas fa-fire text-danger me-2"></i>
                                    {{ $flashSale->name }}
                                </h2>
                                <p class="flash-sale-time text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $flashSale->start_time->format('d/m/Y H:i') }} - {{ $flashSale->end_time->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="col-lg-4 text-end">
                                <div class="flash-sale-stats">
                                    <span class="stat-item">
                                        <i class="fas fa-box text-primary"></i>
                                        {{ $flashSale->flashSaleProducts->count() }} sản phẩm
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Products Grid --}}
                    <div class="row">
                        @php
                            // Nhóm theo product_id để tránh hiển thị trùng cùng 1 sản phẩm khác biến thể
                            $flashProductsByProduct = $flashSale->flashSaleProductsByPriority
                                ->filter(function($item){ return $item->productVariant && $item->hasStock(); })
                                ->groupBy(function ($item) {
                                    return optional(optional($item->productVariant)->product)->id;
                                })
                                ->map(function ($group) {
                                    // lấy item ưu tiên cao nhất trong nhóm
                                    return $group->first();
                                });
                        @endphp
                        @foreach($flashProductsByProduct as $flashProduct)
                            @if($flashProduct && $flashProduct->productVariant)
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                                    <div class="flash-product-card h-100">
                                        {{-- Product Image --}}
                                        <div class="product-image-container">
                                            <a href="{{ route('flash-sale.product.detail', $flashProduct->productVariant->product->slug) }}">
                                                <img src="{{ $flashProduct->productVariant->image ? (str_starts_with($flashProduct->productVariant->image, 'http') ? $flashProduct->productVariant->image : asset('storage/' . $flashProduct->productVariant->image)) : asset('images/no-image.png') }}" 
                                                     alt="{{ $flashProduct->productVariant->product->name }}" 
                                                     class="product-image"
                                                     onerror="this.src='{{ asset('images/no-image.png') }}'">
                                            </a>
                                            
                                            {{-- Discount Badge --}}
                                            <div class="discount-badge">
                                                -{{ $flashProduct->getDiscountPercentage() }}%
                                            </div>
                                            
                                            {{-- Priority Badge --}}
                                            @if($flashProduct->priority > 0)
                                                <div class="priority-badge">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Product Info --}}
                                        <div class="product-content p-3">
                                            <h5 class="product-title mb-2">
                                                <a href="{{ route('flash-sale.product.detail', $flashProduct->productVariant->product->slug) }}">
                                                    {{ $flashProduct->productVariant->product->name }}
                                                </a>
                                            </h5>
                                            
                                            {{-- Variant Info --}}
                                            <div class="variant-tags mb-2">
                                                @if($flashProduct->productVariant->color)
                                                    <span class="variant-tag">{{ $flashProduct->productVariant->color->name }}</span>
                                                @endif
                                                @if($flashProduct->productVariant->capacity)
                                                    <span class="variant-tag">{{ $flashProduct->productVariant->capacity->name }}</span>
                                                @endif
                                            </div>

                                            {{-- Price --}}
                                            <div class="price-info mb-3">
                                                <div class="price-row">
                                                    <span class="sale-price">{{ number_format($flashProduct->sale_price, 0, ',', '.') }}₫</span>
                                                    <span class="original-price">{{ number_format($flashProduct->original_price, 0, ',', '.') }}₫</span>
                                                </div>
                                                <div class="savings">
                                                    Tiết kiệm: <strong class="text-success">{{ number_format($flashProduct->getSavingAmount(), 0, ',', '.') }}₫</strong>
                                                </div>
                                            </div>


                                            {{-- Action Buttons --}}
                                            <div class="product-actions">
                                                @if($flashProduct->remaining_stock > 0)
                                                    <button class="btn btn-danger btn-buy-now w-100 mb-2" 
                                                            data-variant-id="{{ $flashProduct->productVariant->id }}"
                                                            data-flash-price="{{ $flashProduct->sale_price }}">
                                                        <i class="fas fa-shopping-cart me-2"></i>
                                                        Mua ngay
                                                    </button>
                                                    <button class="btn btn-outline-secondary btn-add-to-cart w-100" 
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
                    </div>
                </div>
            @endforeach
        @else
            {{-- No Flash Sales --}}
            <div class="no-flash-sales text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-bolt fa-5x text-muted mb-4"></i>
                    <h3 class="text-muted mb-3">Hiện tại không có Flash Sale nào</h3>
                    <p class="text-muted mb-4">Hãy quay lại sau để không bỏ lỡ những ưu đãi hấp dẫn!</p>
                    <a href="{{ route('products') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Xem sản phẩm khác
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Styles --}}
<style>
.flash-sales-page {
    min-height: 100vh;
}

.page-header {
    background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 30s linear infinite;
}

.page-title {
    font-size: 3rem;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.page-subtitle {
    font-size: 1.2rem;
}

.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Countdown Widget */
.countdown-widget {
    background: rgba(255,255,255,0.2);
    padding: 20px;
    border-radius: 15px;
    backdrop-filter: blur(10px);
}

.countdown-display {
    display: flex;
    gap: 10px;
    align-items: center;
    justify-content: center;
}

.time-unit {
    text-align: center;
    background: rgba(255,255,255,0.3);
    padding: 10px 15px;
    border-radius: 10px;
    min-width: 60px;
}

.time-number {
    display: block;
    font-size: 1.8rem;
    font-weight: 700;
    color: white;
    line-height: 1;
}

.time-label {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.8);
}

.time-separator {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.3; }
}

/* Flash Sale Section */
.flash-sale-section {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 30px;
    margin-bottom: 30px;
}

.flash-sale-name {
    font-size: 2rem;
    font-weight: 700;
    color: #dc3545;
    margin-bottom: 10px;
}

.flash-sale-time {
    font-size: 1rem;
}

.flash-sale-stats .stat-item {
    background: #f8f9fa;
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 600;
}

/* Product Cards */
.flash-product-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    overflow: hidden;
    border: 2px solid transparent;
}

.flash-product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    border-color: #dc3545;
}

.product-image-container {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.flash-product-card:hover .product-image {
    transform: scale(1.1);
}

.discount-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: linear-gradient(45deg, #dc3545, #fd7e14);
    color: white;
    padding: 8px 15px;
    border-radius: 25px;
    font-weight: 700;
    font-size: 0.9rem;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    z-index: 2;
}

.priority-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #ffc107;
    color: #212529;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
}

.product-title {
    font-size: 1.1rem;
    font-weight: 600;
    line-height: 1.3;
    height: 2.6rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.product-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-title a:hover {
    color: #dc3545;
}

.variant-tags {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.variant-tag {
    background: #e9ecef;
    color: #495057;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.price-info {
    border-bottom: 1px solid #f8f9fa;
    padding-bottom: 15px;
}

.price-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 5px;
}

.sale-price {
    font-size: 1.4rem;
    font-weight: 700;
    color: #dc3545;
}

.original-price {
    font-size: 1rem;
    color: #6c757d;
    text-decoration: line-through;
}

.savings {
    font-size: 0.9rem;
    color: #6c757d;
}

.stock-section {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
}

.stock-progress-bar .progress {
    height: 6px;
    background: #e9ecef;
    border-radius: 3px;
    margin-bottom: 8px;
}

.stock-info {
    display: flex;
    justify-content: space-between;
}

.btn-buy-now {
    background: linear-gradient(45deg, #dc3545, #fd7e14);
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-buy-now:hover {
    background: linear-gradient(45deg, #c82333, #e8650e);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
}

.btn-add-to-cart {
    transition: all 0.3s ease;
}

.btn-add-to-cart:hover {
    background: #6c757d;
    border-color: #6c757d;
    color: white;
    transform: translateY(-2px);
}

/* Empty State */
.empty-state {
    max-width: 500px;
    margin: 0 auto;
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .countdown-widget {
        margin-top: 20px;
    }
    
    .time-number {
        font-size: 1.4rem;
    }
    
    .flash-sale-section {
        padding: 20px;
    }
    
    .product-image-container {
        height: 200px;
    }
}

@media (max-width: 576px) {
    .countdown-display {
        gap: 5px;
    }
    
    .time-unit {
        padding: 8px 10px;
        min-width: 50px;
    }
    
    .time-number {
        font-size: 1.2rem;
    }
    
    .sale-price {
        font-size: 1.2rem;
    }
}
</style>

{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown Timer for page header
    const countdownDisplay = document.querySelector('.countdown-display');
    if (countdownDisplay) {
        const endTime = new Date(countdownDisplay.dataset.endTime).getTime();
        
        function updatePageCountdown() {
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance < 0) {
                document.getElementById('page-hours').textContent = '00';
                document.getElementById('page-minutes').textContent = '00';
                document.getElementById('page-seconds').textContent = '00';
                return;
            }
            
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById('page-hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('page-minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('page-seconds').textContent = seconds.toString().padStart(2, '0');
        }
        
        updatePageCountdown();
        setInterval(updatePageCountdown, 1000);
    }
    
    // Add to Cart functionality
    document.querySelectorAll('.btn-add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const variantId = this.dataset.variantId;
            const flashPrice = this.dataset.flashPrice;
            
            addToCart(variantId, 1, flashPrice);
        });
    });
    
    // Buy Now functionality
    document.querySelectorAll('.btn-buy-now').forEach(button => {
        button.addEventListener('click', function() {
            const variantId = this.dataset.variantId;
            const flashPrice = this.dataset.flashPrice;
            
            buyNow(variantId, flashPrice);
        });
    });
});

// Add to cart function
function addToCart(variantId, quantity = 1, price = null) {
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
            showNotification('Đã thêm vào giỏ hàng!', 'success');
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
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            variant_id: variantId,
            quantity: 1,
            flash_price: price,
            redirect_to: 'cart' // Add this parameter to indicate we want to redirect to cart
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to cart page immediately after successful addition
            window.location.href = '{{ route('cart') }}';
        } else {
            showNotification(data.message || 'Có lỗi xảy ra!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
    });
}

// Notification function
function showNotification(message, type = 'info') {
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
@endsection
