{{-- Shopee Style --}}
@props(['flashSales' => collect(), 'limit' => 6])

@if($flashSales->isNotEmpty())
<section class="flash-sale-section py-3">
    <div class="container">
        {{-- Compact Header --}}
        <div class="flash-sale-header mb-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="flash-icon me-2">⚡</div>
                    <h3 class="section-title mb-0">FLASH SALE</h3>
                    @if($flashSales->first())
                        @php $fs = $flashSales->first(); @endphp
                        <div class="countdown-compact ms-3" 
                             data-start-time="{{ optional($fs->start_time)->toISOString() }}"
                             data-end-time="{{ optional($fs->end_time)->toISOString() }}"
                             data-status="{{ $fs->status_code }}">
                            <span class="countdown-text status-label">{{ $fs->status_label }}</span>
                            <span class="timer-compact">
                                <span id="hours">00</span>:<span id="minutes">00</span>:<span id="seconds">00</span>
                            </span>
                        </div>
                    @endif
                </div>
                <a href="{{ route('flash-sales') }}" class="view-all-link">Xem tất cả ></a>
            </div>
        </div>

        {{-- Products Grid --}}
        <div class="row g-1">
            @foreach($flashSales as $flashSale)
                @php
                    // Group items by product to avoid duplicate cards for different variants
                    $itemsGroupedByProduct = $flashSale->flashSaleProductsByPriority
                        ->filter(function($item){
                            return $item->productVariant && $item->hasStock();
                        })
                        ->groupBy(function($item){
                            return optional(optional($item->productVariant)->product)->id;
                        })
                        ->map(function($group){
                            // Use the highest-priority item in each product group
                            return $group->first();
                        })
                        ->take($limit);
                @endphp
                @foreach($itemsGroupedByProduct as $flashProduct)
                    @if($flashProduct && $flashProduct->productVariant)
                        <div class="col-lg-1 col-md-2 col-sm-2 col-2 mb-1">
                            <div class="flash-product-card">
                                <a href="{{ route('flash-sale.product.detail', $flashProduct->productVariant->product->slug) }}" class="text-decoration-none">
                                    {{-- Product Image --}}
                                    <div class="product-image-container">
                                        <img src="{{ $flashProduct->productVariant->image ?: asset('images/no-image.png') }}" 
                                             alt="{{ $flashProduct->productVariant->product->name }}" 
                                             class="product-img"
                                             onerror="this.src='{{ asset('images/no-image.png') }}'">
                                        <div class="discount-tag">-{{ $flashProduct->getDiscountPercentage() }}%</div>
                                    </div>

                                    {{-- Product Info --}}
                                    <div class="product-details">
                                        <div class="product-name" title="{{ $flashProduct->productVariant->product->name }}">{{ $flashProduct->productVariant->product->name }}</div>
                                        <div class="product-price">
                                            <span class="sale-price">₫{{ number_format($flashProduct->sale_price, 0, ',', '.') }}</span>
                                            <span class="original-price">₫{{ number_format($flashProduct->original_price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="status-pill status-{{ $flashSale->status_code }}">{{ $flashSale->status_label }}</div>
                                        
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>

    </div>
</section>

{{-- Shopee Style CSS --}}
<style>
.flash-sale-section {
    background: #fff;
    border-top: 1px solid #f5f5f5;
    border-bottom: 1px solid #f5f5f5;
}

.flash-sale-header {
    padding: 15px 0;
    border-bottom: 1px solid #f5f5f5;
}

.flash-icon {
    font-size: 20px;
    color: #ee4d2d;
}

.section-title {
    font-size: 16px;
    font-weight: 700;
    color: #ee4d2d;
    text-transform: uppercase;
    margin: 0;
}

.countdown-compact {
    display: flex;
    align-items: center;
    gap: 8px;
}

.countdown-text {
    font-size: 12px;
    color: #757575;
}

.timer-compact {
    background: #ee4d2d;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.status-pill {
    display: inline-block;
    padding: 2px 6px;
    font-size: 10px;
    border-radius: 10px;
    font-weight: 600;
}
.status-upcoming { background: #fff3cd; color: #856404; }
.status-ongoing { background: #d4edda; color: #155724; }
.status-ended { background: #f8d7da; color: #721c24; }

.view-all-link {
    color: #ee4d2d;
    text-decoration: none;
    font-size: 14px;
}

.view-all-link:hover {
    color: #d73211;
}

/* Product Cards - Shopee Style */
.flash-product-card {
    background: white;
    border: 1px solid #f5f5f5;
    border-radius: 4px;
    overflow: hidden;
    transition: all 0.2s ease;
    height: 100%;
}

.flash-product-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.product-image-container {
    position: relative;
    width: 100%;
    /* wider, shorter ratio like Shopee */
    aspect-ratio: 1.2 / 1;
    overflow: hidden;
    border-radius: 6px;
    margin: 0 auto;
    width: 80%;
}

/* Force image to fill container and fit the tile */
.flash-sale-section .product-image-container .product-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.discount-tag {
    position: absolute;
    top: 0;
    right: 0;
    background: #ee4d2d;
    color: white;
    padding: 0 3px;
    font-size: 8px;
    font-weight: 600;
    border-bottom-left-radius: 4px;
}

.product-details {
    padding: 2px;
}

.product-name {
    font-size: 13px;
    line-height: 1.3;
    height: 28px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    margin-bottom: 6px;
    color: #333;
    text-align: center;
    font-weight: 600;
}

.product-price {
    margin-bottom: 2px;
    text-align: center;
}

.sale-price {
    color: #ee4d2d;
    font-size: 16px;
    font-weight: 800;
    display: block;
    text-align: center;
    margin-bottom: 2px;
    text-shadow: 0 1px 2px rgba(238, 77, 45, 0.2);
}

.original-price {
    color: #929292;
    font-size: 10px;
    text-decoration: line-through;
    text-align: center;
    display: block;
}


/* Responsive */
@media (max-width: 768px) {
    .flash-sale-header {
        padding: 10px 0;
    }
    
    .section-title {
        font-size: 14px;
    }
    
    .product-image-container { 
        aspect-ratio: 1.2 / 1; 
        width: 80%;
    }
    
    .countdown-compact {
        flex-direction: column;
        gap: 4px;
    }
}

@media (max-width: 576px) {
    .product-image-container { 
        aspect-ratio: 1.2 / 1; 
        width: 75%;
    }
    
    .sale-price { font-size: 14px; }
    
    .original-price { font-size: 9px; }
    
    .product-name { font-size: 11px; }
}
</style>

{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown Timer - Compact Version
    const countdownCompact = document.querySelector('.countdown-compact');
    if (countdownCompact) {
        const startTimeRaw = countdownCompact.dataset.startTime;
        const endTimeRaw = countdownCompact.dataset.endTime;
        const statusInitial = countdownCompact.dataset.status;
        const startTime = startTimeRaw ? new Date(startTimeRaw).getTime() : null;
        const endTime = endTimeRaw ? new Date(endTimeRaw).getTime() : null;
        const statusLabel = countdownCompact.querySelector('.status-label');
        
        function updateCountdown() {
            const now = new Date().getTime();
            if (startTime && now < startTime) {
                // Upcoming
                const distance = startTime - now;
                statusLabel.textContent = 'Chưa bắt đầu';
                if (distance <= 0) {
                    statusLabel.textContent = 'Đã bắt đầu';
                }
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
                return;
            }

            if (endTime && now <= endTime) {
                // Ongoing
                const distance = endTime - now;
                statusLabel.textContent = 'Đã bắt đầu';
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
                return;
            }

            // Ended
            if (statusLabel) statusLabel.textContent = 'Đã kết thúc';
                document.getElementById('hours').textContent = '00';
                document.getElementById('minutes').textContent = '00';
                document.getElementById('seconds').textContent = '00';
            return;
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
});
</script>
@endif