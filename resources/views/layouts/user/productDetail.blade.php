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
                            <li>Sản phẩm chi tiết</li>
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
                                        <!-- hr -->
                                        <hr>
                                        <div class="single-pro-color-rating clearfix mb-2">
                                            <div class="sin-pro-color f-left">
                                                <span class="color-title f-left">Số lượng: </span>
                                                <span id="variant-quantity">{{ isset($variants[0]) ? $variants[0]->quantity : '' }}</span>
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- plus-minus-pro-action -->
                                        <div class="plus-minus-pro-action clearfix">
                                            <div class="sin-plus-minus f-left clearfix">
                                                <p class="color-title f-left">Qty</p>
                                                <div class=" f-left">
                                                    <button type="button" class="qty-btn qty-btn-minus">-</button>
                                                    <input type="text" value="1" name="qtybutton" class="cart-plus-minus-box" id="qty-input" style="width:40px;text-align:center;">
                                                    <button type="button" class="qty-btn qty-btn-plus">+</button>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- plus-minus-pro-action end -->
                                        <!-- hr -->
                                        <hr>
                                        <!-- single-product-price -->
                                        <h3 id="product-price" class="pro-price">Giá: {{ isset($variants[0]) ? number_format($variants[0]->price).'đ' : ''}}</h3>
                                        @if(isset($variants[0]) && $variants[0]->price_sale)
                                        <h3 id="product-price-sale" class="pro-price-sale text-danger text-decoration-line-through">
                                            {{ number_format($variants[0]->price_sale).'đ' }} 
                                        </h3>
                                        @else
                                        <h3 id="product-price-sale" class="pro-price-sale text-danger text-decoration-line-through" style="display:none"></h3>
                                        @endif
                                        <!--  hr -->
                                        <hr>
                                        <div class="product-action-btns text-center mt-3">
                                            <button type="button" class="button extra-small button-black me-2" tabindex="-1">
                                                <span class="text-uppercase">Mua ngay</span>
                                            </button>
                                            <button type="button" class="button extra-small button-outline" tabindex="-1">
                                                <span class="text-uppercase">Thêm vào giỏ hàng</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- single-product-info end -->
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-lg-12">
                                <!-- hr -->
                                <hr>
                                <div class="single-product-tab reviews-tab">
                                    <ul class="nav mb-20">
                                        <li><a class="active" href="#description" data-bs-toggle="tab">Mô tả sản phẩm</a></li>
                                        <li><a href="#reviews" data-bs-toggle="tab">Đánh giá & Bình luận</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active show" id="description">
                                            <p>{{ $product->description }}</p>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="reviews">
                                            <!-- Bình luận và đánh giá -->
                                            <div class="reviews-tab-desc">
                                                <div class="row">
                                                    <div class="col-lg-8">
                                                        <!-- Thống kê đánh giá -->
                                                        <div class="rating-summary mb-4">
                                                            <h5>Đánh giá trung bình</h5>
                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="rating-stars me-3">
                                                                    @php
                                                                        $avgRating = $comments->avg('rating') ?? 0;
                                                                        $totalComments = $comments->total();
                                                                    @endphp
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        <i class="zmdi zmdi-star {{ $i <= $avgRating ? 'text-warning' : 'text-muted' }}"></i>
                                                                    @endfor
                                                                    <span class="ms-2">{{ number_format($avgRating, 1) }}/5</span>
                                                                </div>
                                                                <span class="text-muted">({{ $totalComments }} bình luận)</span>
                                                            </div>
                                                        </div>

                                                        <!-- Danh sách bình luận -->
                                                        <div class="comments-section">
                                                            <h5>Bình luận ({{ $totalComments }})</h5>
                                                            @forelse($comments as $comment)
                                                                <div class="media mt-30 mb-4">
                                                                    <div class="media-left me-3">
                                                                        <img class="media-object rounded-circle" 
                                                                             src="{{ $comment->user->avatar ?? asset('frontend/img/author/1.jpg') }}" 
                                                                             alt="{{ $comment->user->name }}" 
                                                                             style="width: 50px; height: 50px; object-fit: cover;">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                                            <div>
                                                                                <h6 class="media-heading mb-1">{{ $comment->user->name ?? 'Khách' }}</h6>
                                                                                @if($comment->rating)
                                                                                    <div class="rating-stars mb-1">
                                                                                        @for($i = 1; $i <= 5; $i++)
                                                                                            <i class="zmdi zmdi-star {{ $i <= $comment->rating ? 'text-warning' : 'text-muted' }}" style="font-size: 14px;"></i>
                                                                                        @endfor
                                                                                    </div>
                                                                                @endif
                                                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                                            </div>
                                                                            @if(Auth::check() && (Auth::id() == $comment->user_id || Auth::user()->role->name === 'admin'))
                                                                                <form action="{{ route('product.comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa bình luận này?')">
                                                                                        <i class="zmdi zmdi-delete"></i>
                                                                                    </button>
                                                                                </form>
                                                                            @endif
                                                                        </div>
                                                                        <p class="mb-2">{{ $comment->content }}</p>
                                                                        
                                                                        <!-- Nút trả lời -->
                                                                        @if(Auth::check())
                                                                            <button class="btn btn-sm btn-outline-primary reply-btn" data-comment-id="{{ $comment->id }}">
                                                                                <i class="zmdi zmdi-reply"></i> Trả lời
                                                                            </button>
                                                                            
                                                                            <!-- Form trả lời (ẩn) -->
                                                                            <div class="reply-form mt-2" id="reply-form-{{ $comment->id }}" style="display: none;">
                                                                                <form action="{{ route('product.comments.reply', $comment->id) }}" method="POST">
                                                                                    @csrf
                                                                                    <div class="form-group">
                                                                                        <textarea name="content" class="form-control" rows="2" placeholder="Viết trả lời..."></textarea>
                                                                                    </div>
                                                                                    <div class="mt-2">
                                                                                        <button type="submit" class="btn btn-sm btn-primary">Gửi trả lời</button>
                                                                                        <button type="button" class="btn btn-sm btn-secondary cancel-reply" data-comment-id="{{ $comment->id }}">Hủy</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        @endif
                                                                        
                                                                        <!-- Hiển thị trả lời -->
                                                                        @foreach($comment->replies as $reply)
                                                                            <div class="reply ms-4 mt-3 p-3 bg-light rounded">
                                                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                                                    <div>
                                                                                        <h6 class="mb-1">{{ $reply->user->name ?? 'Khách' }}</h6>
                                                                                        <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                                                    </div>
                                                                                    @if(Auth::check() && (Auth::id() == $reply->user_id || Auth::user()->role->name === 'admin'))
                                                                                        <form action="{{ route('product.comments.destroy', $reply->id) }}" method="POST" class="d-inline">
                                                                                            @csrf
                                                                                            @method('DELETE')
                                                                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa trả lời này?')">
                                                                                                <i class="zmdi zmdi-delete"></i>
                                                                                            </button>
                                                                                        </form>
                                                                                    @endif
                                                                                </div>
                                                                                <p class="mb-0">{{ $reply->content }}</p>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @empty
                                                                <div class="text-center py-4">
                                                                    <i class="zmdi zmdi-comment-outline text-muted" style="font-size: 48px;"></i>
                                                                    <p class="text-muted mt-2">Chưa có bình luận nào cho sản phẩm này.</p>
                                                                </div>
                                                            @endforelse
                                                            
                                                            <!-- Phân trang -->
                                                            @if($comments->hasPages())
                                                                <div class="d-flex justify-content-center mt-4">
                                                                    {{ $comments->links() }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-lg-4">
                                                        <!-- Form bình luận -->
                                                        @if(Auth::check())
                                                            <div class="comment-form">
                                                                <h5>Viết bình luận</h5>
                                                                <form action="{{ route('product.comments.store', $product->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="form-group mb-3">
                                                                        <label for="rating">Đánh giá:</label>
                                                                        <div class="rating-input">
                                                                            @for($i = 5; $i >= 1; $i--)
                                                                                <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}">
                                                                                <label for="star{{ $i }}" class="zmdi zmdi-star"></label>
                                                                            @endfor
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group mb-3">
                                                                        <label for="content">Nội dung bình luận:</label>
                                                                        <textarea name="content" class="form-control" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..." required></textarea>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary">
                                                                        <i class="zmdi zmdi-send"></i> Gửi bình luận
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @else
                                                            <div class="alert alert-info">
                                                                <i class="zmdi zmdi-info"></i>
                                                                <a href="{{ route('auth.login') }}">Đăng nhập</a> để viết bình luận.
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  hr -->
                                <hr>
                            </div>
                        </div>
                    </div>
                    <!-- single-product-tab -->
                    <div class="col-12 mt-5">
                        <div class="row">
                            <!-- Sidebar Danh mục -->
                            <div class="col-lg-4 mb-4">
                                <aside class="widget widget-categories box-shadow mb-30">
                                    <h6 class="widget-title border-left mb-20">Danh mục</h6>
                                    <div id="cat-treeview" class="product-cat">
                                        <ul>
                                            @foreach($categories as $parent)
                                            <li class="{{ $parent->children->count() ? 'closed' : '' }}">
                                                <a href="#">{{ $parent->Name }}</a>
                                                @if($parent->children->count())
                                                <ul>
                                                    @foreach($parent->children as $child)
                                                    <li><a href="#">{{ $child->Name }}</a></li>
                                                    @endforeach
                                                </ul>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </aside>
                            </div>

                            <!-- Sản phẩm gần đây -->
                            <div class="col-lg-8">
                                <aside class="widget widget-product box-shadow mb-30">
                                    <h6 class="widget-title border-left mb-20">Sản phẩm gần đây</h6>
                                    <div class="row">
                                        @for($i = 0; $i < 4; $i++)
                                            <div class="col-md-3 col-sm-6 mb-3">
                                            <div class="product-item text-center">
                                                <div class="product-img mb-2">
                                                    <a href="#">
                                                        <img src="{{ asset('frontend/img/product/' . (4 + $i*4) . '.jpg') }}" alt="" class="img-fluid" />
                                                    </a>
                                                </div>
                                                <div class="product-info">
                                                    <h6 class="product-title mb-1">
                                                        <a href="#">Product Name</a>
                                                    </h6>
                                                    <h3 class="pro-price mb-0">869.000₫</h3>
                                                </div>
                                            </div>
                                    </div>
                                    @endfor
                            </div>
                            </aside>
                        </div>
                    </div>
                </div>

                <!-- SHOP SECTION END -->

