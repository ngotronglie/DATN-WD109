@extends('index.clientdashboard')

@section('content')

        <!-- BREADCRUMBS SETCTION START -->
        <div class="breadcrumbs-section plr-200 mb-80 section">
            <div class="breadcrumbs overlay-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="breadcrumbs-inner">
                                <h1 class="breadcrumbs-title">Single Product Left Sidebar</h1>
                                <ul class="breadcrumb-list">
                                    <li><a href="index.html">Home</a></li>
                                    <li>Single Product Left Sidebar</li>
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
                                <div class="row">
                                    <!-- imgs-zoom-area start -->
                                    <div class="col-lg-5">
                                        <div class="imgs-zoom-area">
                                            <img id="product-image" src="{{ isset($variants[0]) ? asset($variants[0]->image) : '' }}" data-zoom-image="{{ isset($variants[0]) ? asset($variants[0]->image) : '' }}" alt="">
                                        </div>
                                    </div>
                                    <!-- imgs-zoom-area end -->
                                    <!-- single-product-info start -->
                                    <div class="col-lg-7">
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
                                            <h3 id="product-price" class="pro-price">Price: ${{ isset($variants[0]) ? number_format($variants[0]->price, 2) : '' }}</h3>
                                            @if(isset($variants[0]) && $variants[0]->price_sale)
                                                <h3 id="product-price-sale" class="pro-price-sale text-danger text-decoration-line-through">
                                                    ${{ number_format($variants[0]->price_sale, 2) }}
                                                </h3>
                                            @else
                                                <h3 id="product-price-sale" class="pro-price-sale text-danger text-decoration-line-through" style="display:none"></h3>
                                            @endif
                                            <!--  hr -->
                                            <hr>
                                            <div class="product-action-btns text-center mt-3">
                                                <button type="button" class="button extra-small button-black me-2" tabindex="-1">
                                                    <span class="text-uppercase">Buy now</span>
                                                </button>
                                                <button type="button" class="button extra-small button-outline" tabindex="-1">
                                                    <span class="text-uppercase">Add to cart</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single-product-info end -->
                                </div>
                                <!-- single-product-tab -->
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 order-lg-1 order-2">
                            <!-- widget-categories -->
                            <aside class="widget widget-categories box-shadow mb-30">
                                <h6 class="widget-title border-left mb-20">Categories</h6>
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

                            <!-- widget-product -->
                            <aside class="widget widget-product box-shadow">
                                <h6 class="widget-title border-left mb-20">recent products</h6>
                                <!-- product-item start -->
                                <div class="product-item">
                                    <div class="product-img">
                                        <a href="single-product.html">
                                            <img src="{{asset('frontend/img/product/4.jpg')}}" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <h6 class="product-title">
                                            <a href="single-product.html">Product Name</a>
                                        </h6>
                                        <h3 class="pro-price">$ 869.00</h3>
                                    </div>
                                </div>
                                <!-- product-item end -->
                                <!-- product-item start -->
                                <div class="product-item">
                                    <div class="product-img">
                                        <a href="single-product.html">
                                            <img src="{{asset('frontend/img/product/8.jpg')}}" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <h6 class="product-title">
                                            <a href="single-product.html">Product Name</a>
                                        </h6>
                                        <h3 class="pro-price">$ 869.00</h3>
                                    </div>
                                </div>
                                <!-- product-item end -->
                                <!-- product-item start -->
                                <div class="product-item">
                                    <div class="product-img">
                                        <a href="single-product.html">
                                            <img src="{{asset('frontend/img/product/12.jp')}}g" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <h6 class="product-title">
                                            <a href="single-product.html">Product Name</a>
                                        </h6>
                                        <h3 class="pro-price">$ 869.00</h3>
                                    </div>
                                </div>
                                <!-- product-item end -->
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
            <!-- SHOP SECTION END -->

        </section>
        <!-- End page content -->

        @if(isset($variants) && count($variants) > 0)
            <div class="mb-3">
                <label for="variant-select" class="form-label">Chọn biến thể:</label>
                <select id="variant-select" class="form-select">
                    @foreach($variants as $variant)
                        <option value="{{ $variant->id }}"
                            data-image="{{ asset($variant->image) }}"
                            data-price="{{ $variant->price }}"
                            data-price-sale="{{ $variant->price_sale }}">
                            {{ $variant->color->name ?? '' }} {{ $variant->capacity->name ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

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
                    document.getElementById('product-price').innerText = 'Price: $' + Number(data.price).toLocaleString();
                    if (data.price_sale) {
                        priceSaleElem.innerText = '$' + Number(data.price_sale).toLocaleString();
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
                    // Enable buy button
                    if (buyBtn) {
                        buyBtn.disabled = false;
                        buyBtn.innerText = 'Buy now';
                    }
                    // Ẩn thông báo lỗi nếu có
                    document.getElementById('variant-error')?.remove();
                } else {
                    if (quantityElem) {
                        quantityElem.innerText = '';
                    }
                    // Hiển thị thông báo lỗi
                    if (!document.getElementById('variant-error')) {
                        var error = document.createElement('div');
                        error.id = 'variant-error';
                        error.className = 'alert alert-danger mt-2';
                        error.innerText = 'Biến thể này hiện không có hàng!';
                        document.querySelector('.single-product-info').appendChild(error);
                    }
                    // Disable buy button
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

    // Số lượng tăng giảm
    var qtyInput = document.getElementById('qty-input');
    document.querySelector('.qty-btn-minus').addEventListener('click', function() {
        var val = parseInt(qtyInput.value) || 1;
        if (val > 1) qtyInput.value = val - 1;
    });
    document.querySelector('.qty-btn-plus').addEventListener('click', function() {
        var val = parseInt(qtyInput.value) || 1;
        qtyInput.value = val + 1;
    });
});
</script>
<style>
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
