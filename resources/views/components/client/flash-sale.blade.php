{{-- Shopee Style --}}
@props(['flashSales' => collect(), 'limit' => 6])

@if($flashSales->isNotEmpty())
<section class="flash-sale-section py-3 px-0">
    <div class="container-fluid px-0">
        {{-- Compact Header --}}
        <div class="flash-sale-header mb-3 px-3">
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
                        </div>
                    @php
                        $upcomingFlashSales = $flashSales->filter(function($flashSale) {
                            return $flashSale->start_time > now();
                        });

                        $ongoingFlashSales = $flashSales->filter(function($flashSale) {
                            return $flashSale->isActive();
                        })->first();
                    @endphp

                    @if($ongoingFlashSales)
                        <div class="countdown-compact ms-3" data-end-time="{{ $ongoingFlashSales->end_time->toISOString() }}">
                            <span class="countdown-text">Kết thúc sau</span>
                            <span class="timer-compact">
                                <span id="hours">00</span>:<span id="minutes">00</span>:<span id="seconds">00</span>
                            </span>
                        </div>
                    @elseif($upcomingFlashSales->isNotEmpty())
                        @php
                            $nextFlashSale = $upcomingFlashSales->sortBy('start_time')->first();
                            $startTime = $nextFlashSale->start_time;
                            $hours = $startTime->diffInHours(now());
                            $minutes = $startTime->diffInMinutes(now()) % 60;
                            $seconds = $startTime->diffInSeconds(now()) % 60;
                            $timeString = '';
                            if ($hours > 0) {
                                $timeString .= $hours . ' giờ ';
                            }
                            $timeString .= $minutes . ' phút ' . $seconds . ' giây';
                        @endphp
                        <div class="upcoming-badge ms-3">
                            <span class="badge bg-warning text-dark">Bắt đầu sau {{ $timeString }}</span>
                        </div>
                    @endif
                </div>
                @endif
                <a href="{{ route('flash-sales') }}" class="view-all-link">Xem tất cả ></a>
            </div>
        </div>

        {{-- Products Grid --}}
        <div class="row g-0">
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
                                        @if($flashSale->start_time > now())
                                            <div class="upcoming-overlay">Sắp diễn ra</div>
                                        @endif
                                    </div>

                                    {{-- Product Info --}}
                                    <div class="product-details">
                                        <div class="product-name" title="{{ $flashProduct->productVariant->product->name }}">{{ $flashProduct->productVariant->product->name }}</div>
                                        <div class="product-price">
                                            <span class="sale-price">₫{{ number_format($flashProduct->sale_price, 0, ',', '.') }}</span>
                                            <span class="original-price">₫{{ number_format($flashProduct->original_price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="status-pill status-{{ $flashSale->status_code }}">{{ $flashSale->status_label }}</div>
                                        
                                        @if($flashSale->start_time > now())
                                            <div class="upcoming-price">
                                                <div class="original-price">
                                                    <span class="original-price-value">₫{{ number_format($flashProduct->original_price, 0, ',', '.') }}</span>
                                                    <div class="sale-price-placeholder">
                                                        <span class="price-char">?</span>
                                                        <span class="price-char">?</span>
                                                        <span class="price-char">?</span>
                                                        <span class="price-char">?</span>
                                                        <span class="price-char">đ</span>
                                                    </div>
                                                    <span class="discount-badge">-{{ $flashProduct->getDiscountPercentage() }}%</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="product-price">
                                                <div class="sale-price">₫{{ number_format($flashProduct->sale_price, 0, ',', '.') }}</div>
                                                <div class="original-price">
                                                    <span>₫{{ number_format($flashProduct->original_price, 0, ',', '.') }}</span>
                                                    <!-- <span class="discount-badge">-{{ number_format((($flashProduct->original_price - $flashProduct->sale_price) / $flashProduct->original_price) * 100, 0) }}%</span> -->
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>

        @if($upcomingFlashSales->isNotEmpty())
            @php
                $nextFlashSale = $upcomingFlashSales->sortBy('start_time')->first();
            @endphp
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const startTime = new Date('{{ $nextFlashSale->start_time->toIso8601String() }}').getTime();
                    const countdownElement = document.querySelector('.upcoming-badge .badge');

                    function updateCountdown() {
                        const now = new Date().getTime();
                        const distance = startTime - now;

                        if (distance < 0) {
                            window.location.reload();
                            return;
                        }

                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        let timeString = 'Bắt đầu sau ';
                        if (hours > 0) {
                            timeString += hours + ' giờ ';
                        }
                        if (minutes > 0 || hours > 0) {
                            timeString += minutes + ' phút ';
                        }
                        timeString += seconds + ' giây';

                        countdownElement.textContent = timeString;
                    }

                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                });
            </script>
        @endif
    </div>
</section>

{{-- Shopee Style CSS --}}
<style>
.flash-sale-section {
    margin: 20px 0 10px;
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
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid #f0f0f0;
    position: relative;
    padding: 10px;
    margin: 2px;
}

.flash-product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    z-index: 1;
}