</section>



@endsection

@section('script-client')
<link rel="stylesheet" href="{{ asset('css/product-comments.css') }}">
<style>
/* Rating Stars CSS */
.rating-stars {
    display: inline-block;
}

.rating-stars i {
    font-size: 18px;
    cursor: pointer;
    transition: color 0.2s;
}

.rating-input {
    display: flex;
    flex-direction: row-reverse;
    gap: 5px;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input label {
    font-size: 24px;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

.rating-input label:hover,
.rating-input label:hover ~ label,
.rating-input input[type="radio"]:checked ~ label {
    color: #ffc107;
}

/* Comment styles */
.comments-section {
    max-height: 600px;
    overflow-y: auto;
}

.reply {
    border-left: 3px solid #007bff;
}

.media-object {
    border: 2px solid #f8f9fa;
}

/* Success/Error messages */
.alert {
    border-radius: 8px;
    border: none;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateVariant() {
        var productId = {{ $product->id }};
        var colorId = document.querySelector('input[name="color"]:checked').value;
        var capacityId = document.querySelector('input[name="capacity"]:checked').value;

        fetch(`/api/product-variant?product_id=${productId}&color_id=${colorId}&capacity_id=${capacityId}`)
            .then(res => res.json())
            .then(data => {
                var priceSaleElem = document.getElementById('product-price-sale');
                var buyBtn = document.querySelector('.button-black');
                var quantityElem = document.getElementById('variant-quantity');

                if (data.success) {
                    document.getElementById('product-image').src = data.image;
                    document.getElementById('product-image').setAttribute('data-zoom-image', data.image);

                    document.getElementById('product-price').innerText =
                        'Giá: ' + Number(data.price).toLocaleString('vi-VN') + '₫';

                    if (data.price_sale) {
                        priceSaleElem.innerText = Number(data.price_sale).toLocaleString('vi-VN') + '₫';
                        priceSaleElem.style.display = '';
                    } else {
                        priceSaleElem.style.display = 'none';
                    }

                    if (quantityElem) {
                        quantityElem.innerText = data.quantity ?? '';
                    }

                    if (data.product_name) {
                        document.getElementById('product-name').innerText = data.product_name;
                    }

                    if (data.category_name) {
                        document.getElementById('category-name').innerText = data.category_name;
                    }

                    if (buyBtn) {
                        buyBtn.disabled = false;
                        buyBtn.innerText = 'Mua ngay';
                    }

                    document.getElementById('variant-error')?.remove();
                } else {
                    if (quantityElem) {
                        quantityElem.innerText = '';
                    }

                    if (!document.getElementById('variant-error')) {
                        var error = document.createElement('div');
                        error.id = 'variant-error';
                        error.className = 'alert alert-danger mt-2';
                        error.innerText = 'Biến thể này hiện không có hàng!';
                        document.querySelector('.single-product-info').appendChild(error);
                    }

                    if (buyBtn) {
                        buyBtn.disabled = true;
                        buyBtn.innerText = 'Không có hàng';
                    }
                }
            });
    }

    document.querySelectorAll('input[name="color"], input[name="capacity"]').forEach(function(input) {
        input.addEventListener('change', updateVariant);
    });

    // Tăng giảm số lượng
    var qtyInput = document.getElementById('qty-input');
    document.querySelector('.qty-btn-minus').addEventListener('click', function() {
        var val = parseInt(qtyInput.value) || 1;
        if (val > 1) qtyInput.value = val - 1;
    });

    document.querySelector('.qty-btn-plus').addEventListener('click', function() {
        var val = parseInt(qtyInput.value) || 1;
        qtyInput.value = val + 1;
    });

    // Thêm vào giỏ hàng
    document.querySelector('.button-outline').addEventListener('click', function() {
        var productId = {{ $product->id }};
        var productName = document.getElementById('product-name').innerText;
        var colorId = document.querySelector('input[name="color"]:checked').value;
        var colorName = document.querySelector('input[name="color"]:checked').parentElement.innerText.trim();
        var capacityId = document.querySelector('input[name="capacity"]:checked').value;
        var capacityName = document.querySelector('input[name="capacity"]:checked').parentElement.innerText.trim();
        var qty = parseInt(document.getElementById('qty-input').value) || 1;
        var price = document.getElementById('product-price').innerText;
        var priceSaleElem = document.getElementById('product-price-sale');
        var priceSale = priceSaleElem && priceSaleElem.style.display !== 'none' ? priceSaleElem.innerText : null;
        var image = document.getElementById('product-image').src;

        // Tìm variant_id phù hợp
        var variantId = null;
        @foreach($variants as $variant)
            if ({{ $variant->color_id ?? 'null' }} == colorId && {{ $variant->capacity_id ?? 'null' }} == capacityId) {
                variantId = {{ $variant->id }};
            }
        @endforeach

        var data = {
            product_id: productId,
            color_id: colorId,
            capacity_id: capacityId,
            variant_id: variantId,
            quantity: qty,
            price: price,
            price_sale: priceSale,
            image: image
        };

        fetch('/api/add-to-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                variant_id: data.variant_id,
                quantity: data.quantity
            })
        })
        .then(res => res.json())
        .then(resData => {
            if (resData.success) {
                window.location.href = '/cart';
            } else {
                alert(resData.message || 'Có lỗi xảy ra!');
            }
        });
    });

    // Reply functionality
    document.querySelectorAll('.reply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            
            // Hide all other reply forms
            document.querySelectorAll('.reply-form').forEach(form => {
                form.style.display = 'none';
            });
            
            // Show this reply form
            replyForm.style.display = 'block';
        });
    });

    // Cancel reply functionality
    document.querySelectorAll('.cancel-reply').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            replyForm.style.display = 'none';
        });
    });

    // Show success/error messages
    @if(session('success'))
        showAlert('{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
        showAlert('{{ session('error') }}', 'danger');
    @endif

    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert at the top of the page
        document.body.insertBefore(alertDiv, document.body.firstChild);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
});
</script>
<style>
        .thumb-wrapper {
        width: 70px;
        height: 70px;
        border: 2px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        padding: 2px;
        background-color: #fff;
        transition: border-color 0.3s;
        cursor: pointer;
    }

    .thumb-wrapper:hover {
        border-color: #007bff;
    }

    .thumb-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
    }

    .thumb-image:hover {
        border: 2px solid #007bff;
    }
