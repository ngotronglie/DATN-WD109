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
                    @php
                        // Lọc flash sale đang diễn ra (active và trong khung giờ)
                        $ongoingFlashSales = $flashSales->filter(function($flashSale) {
                            return $flashSale->isActive();
                        })->first();

                        // Chỉ hiển thị flash sale sắp diễn ra khi KHÔNG có flash sale nào đang diễn ra
                        $upcomingFlashSales = collect();
                        if (!$ongoingFlashSales) {
                            $upcomingFlashSales = $flashSales->filter(function($flashSale) {
                                return $flashSale->start_time > now() && $flashSale->is_active;
                            });
                        }
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
                            $now = now();

                            $days = $startTime->diffInDays($now);
                            $hours = $startTime->diffInHours($now) % 24;
                            $minutes = $startTime->diffInMinutes($now) % 60;
                            $seconds = $startTime->diffInSeconds($now) % 60;

                            $timeString = '';
                            if ($days > 0) {
                                $timeString .= $days . ' ngày ';
                            }
                            if ($hours > 0 || $days > 0) {
                                $timeString .= $hours . ' giờ ';
                            }
                            if ($minutes > 0 || $hours > 0 || $days > 0) {
                                $timeString .= $minutes . ' phút ';
                            }
                            $timeString .= $seconds . ' giây';
                        @endphp
                        <div class="upcoming-badge ms-3">
                            <span class="badge bg-warning text-dark">Bắt đầu sau {{ $timeString }}</span>
                        </div>
                    @endif
                </div>
                <a href="{{ route('flash-sales') }}" class="view-all-link">Xem tất cả ></a>
            </div>
        </div>

        {{-- Products Grid --}}
        <div class="row g-0">
            @php
                // Chỉ hiển thị flash sale đang diễn ra, nếu không có thì hiển thị sắp diễn ra
                $displayFlashSales = collect();
                if ($ongoingFlashSales) {
                    $displayFlashSales = collect([$ongoingFlashSales]);
                } elseif ($upcomingFlashSales->isNotEmpty()) {
                    $displayFlashSales = $upcomingFlashSales->take(1); // Chỉ lấy 1 flash sale sắp diễn ra gần nhất
                }
            @endphp

            @foreach($displayFlashSales as $flashSale)
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
                                        @if($flashSale->start_time > now())
                                            <div class="upcoming-price">
                                                <div class="original-price">
                                                    <span class="original-price-value">{{ number_format($flashProduct->original_price, 0, ',', '.') }}₫</span>
                                                    <div class="sale-price-placeholder">
                                                        <span class="price-char">?</span>
                                                        <span class="price-char">?</span>
                                                        <span class="price-char">?</span>
                                                        <span class="price-char">?</span>
                                                        <span class="price-char">đ</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="product-price">
                                                <div class="sale-price">{{ number_format($flashProduct->sale_price, 0, ',', '.') }}₫</div>
                                                <div class="original-price">
                                                    <span>{{ number_format($flashProduct->original_price, 0, ',', '.') }}₫</span>
                                                    <!-- <span class="discount-badge">-{{ number_format((($flashProduct->original_price - $flashProduct->sale_price) / $flashProduct->original_price) * 100, 0) }}%</span> -->
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Product Actions --}}
                                        <div class="product-actions">
                                            <button class="action-btn add-to-favorite" data-product-id="{{ $flashProduct->productVariant->product->id }}" title="Thêm vào yêu thích" onclick="addToFavorite(event, {{ $flashProduct->productVariant->product->id }}); return false;">
                                                <i class="zmdi zmdi-favorite"></i>
                                            </button>
                                            <button class="action-btn add-to-cart" data-product-id="{{ $flashProduct->productVariant->product->id }}" data-variant-id="{{ $flashProduct->productVariant->id }}" data-flash-sale-id="{{ $flashSale->id }}" data-flash-price="{{ $flashProduct->sale_price }}" title="Thêm vào giỏ hàng" onclick="addToCart(event, {{ $flashProduct->productVariant->product->id }}, {{ $flashProduct->productVariant->id }}, {{ $flashSale->id }}, {{ $flashProduct->sale_price }}); return false;">
                                                <i class="zmdi zmdi-shopping-cart"></i>
                                            </button>
                                        </div>
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

                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        let timeString = 'Bắt đầu sau ';
                        if (days > 0) {
                            timeString += days + ' ngày ';
                        }
                        if (hours > 0 || days > 0) {
                            timeString += hours + ' giờ ';
                        }
                        if (minutes > 0 || hours > 0 || days > 0) {
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
    width: 28px;
    height: 28px;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    color: #666;
    text-align: center;
    transition: all 0.2s ease;
    text-decoration: none;
    font-size: 12px;
    cursor: pointer;
}

.action-btn:hover {
    background: #f8f8f8;
    color: #333;
    transform: none;
    box-shadow: none;
}

.action-btn.add-to-favorite {
    color: #999;
}

.action-btn.add-to-favorite[data-favorited="true"],
.action-btn.add-to-favorite:hover {
    color: #ff4757;
    background: #fff;
}

.action-btn.add-to-cart {
    color: #666;
    background: #fff;
}

.action-btn.add-to-cart:hover {
    color: #2ecc71;
    background: #f8f8f8;
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
}

