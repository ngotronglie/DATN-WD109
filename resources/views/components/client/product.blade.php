


        <!-- PRODUCT TAB SECTION START -->
        <div class="product-tab-section section-bg-tb pt-80 pb-55">
            <div class="container">
                <div class="row">
                    <h2 class="mb-3 font-bold">Sản phẩm phổ biến </h2>
                    <div class="col-lg-12">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!-- popular-product start -->
                            <div id="popular-product" class="tab-pane active show">
                                <div class="row">
                                    @foreach($products as $product)
                                        <!-- product-item start -->
                                        <div class="col-lg-3 col-md-4">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <a href="{{ url('product/' . $product->product_slug) }}">
                                                        <img src="{{ asset($product->product_image) }}" alt="{{ $product->product_name }}" />
                                                    </a>
                                                </div>
                                                <div class="product-info">
                                                    <h6 class="product-title">
                                                        <a href="{{ url('product/' . $product->product_slug) }}">{{ $product->product_name }}</a>
                                                    </h6>
                                                    <div class="product-views mb-1 text-muted" style="font-size: 0.9em;">
                                                        <i class="zmdi zmdi-eye"></i> {{ $product->product_view }} lượt xem
                                                    </div>
                                                    @if($product->product_price_discount)
                                                        <h3 class="pro-price-sale text-danger text-decoration-line-through">$ {{ number_format($product->product_price, 2) }}</h3>
                                                        <h3 class="pro-price">$ {{ number_format($product->product_price_discount, 2) }}</h3>
                                                    @else
                                                        <h3 class="pro-price">$ {{ number_format($product->product_price, 2) }}</h3>
                                                    @endif
                                                    <ul class="action-button">
                                                        <li>
                                                            <a href="#" title="Wishlist"><i
                                                                    class="zmdi zmdi-favorite"></i></a>
                                                        </li>
                                                        <li>
                                                            <a href="#" title="Add to cart"><i
                                                                    class="zmdi zmdi-shopping-cart-plus"></i></a>
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
                                    @endforeach
                                </div>
                            </div>
                            <!-- popular-product end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- PRODUCT TAB SECTION END -->