.color-radio-label, .capacity-radio-label {
    display: inline-flex;
    align-items: center;
    margin-right: 15px;
    cursor: pointer;
    font-weight: 500;
}
.color-radio-label input[type="radio"],
.capacity-radio-label input[type="radio"] {
    display: none;
}
.color-radio-custom, .capacity-radio-custom {
    width: 18px;
    height: 18px;
    border: 2px solid #888;
    border-radius: 50%;
    margin-right: 6px;
    display: inline-block;
    position: relative;
    background: #fff;
    transition: border-color 0.2s;
}
.color-radio-label input[type="radio"]:checked + .color-radio-custom,
.capacity-radio-label input[type="radio"]:checked + .capacity-radio-custom {
    border-color: #007bff;
    background: #007bff;
}
.color-radio-label input[type="radio"]:checked + .color-radio-custom::after,
.capacity-radio-label input[type="radio"]:checked + .capacity-radio-custom::after {
    content: '';
    display: block;
    width: 8px;
    height: 8px;
    background: #fff;
    border-radius: 50%;
    position: absolute;
    top: 3px;
    left: 3px;
}
.product-action-btns {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 20px;
}
.button.extra-small {
    padding: 8px 22px;
    font-size: 1em;
    border-radius: 6px;
    border: none;
    background: #444;
    color: #fff;
    transition: background 0.2s, color 0.2s;
    cursor: pointer;
    font-weight: 600;
    letter-spacing: 1px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    outline: none;
}
.button.extra-small:disabled {
    background: #aaa;
    color: #eee;
    cursor: not-allowed;
}
.button-outline {
    background: #fff;
    color: #444;
    border: 2px solid #444;
}
.button-outline:hover {
    background: #444;
    color: #fff;
}
.qty-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: #eee;
    color: #333;
    font-size: 1.2em;
    font-weight: bold;
    border-radius: 50%;
    margin: 0 4px;
    cursor: pointer;
    transition: background 0.2s;
}
.qty-btn:hover {
    background: #ccc;
}
.cart-plus-minus-box {
    width: 40px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin: 0 2px;
}
</style>
@endsection
