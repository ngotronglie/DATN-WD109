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
                                        <span>Sort by :</span>
                                        <select>
                                            <option value="volvo">Newest items</option>
                                            <option value="saab">Saab</option>
                                            <option value="mercedes">Mercedes</option>
                                            <option value="audi">Audi</option>
                                        </select>
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
                                            @php $variant = $product->variants->first(); @endphp
                                            <div class="col-lg-4 col-md-6">
                                                <div class="product-item">
                                                    <div class="product-img">
                                                        <a href="{{ route('product.detail', ['slug' => $product->slug]) }}">
                                                            <img src="{{ $variant ? asset('storage/' . $variant->image) : asset('default.jpg') }}" alt="{{ $product->name }}">
                                                        </a>
                                                    </div>
                                                    <div class="product-info">
                                                        <h6 class="product-title">
                                                            <a href="{{ route('product.detail', ['slug' => $product->slug]) }}">{{ $product->name }}</a>
                                                        </h6>
                                                        <h3 class="pro-price">{{ $variant ? number_format($variant->price, 0, ',', '.') : 'N/A' }} đ</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        </div>
                                        {{ $products->links() }}
                                    </div>
                                    <!-- list-view -->
                                    <div id="list-view" class="tab-pane" role="tabpanel">
                                        <div class="row">
                                            <!-- product-item start -->
                                            <div class="col-lg-12">
                                                <div class="shop-list product-item">
                                                    <div class="product-img">
                                                        <a href="single-product.html">
                                                          <img src="{{asset('frontend/img/product/7.jpg')}}" alt="" />
                                                        </a>
                                                    </div>
                                                    <div class="product-info">
                                                        <div class="clearfix">
                                                            <h6 class="product-title f-left">
                                                                <a href="single-product.html">Dummy Product Name </a>
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
                                                        <h3 class="pro-price">$ 869.00</h3>
                                                        <p>There are many variations of passages of Lorem Ipsum available, but
                                                            the majority have suffered alteration in some form, by injected
                                                            humour, or randomised words which don't look even slightly
                                                            believable.</p>
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
                                            <!-- product-item end -->
                                            <!-- product-item start -->
                                            <div class="col-lg-12">
                                                <div class="shop-list product-item">
                                                    <div class="product-img">
                                                        <a href="single-product.html">
                                                          <img src="{{asset('frontend/img/product/10.jpg')}}" alt="" />
                                                        </a>
                                                    </div>
                                                    <div class="product-info">
                                                        <div class="clearfix">
                                                            <h6 class="product-title f-left">
                                                                <a href="single-product.html">Dummy Product Name </a>
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
                                                        <h3 class="pro-price">$ 869.00</h3>
                                                        <p>There are many variations of passages of Lorem Ipsum available, but
                                                            the majority have suffered alteration in some form, by injected
                                                            humour, or randomised words which don't look even slightly
                                                            believable.</p>
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
                                            <!-- product-item end -->
                                            <!-- product-item start -->
                                            <div class="col-lg-12">
                                                <div class="shop-list product-item">
                                                    <div class="product-img">
                                                        <a href="single-product.html">
                                                          <img src="{{asset('frontend/img/product/4.jpg')}}" alt="" />
                                                        </a>
                                                    </div>
                                                    <div class="product-info">
                                                        <div class="clearfix">
                                                            <h6 class="product-title f-left">
                                                                <a href="single-product.html">Dummy Product Name </a>
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
                                                        <h3 class="pro-price">$ 869.00</h3>
                                                        <p>There are many variations of passages of Lorem Ipsum available, but
                                                            the majority have suffered alteration in some form, by injected
                                                            humour, or randomised words which don't look even slightly
                                                            believable.</p>
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
                                            <!-- product-item end -->
                                            <!-- product-item start -->
                                            <div class="col-lg-12">
                                                <div class="shop-list product-item">
                                                    <div class="product-img">
                                                        <a href="single-product.html">
                                                          <img src="{{asset('frontend/img/product/8.jpg')}}" alt="" />
                                                        </a>
                                                    </div>
                                                    <div class="product-info">
                                                        <div class="clearfix">
                                                            <h6 class="product-title f-left">
                                                                <a href="single-product.html">Dummy Product Name </a>
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
                                                        <h3 class="pro-price">$ 869.00</h3>
                                                        <p>There are many variations of passages of Lorem Ipsum available, but
                                                            the majority have suffered alteration in some form, by injected
                                                            humour, or randomised words which don't look even slightly
                                                            believable.</p>
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
                                            <!-- product-item end -->
                                            <!-- product-item start -->
                                            <div class="col-lg-12">
                                                <div class="shop-list product-item">
                                                    <div class="product-img">
                                                        <a href="single-product.html">
                                                          <img src="{{asset('frontend/img/product/2.jpg')}}" alt="" />
                                                        </a>
                                                    </div>
                                                    <div class="product-info">
                                                        <div class="clearfix">
                                                            <h6 class="product-title f-left">
                                                                <a href="single-product.html">Dummy Product Name </a>
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
                                                        <h3 class="pro-price">$ 869.00</h3>
                                                        <p>There are many variations of passages of Lorem Ipsum available, but
                                                            the majority have suffered alteration in some form, by injected
                                                            humour, or randomised words which don't look even slightly
                                                            believable.</p>
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
                                            <!-- product-item end -->
                                        </div>
                                    </div>
                                </div>
                                <!-- Tab Content end -->
                                <!-- shop-pagination start -->
                                <ul class="shop-pagination box-shadow text-center ptblr-10-30">
                                    <li><a href="#"><i class="zmdi zmdi-chevron-left"></i></a></li>
                                    <li><a href="#">01</a></li>
                                    <li><a href="#">02</a></li>
                                    <li><a href="#">03</a></li>
                                    <li><a href="#">...</a></li>
                                    <li><a href="#">05</a></li>
                                    <li class="active"><a href="#"><i class="zmdi zmdi-chevron-right"></i></a></li>
                                </ul>
                                <!-- shop-pagination end -->
                            </div>
                        </div>
                        <div class="col-lg-3 order-lg-1 order-2">
                            <!-- widget-categories -->
                            <form method="GET" action="{{ route('shop') }}">
                                <!-- Categories -->
                                <aside class="widget widget-categories box-shadow mb-30">
                                    <h6 class="widget-title border-left mb-20">Categories</h6>
                                    <select name="category" class="form-control mb-2">
                                        <option value="">Tất cả danh mục</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                            @if($cat->children && $cat->children->count())
                                                @foreach($cat->children as $child)
                                                    <option value="{{ $child->id }}" {{ request('category') == $child->id ? 'selected' : '' }}>
                                                        -- {{ $child->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                </aside>

                                <!-- Price -->
                                <aside class="widget shop-filter box-shadow mb-30">
                                    <h6 class="widget-title border-left mb-20">Price</h6>
                                    <div class="price_filter">
                                        <div class="price_slider_amount">
                                            <input type="number" name="min_price" class="form-control mb-1" placeholder="Giá từ" value="{{ request('min_price') }}">
                                            <input type="number" name="max_price" class="form-control mb-1" placeholder="Giá đến" value="{{ request('max_price') }}">
                                        </div>
                                    </div>
                                </aside>

                                <!-- Color -->
                                <aside class="widget widget-color box-shadow mb-30">
                                    <h6 class="widget-title border-left mb-20">Color</h6>
                                    <select name="color" class="form-control mb-2">
                                        <option value="">Tất cả màu</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color->id }}" {{ request('color') == $color->id ? 'selected' : '' }}>
                                                {{ $color->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </aside>

                                <button type="submit" class="btn btn-primary w-100">Lọc sản phẩm</button>
                            </form>
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