.modal-overlay.show .modal-content {
    transform: scale(1);
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

.modal-button {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.modal-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.modal-button:active {
    transform: translateY(0);
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 20px;
    background: none;
    border: none;
    font-size: 24px;
    color: #999;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: #f8f9fa;
    color: #333;
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
// Cache cho trạng thái yêu thích
const favoriteCache = new Map();

document.addEventListener('DOMContentLoaded', function() {
    // Countdown Timer - Compact Version
    const countdownCompact = document.querySelector('.countdown-compact');
    if (countdownCompact) {
        const endTime = new Date(countdownCompact.dataset.endTime).getTime();

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

    // Kiểm tra trạng thái yêu thích khi trang load
    @auth
    checkFavoriteStatus();
    @endauth
});

// Kiểm tra trạng thái yêu thích của tất cả sản phẩm
@auth
function checkFavoriteStatus() {
    const favoriteButtons = document.querySelectorAll('.flash-sale-section .add-to-favorite');

    favoriteButtons.forEach(button => {
        const productId = button.getAttribute('data-product-id');
        const icon = button.querySelector('i');

        // Kiểm tra cache trước
        if (favoriteCache.has(productId)) {
            const isFavorite = favoriteCache.get(productId);
            if (isFavorite) {
                icon.style.color = '#e74c3c';
                button.setAttribute('data-favorited', 'true');
            }
            return;
        }

        // Gọi API kiểm tra trạng thái yêu thích
        fetch(`/favorites/check/${productId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Lưu vào cache
                favoriteCache.set(productId, data.is_favorite);

                if (data.is_favorite) {
                    // Nếu đã yêu thích thì đổi màu icon thành đỏ
                    icon.style.color = '#e74c3c';
                    button.setAttribute('data-favorited', 'true');
                }
            })
            .catch(error => {
                console.log('Lỗi khi kiểm tra trạng thái yêu thích:', error);
            });
    });
}
@endauth

// Hàm thêm vào yêu thích
function addToFavorite(event, productId) {
    event.preventDefault();
    event.stopPropagation();

    // Lấy button và icon chính xác
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

                    // Cập nhật cache
                    favoriteCache.set(productId, true);

                    // Hiển thị thông báo thành công
                    showModal('Đã thêm sản phẩm vào danh sách yêu thích!', 'success');
                } else {
                    // Kiểm tra nếu sản phẩm đã có trong yêu thích thì hiển thị thông báo thông tin
                    if (data.message && data.message.includes('đã có trong danh sách yêu thích')) {
                        icon.style.color = '#e74c3c';
                        button.setAttribute('data-favorited', 'true');

                        // Cập nhật cache
                        favoriteCache.set(productId, true);

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
        // Nếu chưa đăng nhập, chỉ hiển thị thông báo
        showModal('Vui lòng đăng nhập để thêm sản phẩm vào yêu thích!', 'warning');
    @endauth
}

// Hàm thêm vào giỏ hàng
function addToCart(event, productId, variantId, flashSaleId, flashPrice) {
    event.preventDefault();
    event.stopPropagation();

    @auth
        showModal('Đang thêm sản phẩm vào giỏ hàng...', 'info');

        // Tạo data cho flash sale product
        const cartData = {
            product_id: productId,
            product_variant_id: variantId,
            quantity: 1,
            is_flash_sale: true,
            flash_sale_id: flashSaleId,
            flash_sale_price: flashPrice,
            _token: '{{ csrf_token() }}'
        };

        // Gửi request thêm vào giỏ hàng
        fetch('/api/add-to-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(cartData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showModal('Đã thêm sản phẩm flash sale vào giỏ hàng!', 'success');
                } else {
                    showModal(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showModal('Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
            });
    @else
        // Nếu chưa đăng nhập, chỉ hiển thị thông báo
        showModal('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!', 'warning');
    @endauth
}

// Hiển thị modal thông báo ở giữa màn hình
function showModal(message, type = 'info') {
    const icons = {
        success: 'zmdi-check-circle success-icon',
        error: 'zmdi-close-circle error-icon',
        warning: 'zmdi-alert-triangle warning-icon',
        info: 'zmdi-info info-icon'
    };

    const titles = {
        success: 'Thành công!',
        error: 'Có lỗi!',
        warning: 'Cảnh báo!',
        info: 'Thông báo'
    };

    const modalHtml = `
        <div class="modal-overlay" id="flashSaleNotificationModal">
            <div class="modal-content">
                <button class="modal-close" onclick="closeFlashSaleModal()">
                    <i class="zmdi zmdi-close"></i>
                </button>
                <i class="zmdi ${icons[type]} modal-icon"></i>
                <h3 class="modal-title">${titles[type]}</h3>
                <p class="modal-message">${message}</p>
            </div>
        </div>
    `;

    // Xóa modal cũ nếu có
    const oldModal = document.getElementById('flashSaleNotificationModal');
    if (oldModal) {
        oldModal.remove();
    }

    // Thêm modal mới
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Hiển thị animation
    setTimeout(() => {
        const modal = document.getElementById('flashSaleNotificationModal');
        if (modal) {
            modal.classList.add('show');
        }
    }, 100);

    // Tự động đóng sau 3 giây
    setTimeout(function() {
        closeFlashSaleModal();
    }, 3000);
}

// Đ��ng modal
function closeFlashSaleModal() {
    const modal = document.getElementById('flashSaleNotificationModal');
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.remove(), 300);
    }
}
</script>
@endif
