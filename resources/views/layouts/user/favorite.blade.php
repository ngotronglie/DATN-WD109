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
<script>
$(document).ready(function() {
    // Xóa sản phẩm khỏi favorites
    $('.remove-favorite').on('click', function(e) {
        e.preventDefault();
        
        const favoriteId = $(this).data('favorite-id');
        const productItem = $(this).closest('.col-lg-4');
        
        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi danh sách yêu thích?')) {
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
            success: function(response) {
                showNotification('Đã thêm sản phẩm vào giỏ hàng', 'success');
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
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Thêm thông báo vào đầu trang
        $('body').prepend(alertHtml);
        
        // Tự động ẩn sau 3 giây
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    }
    
    // Khởi tạo số lượng favorites
    updateFavoritesCount();
});
</script>
@endsection
