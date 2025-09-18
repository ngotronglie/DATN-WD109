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
                                                        
                                                        <button class="remove-favorite-btn" data-favorite-id="{{ $favorite->id }}" title="Xóa khỏi yêu thích">
                                                            <i class="zmdi zmdi-close"></i>
                                                        </button>
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
                                                        <div class="product-actions">
                                                            @if($favorite->product->variants && $favorite->product->variants->first())
                                                                @php $variant = $favorite->product->variants->first(); @endphp
                                                                <a href="{{ url('cart/add/' . $variant->id) }}" class="action-btn add-to-cart" title="Thêm vào giỏ hàng">
                                                                    <i class="zmdi zmdi-shopping-cart"></i>
                                                                </a>
                                                            @endif
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
@endsection

@section('script-client')
<style>
/* Product Actions */
.product-actions {
    display: flex;
    justify-content: center;
    padding: 8px 0;
    border-top: 1px solid #f0f0f0;
    margin-top: 10px;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    color: #666;
    background-color: #f5f5f5;
    margin: 0 4px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    text-decoration: none;
}

.action-btn:hover {
    background-color: #ee4d2d;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.action-btn i {
    font-size: 18px;
}

.add-to-cart:hover {
    background-color: #ee4d2d;
}

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

.remove-favorite-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0,0,0,0.7);
    color: white;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 10;
}

.favorite-card:hover .remove-favorite-btn {
    opacity: 1;
}

.remove-favorite-btn:hover {
    background: #e74c3c;
    transform: scale(1.1);
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
</style>

<script>
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
    $('.remove-favorite-btn').on('click', function(e) {
        e.preventDefault();
        
        const favoriteId = $(this).data('favorite-id');
        const productItem = $(this).closest('.favorite-card');
        const productName = $(this).closest('.favorite-card').find('.product-name a').text();
        
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
});
</script>
@endsection
