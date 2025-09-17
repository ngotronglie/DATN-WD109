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
                                    </ul>
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active show" id="description">
                                            <p>{{ $product->description }}</p>
                                        </div>
                                    </div>
                                </div>
                                <!--  hr -->
                                <hr>
                                <!-- Product Comments -->
                                <div class="mt-4">
                                    <h5 class="mb-3">Bình luận sản phẩm</h5>
                                    @if(session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    <div class="mb-4">
                                        @php $comments = $comments ?? collect(); @endphp
                                        @forelse($comments as $cmt)
                                            <div class="mb-3 p-2 border rounded">
                                                <strong>{{ $cmt->user->name ?? 'Khách' }}</strong>
                                                <div class="text-muted small">{{ $cmt->created_at->diffForHumans() }}</div>
                                                <div>{{ $cmt->content }}</div>
                                                @if($cmt->replies && $cmt->replies->count())
                                                    <div class="mt-2 ms-3">
                                                        @foreach($cmt->replies as $rep)
                                                            <div class="mb-2 p-2 border-start">
                                                                <strong>{{ $rep->user->name ?? 'Khách' }}</strong>
                                                                <div class="text-muted small">{{ $rep->created_at->diffForHumans() }}</div>
                                                                <div>{{ $rep->content }}</div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @empty
                                            <div class="text-muted">Chưa có bình luận nào.</div>
                                        @endforelse
                                    </div>
                                    @auth
                                        <form method="POST" action="{{ route('product.comments.store', $product->id) }}">
                                            @csrf
                                            <div class="mb-2">
                                                <textarea name="content" class="form-control" rows="3" placeholder="Nhập bình luận..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm">Gửi bình luận</button>
                                        </form>
                                    @else
                                        <div class="alert alert-info">Bạn cần <a href="{{ route('auth.login') }}">đăng nhập</a> để bình luận.</div>
                                    @endauth
                                </div>
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
});
</script>

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
