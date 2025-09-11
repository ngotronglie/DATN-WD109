@extends('index.clientdashboard')

@section('content')
        <!-- BREADCRUMBS SETCTION START -->
        <div class="breadcrumbs-section plr-200 mb-80 section">
            <div class="breadcrumbs" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 200px; display: flex; align-items: center;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="breadcrumbs-inner text-center">
                                <div class="mb-3">
                                    <i class="zmdi zmdi-favorite" style="font-size: 60px; color: #fff; text-shadow: 0 2px 4px rgba(0,0,0,0.3);"></i>
                                </div>
                                <h1 class="breadcrumbs-title" style="color: #fff; font-size: 48px; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.3); margin-bottom: 10px;">Sản phẩm yêu thích</h1>
                                <p style="color: #fff; font-size: 18px; opacity: 0.9; margin-bottom: 20px;">Khám phá những sản phẩm bạn đã yêu thích</p>
                                <ul class="breadcrumb-list" style="justify-content: center;">
                                    <li><a href="{{ route('home') }}" style="color: #fff; opacity: 0.8;">Trang chủ</a></li>
                                    <li style="color: #fff; opacity: 0.6;">/ Sản phẩm yêu thích</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- BREADCRUMBS SETCTION END -->

        <!-- Start page content -->
        <div id="page-content" class="page-wrapper section">

            <!-- FAVORITES SECTION START -->
            <div class="shop-section mb-80">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="shop-content">
                                <!-- shop-option start -->
                                <div class="shop-option box-shadow mb-30 clearfix">
                                    <!-- showing -->
                                    <div class="showing f-right text-end">
                                        <span>Hiển thị: <span id="favorites-count">0</span></span>
                                    </div>
                                </div>
                                <!-- shop-option end -->
                                
                                <!-- Favorites Content -->
                                <div id="favorites-container">
                                    @if(isset($favorites) && count($favorites) > 0)
                                        <div class="row">
                                            @foreach($favorites as $favorite)
                                                @if($favorite->product)
                                                <div class="col-lg-4 col-md-6 mb-4">
                                                    <div class="product-item">
                                                        <div class="product-img">
                                                            <a href="{{ route('product.detail', $favorite->product->slug) }}">
                                                                @if($favorite->product->variants && $favorite->product->variants->first() && $favorite->product->variants->first()->image)
                                                                    <img src="{{ asset($favorite->product->variants->first()->image) }}" alt="{{ $favorite->product->name }}" />
                                                                @else
                                                                    <img src="{{ asset('frontend/img/product/1.jpg') }}" alt="{{ $favorite->product->name }}" />
                                                                @endif
                                                            </a>
                                                        </div>
                                                        <div class="product-info">
                                                            <h6 class="product-title">
                                                                <a href="{{ route('product.detail', $favorite->product->slug) }}">{{ $favorite->product->name }}</a>
                                                            </h6>
                                                            <div class="pro-rating">
                                                                <a href="#"><i class="zmdi zmdi-star"></i></a>
                                                                <a href="#"><i class="zmdi zmdi-star"></i></a>
                                                                <a href="#"><i class="zmdi zmdi-star"></i></a>
                                                                <a href="#"><i class="zmdi zmdi-star-half"></i></a>
                                                                <a href="#"><i class="zmdi zmdi-star-outline"></i></a>
                                                            </div>
                                                            <h3 class="pro-price">
                                                                @if($favorite->product->variants && $favorite->product->variants->first())
                                                                    {{ number_format($favorite->product->variants->first()->price) }} VNĐ
                                                                @else
                                                                    Liên hệ
                                                                @endif
                                                            </h3>
                                                            <ul class="action-button">
                                                                <li>
                                                                    <a href="#" class="remove-favorite" data-favorite-id="{{ $favorite->id }}" title="Xóa khỏi danh sách yêu thích">
                                                                        <i class="zmdi zmdi-delete" style="color: #e74c3c;"></i>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="{{ route('product.detail', $favorite->product->slug) }}" title="Xem chi tiết">
                                                                        <i class="zmdi zmdi-zoom-in"></i>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" class="add-to-cart" data-product-id="{{ $favorite->product->id }}" title="Thêm vào giỏ hàng">
                                                                        <i class="zmdi zmdi-shopping-cart-plus"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
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
</style>

<script>
$(document).ready(function() {
    // Xóa sản phẩm khỏi favorites
    $('.remove-favorite').on('click', function(e) {
        e.preventDefault();
        
        const favoriteId = $(this).data('favorite-id');
        const productItem = $(this).closest('.col-lg-4');
        const productName = $(this).closest('.product-item').find('.product-title a').text();
        
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
                            if ($('.product-item').length === 0) {
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
    
    // Thêm vào giỏ hàng
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        
        const productId = $(this).data('product-id');
        
        $.ajax({
            url: '/api/add-to-cart',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: 1,
                _token: '{{ csrf_token() }}'
            },
            xhrFields: { withCredentials: true },
            success: function(response) {
                // Điều hướng sang giỏ hàng để người dùng thấy ngay
                window.location.href = '/cart';
            },
            error: function(xhr) {
                showNotification('Có lỗi xảy ra khi thêm vào giỏ hàng', 'error');
            }
        });
    });
    
    // Cập nhật số lượng favorites
    function updateFavoritesCount() {
        const count = $('.product-item').length;
        $('#favorites-count').text(count);
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
