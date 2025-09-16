@extends('index.clientdashboard')

@section('content')

        <!-- BREADCRUMBS SETCTION START -->
        <div class="breadcrumbs-section plr-200 mb-80 section">
            <div class="breadcrumbs overlay-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="breadcrumbs-inner">
                                <h1 class="breadcrumbs-title">product grid view</h1>
                                <ul class="breadcrumb-list">
                                    <li><a href="index.html">Home</a></li>
                                    <li>product grid view</li>
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

            <!-- SHOP SECTION START -->
            <div class="shop-section mb-80">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-9 order-lg-2 order-1">
                            <div class="shop-content">
                                <!-- shop-option start -->
                                <div class="shop-option box-shadow mb-30 clearfix">
                                    <!-- Nav tabs -->
                                    <ul class="nav shop-tab f-left" role="tablist">
                                        <li>
                                            <a class="active" href="#grid-view" data-bs-toggle="tab"><i
                                                    class="zmdi zmdi-view-module"></i></a>
                                        </li>
                                        <li>
                                            <a href="#list-view" data-bs-toggle="tab"><i class="zmdi zmdi-view-list-alt"></i></a>
                                        </li>
                                    </ul>
                                    <!-- short-by -->
                                    <div class="short-by f-left text-center">
                                        <form method="GET" action="{{ route('shop.index') }}" id="sortForm">
                                            <span>Sort by :</span>
                                            <select name="sort" onchange="document.getElementById('sortForm').submit()">
                                                <option value="">Sắp xếp</option>
                                                <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Tên A-Z</option>
                                                <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Tên Z-A</option>
                                            </select>
                                            @foreach(request()->except('sort', 'page') as $key => $value)
                                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                            @endforeach
                                        </form>
                                    </div>
                                    <!-- showing -->
                                    <div class="showing f-right text-end">
                                        <span>Showing : 01-09 of 17.</span>
                                    </div>
                                </div>
                                <!-- shop-option end -->
                                <!-- Tab Content start -->
                                <div class="tab-content">
                                    <!-- grid-view -->
                                    <div id="grid-view" class="tab-pane active show" role="tabpanel">
                                        <div class="row">
                                            @foreach($products as $product)
                                                @php
                                                    $variant = $product->mainVariant;
                                                @endphp
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="product-item">
                                                        <div class="product-img">
                                                            <a href="{{ route('shop.show', $product->id) }}">
                                                                @if($variant && $variant->image)
                                                                    <img src="{{ asset($variant->image) }}" alt="{{ $product->name }}" />
                                                                @else
                                                                    <span>Chưa có ảnh</span>
                                                                @endif
                                                            </a>
                                                        </div>
                                                        <div class="product-info">
                                                            <h6 class="product-title">
                                                                <a href="{{ route('shop.show', $product->id) }}">{{ $product->name }}</a>
                                                            </h6>
                                                            <div class="pro-rating">
                                                                <a href="#"><i class="zmdi zmdi-star"></i></a>
                                                                <a href="#"><i class="zmdi zmdi-star"></i></a>
                                                                <a href="#"><i class="zmdi zmdi-star"></i></a>
                                                                <a href="#"><i class="zmdi zmdi-star-half"></i></a>
                                                                <a href="#"><i class="zmdi zmdi-star-outline"></i></a>
                                                            </div>
                                                            <h3 class="pro-price">
                                                                @if($variant)
                                                                    {{ number_format($variant->price) }} VNĐ
                                                                @else
                                                                    Chưa có giá
                                                                @endif
                                                            </h3>
                                                            @if($variant && $variant->color)
                                                                {{ $variant->color->name }}
                                                            @endif
                                                            <ul class="action-button">
                                                                <li>
                                                                    <a href="#" title="Wishlist"><i
                                                                            class="zmdi zmdi-favorite"></i></a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#productModal"
                                                                        title="Quickview"><i class="zmdi zmdi-zoom-in"></i></a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" title="Compare"><i
                                                                            class="zmdi zmdi-refresh"></i></a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" title="Add to cart"><i
                                                                            class="zmdi zmdi-shopping-cart-plus"></i></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- list-view -->
                                    <div id="list-view" class="tab-pane" role="tabpanel">
                                        <div class="row">
                                            @foreach($products as $product)
                                                @php
                                                    $variant = $product->mainVariant;
                                                @endphp
                                                <div class="col-lg-12">
                                                    <div class="shop-list product-item">
                                                        <div class="product-img">
                                                            <a href="{{ route('shop.show', $product->id) }}">
                                                                @if($variant && $variant->image)
                                                                    <img src="{{ asset($variant->image) }}" alt="{{ $product->name }}" />
                                                                @else
                                                                    <span>Chưa có ảnh</span>
                                                                @endif
                                                            </a>
                                                        </div>
                                                        <div class="product-info">
                                                            <div class="clearfix">
                                                                <h6 class="product-title f-left">
                                                                    <a href="{{ route('shop.show', $product->id) }}">{{ $product->name }}</a>
                                                                </h6>
                                                                <div class="pro-rating f-right">
                                                                    <a href="#"><i class="zmdi zmdi-star"></i></a>
                                                                    <a href="#"><i class="zmdi zmdi-star"></i></a>
                                                                    <a href="#"><i class="zmdi zmdi-star"></i></a>
                                                                    <a href="#"><i class="zmdi zmdi-star-half"></i></a>
                                                                    <a href="#"><i class="zmdi zmdi-star-outline"></i></a>
                                                                </div>
                                                            </div>
                                                            <h6 class="brand-name mb-30">Brand Name</h6>
                                                            <h3 class="pro-price">
                                                                @if($variant)
                                                                    {{ number_format($variant->price) }} VNĐ
                                                                @else
                                                                    Chưa có giá
                                                                @endif
                                                            </h3>
                                                            @if($variant && $variant->color)
                                                                {{ $variant->color->name }}
                                                            @endif
                                                            <p>{{ $product->description ?? '' }}</p>
                                                            <ul class="action-button">
                                                                <li>
                                                                    <a href="#" title="Wishlist"><i
                                                                            class="zmdi zmdi-favorite"></i></a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#productModal"
                                                                        title="Quickview"><i class="zmdi zmdi-zoom-in"></i></a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" title="Compare"><i
                                                                            class="zmdi zmdi-refresh"></i></a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" title="Add to cart"><i
                                                                            class="zmdi zmdi-shopping-cart-plus"></i></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <!-- Tab Content end -->
                                <!-- shop-pagination start -->
                                <div class="mt-3">
                                    {{ $products->links('pagination::bootstrap-4') }}
                                </div>
                                <!-- shop-pagination end -->
                            </div>
                        </div>
                        <div class="col-lg-3 order-lg-1 order-2">
                            <!-- widget-categories -->
                            <aside class="widget widget-categories box-shadow mb-30">
                                <h6 class="widget-title border-left mb-20">Danh mục</h6>
                                <div id="cat-treeview" class="product-cat">
                                    <ul>
                                        <li>
                                            <a href="{{ route('shop.index', request()->except('category','page')) }}" class="{{ request()->filled('category') ? '' : 'fw-bold text-primary' }}">
                                                Tất cả
                                            </a>
                                        </li>
                                        @foreach($allCategories as $cat)
                                            <li>
                                                <a href="{{ route('shop.index', array_merge(request()->except('page'), ['category' => $cat->ID])) }}" class="{{ (string)request('category') === (string)$cat->ID ? 'fw-bold text-primary' : '' }}">
                                                    {{ $cat->Name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </aside>
                            <!-- shop-filter -->
                            <aside class="widget shop-filter box-shadow mb-30">
                                <h6 class="widget-title border-left mb-20">Giá</h6>
                                <form method="GET" action="{{ route('shop.index') }}">
                                    <div class="mb-2 small text-muted">Khoảng giá: <span id="price-range-text">—</span></div>
                                    <div id="slider-range" class="mb-3"></div>
                                    <div class="input-group mb-2">
                                        <input id="min_price" type="number" name="min_price" class="form-control" placeholder="Giá từ" value="{{ request('min_price') }}" min="0">
                                        <span class="input-group-text">-</span>
                                        <input id="max_price" type="number" name="max_price" class="form-control" placeholder="Giá đến" value="{{ request('max_price') }}" min="0">
                                    </div>
                                    @foreach(request()->except('min_price','max_price','page') as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <button type="submit" class="btn btn-warning btn-block w-100">Lọc</button>
                                    @if(request()->filled('min_price') || request()->filled('max_price'))
                                        <a href="{{ route('shop.index', request()->except('min_price','max_price','page')) }}" class="btn btn-link w-100 mt-2 p-0">Xóa lọc giá</a>
                                    @endif
                                </form>
                            </aside>
                            <!-- widget-color -->
                           
                            <!-- operating-system -->
                
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
                                          <img src="{{asset('frontend/img/product/12.jpg')}}" alt="" />
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

        </div>
        <!-- End page content -->

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script>
$(document).ready(function() {
    var min = {{ request('min_price', 0) }};
    var max = {{ request('max_price', 1000) }};
    $("#slider-range").slider({
        range: true,
        min: 0,
        max: 1000,
        values: [min, max],
        slide: function(event, ui) {
            $("#min_price").val(ui.values[0]);
            $("#max_price").val(ui.values[1]);
            $("#price-range-text").text('$' + ui.values[0] + ' - $' + ui.values[1]);
        }
    });
    // Set initial values on page load
    $("#min_price").val($("#slider-range").slider("values", 0));
    $("#max_price").val($("#slider-range").slider("values", 1));
    $("#price-range-text").text('$' + $("#slider-range").slider("values", 0) + ' - $' + $("#slider-range").slider("values", 1));
});
</script>
@endpush