.flash-product-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.product-image-container {
    position: relative;
    width: 100%;
    padding-top: 100%; /* 1:1 Aspect Ratio */
    background: #f5f5f5;
    overflow: hidden;
    border-radius: 4px;
    margin-bottom: 8px;
}

.product-image-container img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 10px;
    transition: transform 0.3s ease;
}

.flash-product-card:hover .product-image-container img {
    transform: scale(1.05);
}

.upcoming-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(255, 193, 7, 0.9);
    color: #000;
    text-align: center;
    padding: 4px 0;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.upcoming-badge .badge {
    font-size: 14px;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
}

/* Làm mờ sản phẩm sắp diễn ra */
.flash-product-card .product-img {
    transition: opacity 0.3s;
}

.flash-sale-section .upcoming .product-img {
    opacity: 0.7;
}

/* Hiệu ứng khi di chuột vào sản phẩm sắp diễn ra */
.flash-product-card:hover .product-img {
    opacity: 1;
}

.product-image-container {
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
    top: 8px;
    right: 8px;
    background: #ff6b00;
    color: white;
    font-size: 12px;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 4px;
    z-index: 2;
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

/* Kiểu giá thông thường */
.product-price {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 8px;
}

.sale-price {
    color: #ee4d2d;
    font-size: 16px;
    font-weight: 600;
    line-height: 1.4;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
}

.discount-badge {
    background: #ee4d2d;
    color: white;
    font-size: 11px;
    padding: 0 4px;
    border-radius: 2px;
    font-weight: 500;
    line-height: 1.4;
}

.original-price {
    color: #9e9e9e;
    font-size: 13px;
    text-decoration: line-through;
    margin-top: 2px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Kiểu giá khi chưa đến giờ flash sale */
.upcoming-price {
    text-align: center;
    padding: 8px 0 4px;
}

.upcoming-price .original-price {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    margin-bottom: 6px;
}

.original-price-value {
    color: #666;
    font-size: 13px;
    text-decoration: line-through;
}

.sale-price-placeholder {
    display: flex;
    justify-content: center;
    margin-bottom: 8px;
    height: 24px;
    align-items: center;
}

.price-char {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 16px;
    height: 20px;
    background: #f5f5f5;
    border-radius: 2px;
    margin: 0 1px;
    font-size: 13px;
    font-weight: 600;
    color: #999;
    position: relative;
    overflow: hidden;
}

.price-char:last-child {
    background: none;
    color: #ee4d2d;
    font-weight: 400;
    margin-left: 2px;
}

.upcoming-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #fff0e6;
    border: 1px solid #ffd2b8;
    color: #ff6b00;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.upcoming-badge i {
    color: #ff6b00;
    font-size: 12px;
}


/* Hiệu ứng khi hover vào sản phẩm */
.flash-product-card:hover .sale-price {
    color: #ff1a2b;
    text-shadow: 0 0 6px rgba(255, 26, 43, 0.3);
}

.flash-product-card:hover .sale-price::before {
    background: rgba(255, 66, 79, 0.15);
    transform: translateY(-50%) skewX(-15deg) scaleX(1.05);
}

.flash-product-card:hover .original-price {
    opacity: 1;
    background: #ebebeb;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
}

/* Thêm hiệu ứng nhấp nháy nhẹ cho giá sale */
@keyframes pricePulse {
    0% { opacity: 0.9; }
    50% { opacity: 1; }
    100% { opacity: 0.9; }
}

.sale-price {
    animation: pricePulse 2s infinite ease-in-out;
}

.flash-product-card:hover .sale-price {
    animation: none;
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
