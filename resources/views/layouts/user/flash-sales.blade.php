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
                            <div class="countdown-label text-white-50 mb-2" id="countdown-label">Kết thúc trong</div>
                            <div class="countdown-display" 
                                 data-start-time="{{ $flashSales->first()->start_time->toISOString() }}"
                                 data-end-time="{{ $flashSales->first()->end_time->toISOString() }}">
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

                                            {{-- Price Info --}}
                                            <div class="price-info mb-3">
                                                @if($flashSale->start_time > now())
                                                    {{-- Upcoming Flash Sale --}}
                                                    <div class="upcoming-price">
                                                        <div class="original-price-value">₫{{ number_format($flashProduct->original_price, 0, ',', '.') }}</div>
                                                        <div class="sale-price-placeholder">
                                                            <span class="price-char">?</span>
                                                            <span class="price-char">?</span>
                                                            <span class="price-char">?</span>
                                                            <span class="price-char">?</span>
                                                            <span class="price-char">₫</span>
                                                        </div>
                                                        <div class="discount-badge">-{{ $flashProduct->getDiscountPercentage() }}%</div>
                                                    </div>
                                                @else
                                                    {{-- Active Flash Sale --}}
                                                    <div class="price-row">
                                                        <span class="sale-price">{{ number_format($flashProduct->sale_price, 0, ',', '.') }}₫</span>
                                                        <span class="original-price">{{ number_format($flashProduct->original_price, 0, ',', '.') }}₫</span>
                                                    </div>
                                                    <div class="savings">
                                                        Tiết kiệm: <strong class="text-success">{{ number_format($flashProduct->getSavingAmount(), 0, ',', '.') }}₫</strong>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Action Icons --}}
                                            <div class="product-actions-bottom">
                                                @if($flashProduct->remaining_stock > 0)
                                                    <button class="action-btn add-to-favorite" data-product-id="{{ $flashProduct->productVariant->product->id }}" title="Thêm vào yêu thích" onclick="addToFavorite(event, {{ $flashProduct->productVariant->product->id }}); return false;">
                                                        <i class="fas fa-heart"></i>
                                                    </button>
                                                    <button class="action-btn add-to-cart" title="Thêm vào giỏ hàng"
                                                            data-variant-id="{{ $flashProduct->productVariant->id }}"
                                                            data-flash-price="{{ $flashProduct->sale_price }}"
                                                            onclick="addFlashSaleToCart(event, {{ $flashProduct->productVariant->id }}, {{ $flashProduct->sale_price }})">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                @else
                                                    <div class="out-of-stock-label-bottom">
                                                        <i class="fas fa-times me-1"></i>
                                                        Hết hàng
                                                    </div>
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
    position: relative;
}

.flash-product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    border-color: #dc3545;
}

.flash-product-card:hover .product-actions {
    opacity: 1;
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

/* Upcoming Price Styles */
.upcoming-price {
    text-align: center;
    padding: 8px 0 4px;
}

.original-price-value {
    color: #666;
    font-size: 1rem;
    text-decoration: line-through;
    margin-bottom: 8px;
}

.sale-price-placeholder {
    display: flex;
    justify-content: center;
    margin-bottom: 8px;
    height: 32px;
    align-items: center;
}

.price-char {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 24px;
    background: #f5f5f5;
    border-radius: 2px;
    margin: 0 1px;
    font-size: 16px;
    font-weight: 600;
    color: #999;
    position: relative;
    overflow: hidden;
}

.price-char:last-child {
    background: none;
    color: #dc3545;
    font-weight: 400;
    margin-left: 2px;
}

.upcoming-price .discount-badge {
    background: #dc3545;
    color: white;
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 4px;
    font-weight: 500;
    display: inline-block;
}

/* Action Icons - Bottom */
.product-actions-bottom {
    display: flex;
    justify-content: center;
    gap: 15px;
    padding: 15px;
    border-top: 1px solid #f0f0f0;
    margin-top: 10px;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: #f8f9fa;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    font-size: 16px;
}

.action-btn:hover {
    background: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.action-btn.add-to-favorite {
    color: #999;
}

.action-btn.add-to-favorite[data-favorited="true"],
.action-btn.add-to-favorite:hover {
    color: #ff4757;
    background: #fff5f5;
}

.action-btn.add-to-cart {
    color: #666;
}

.action-btn.add-to-cart:hover {
    color: #2ecc71;
    background: #f0fff4;
}

.out-of-stock-label-bottom {
    background: #6c757d;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-align: center;
    width: 100%;
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
    position: relative;
}

.modal-overlay.show .modal-content {
    transform: scale(1);
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 15px;
    background: none;
    border: none;
    font-size: 18px;
    color: #999;
    cursor: pointer;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: #f5f5f5;
    color: #333;
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

.modal-actions {
    margin-top: 20px;
}

.modal-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    text-decoration: none;
    min-width: 150px;
}

.modal-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
    color: white;
    text-decoration: none;
}

.modal-button:active {
    transform: translateY(0);
}

.modal-button.login-btn {
    background: linear-gradient(135deg, #28a745, #20c997);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.modal-button.login-btn:hover {
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
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
        const startTime = new Date(countdownDisplay.dataset.startTime).getTime();
        const endTime = new Date(countdownDisplay.dataset.endTime).getTime();
        const countdownLabel = document.getElementById('countdown-label');

        function updatePageCountdown() {
            const now = new Date().getTime();
            let targetTime, labelText;

            // Determine what to count down to
            if (now < startTime) {
                // Flash sale hasn't started yet - count down to start time
                targetTime = startTime;
                labelText = 'Bắt đầu trong';
            } else if (now < endTime) {
                // Flash sale is active - count down to end time
                targetTime = endTime;
                labelText = 'Kết thúc trong';
            } else {
                // Flash sale has ended
                countdownLabel.textContent = 'Đã kết thúc';
                document.getElementById('page-hours').textContent = '00';
                document.getElementById('page-minutes').textContent = '00';
                document.getElementById('page-seconds').textContent = '00';
                return;
            }

            // Update label
            countdownLabel.textContent = labelText;

            // Calculate time remaining
            const distance = targetTime - now;
            
            if (distance < 0) {
                document.getElementById('page-hours').textContent = '00';
                document.getElementById('page-minutes').textContent = '00';
                document.getElementById('page-seconds').textContent = '00';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Include days in hours if there are days remaining
            const totalHours = days * 24 + hours;

            document.getElementById('page-hours').textContent = totalHours.toString().padStart(2, '0');
            document.getElementById('page-minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('page-seconds').textContent = seconds.toString().padStart(2, '0');
        }

        updatePageCountdown();
        setInterval(updatePageCountdown, 1000);
    }

    // Add to Cart functionality - Updated for new icon buttons
    // Note: The onclick handlers are already defined in HTML, so we don't need event listeners here

    // Kiểm tra các hành động pending sau khi đăng nhập
    checkPendingActions();
});

// Add to cart function
function addToCart(variantId, quantity = 1, price = null) {
    fetch('/api/add-to-cart', {
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
            try {
                if (typeof window.setCartBadge === 'function' && data.cart_count !== undefined) {
                    window.setCartBadge(data.cart_count);
                } else if (typeof window.refreshCartBadgeByApi === 'function') {
                    window.refreshCartBadgeByApi();
                }
            } catch (_) {}
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra!', 'error');
    });
}

// Buy now function
function buyNow(variantId, price) {
    fetch('/api/add-to-cart', {
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
            try {
                if (typeof window.setCartBadge === 'function' && data.cart_count !== undefined) {
                    window.setCartBadge(data.cart_count);
                } else if (typeof window.refreshCartBadgeByApi === 'function') {
                    window.refreshCartBadgeByApi();
                }
            } catch (_) {}
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

// Hiển thị modal thông báo ở giữa màn hình
function showModal(message, type = 'info', showLoginLink = false) {
    const icons = {
        success: 'fas fa-check-circle success-icon',
        error: 'fas fa-times-circle error-icon',
        warning: 'fas fa-exclamation-triangle warning-icon',
        info: 'fas fa-info-circle info-icon'
    };

    const titles = {
        success: 'Thành công!',
        error: 'Có lỗi!',
        warning: 'Yêu cầu đăng nhập!',
        info: 'Thông báo'
    };

    // Thêm link đăng nhập nếu cần
    const currentPath = window.location.pathname + window.location.search;
    const loginButton = showLoginLink ? `
        <div class="modal-actions">
            <a href="/login?redirect=${encodeURIComponent(currentPath)}" class="modal-button login-btn">
                <i class="fas fa-sign-in-alt me-2"></i>
                Đăng nhập ngay
            </a>
        </div>
    ` : '';

    const modalHtml = `
            <div class="modal-overlay" id="notificationModal">
                <div class="modal-content">
                    <button class="modal-close" onclick="closeModal()">
                        <i class="fas fa-times"></i>
                    </button>
                    <i class="${icons[type]} modal-icon"></i>
                    <h3 class="modal-title">${titles[type]}</h3>
                    <p class="modal-message">${message}</p>
                    ${loginButton}
                </div>
            </div>
        `;

    // Xóa modal cũ nếu có
    const oldModal = document.getElementById('notificationModal');
    if (oldModal) {
        oldModal.remove();
    }

    // Thêm modal mới
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Hiển thị animation
    setTimeout(() => {
        const modal = document.getElementById('notificationModal');
        if (modal) {
            modal.classList.add('show');
        }
    }, 100);

    // Tự động đóng sau 5 giây nếu có link đăng nhập, 3 giây nếu không
    const autoCloseTime = showLoginLink ? 5000 : 3000;
    setTimeout(function() {
        closeModal();
    }, autoCloseTime);
}

// Đóng modal
function closeModal() {
    const modal = document.getElementById('notificationModal');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.remove(), 300);
    }
}

// Add to Favorite function
function addToFavorite(event, productId) {
    event.preventDefault();
    event.stopPropagation();

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

            // Hiển thị thông báo thành công
            showModal('Đã thêm sản phẩm vào danh sách yêu thích!', 'success');
        } else {
            // Kiểm tra nếu sản phẩm đã có trong yêu thích thì hiển thị thông báo thông tin
            if (data.message && data.message.includes('đã có trong danh sách yêu thích')) {
                icon.style.color = '#e74c3c';
                button.setAttribute('data-favorited', 'true');
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
    // Nếu chưa đăng nhập, lưu thông tin sản phẩm và hiển thị thông báo
    localStorage.setItem('pendingFavorite', JSON.stringify({
        productId: productId,
        action: 'favorite',
        timestamp: Date.now()
    }));
    showModal('Bạn cần đăng nhập để thêm sản phẩm vào danh sách yêu thích của mình.', 'warning', true);
    @endauth
}

// Add Flash Sale to Cart function
function addFlashSaleToCart(event, variantId, flashPrice) {
    event.preventDefault();
    event.stopPropagation();

    // Kiểm tra đăng nhập trước khi thêm vào giỏ hàng
    @guest
    localStorage.setItem('pendingCart', JSON.stringify({
        variantId: variantId,
        flashPrice: flashPrice,
        action: 'cart',
        timestamp: Date.now()
    }));
    showModal('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.', 'warning', true);
    return;
    @endguest

    fetch('/api/add-to-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            variant_id: variantId,
            quantity: 1,
            flash_price: flashPrice
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showModal('Đã thêm vào giỏ hàng!', 'success');
            try {
                if (typeof window.setCartBadge === 'function' && data.cart_count !== undefined) {
                    window.setCartBadge(data.cart_count);
                } else if (typeof window.refreshCartBadgeByApi === 'function') {
                    window.refreshCartBadgeByApi();
                }
            } catch (_) {}
        } else {
            showModal(data.message || 'Có lỗi xảy ra!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showModal('Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
    });
}

// Kiểm tra và thực hiện các hành động pending sau khi đăng nhập
function checkPendingActions() {
    @auth
    // Kiểm tra pending favorite
    const pendingFavorite = localStorage.getItem('pendingFavorite');
    if (pendingFavorite) {
        try {
            const favoriteData = JSON.parse(pendingFavorite);
            // Kiểm tra timestamp để tránh thực hiện hành động cũ (trong vòng 5 phút)
            if (Date.now() - favoriteData.timestamp < 300000) {
                localStorage.removeItem('pendingFavorite');
                // Tự động thêm vào yêu thích
                addToFavoriteAfterLogin(favoriteData.productId);
            } else {
                localStorage.removeItem('pendingFavorite');
            }
        } catch (e) {
            localStorage.removeItem('pendingFavorite');
        }
    }

    // Kiểm tra pending cart
    const pendingCart = localStorage.getItem('pendingCart');
    if (pendingCart) {
        try {
            const cartData = JSON.parse(pendingCart);
            // Kiểm tra timestamp để tránh thực hiện hành động cũ (trong vòng 5 phút)
            if (Date.now() - cartData.timestamp < 300000) {
                localStorage.removeItem('pendingCart');
                // Tự động thêm vào giỏ hàng
                addToCartAfterLogin(cartData.variantId, cartData.flashPrice);
            } else {
                localStorage.removeItem('pendingCart');
            }
        } catch (e) {
            localStorage.removeItem('pendingCart');
        }
    }
    @endauth
}

// Thêm vào yêu thích sau khi đăng nhập
function addToFavoriteAfterLogin(productId) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('/favorites', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showModal('Đã tự động thêm sản phẩm vào danh sách yêu thích sau khi đăng nhập!', 'success');
            // Cập nhật UI nếu button vẫn còn trên trang
            const button = document.querySelector(`[data-product-id="${productId}"]`);
            if (button) {
                const icon = button.querySelector('i');
                if (icon) icon.style.color = '#e74c3c';
                button.setAttribute('data-favorited', 'true');
            }
        } else {
            if (data.message && data.message.includes('đã có trong danh sách yêu thích')) {
                showModal('Sản phẩm đã có trong danh sách yêu thích của bạn!', 'info');
            } else {
                showModal(data.message || 'Có lỗi khi thêm vào yêu thích!', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showModal('Có lỗi xảy ra khi thêm vào yêu thích!', 'error');
    });
}

// Thêm vào giỏ hàng sau khi đăng nhập
function addToCartAfterLogin(variantId, flashPrice) {
    fetch('/api/add-to-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            variant_id: variantId,
            quantity: 1,
            flash_price: flashPrice
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showModal('Đã tự động thêm sản phẩm vào giỏ hàng sau khi đăng nhập!', 'success');
            try {
                if (typeof window.setCartBadge === 'function' && data.cart_count !== undefined) {
                    window.setCartBadge(data.cart_count);
                } else if (typeof window.refreshCartBadgeByApi === 'function') {
                    window.refreshCartBadgeByApi();
                }
            } catch (_) {}
        } else {
            showModal(data.message || 'Có lỗi khi thêm vào giỏ hàng!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showModal('Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
    });
}

// Update cart count function
function updateCartCount() {
    // Cart count update functionality can be added here if needed
    // For now, we'll just show a success message
    console.log('Cart updated successfully');
}
</script>
@endsection
