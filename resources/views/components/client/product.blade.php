<!-- PHẦN SẢN PHẨM PHỔ BIẾN BẮT ĐẦU -->
<div class="product-tab-section section-bg-tb pt-80 pb-55">
    <div class="container">
        <div class="row">
            <h2 class="mb-3 font-bold">Sản phẩm phổ biến</h2>
            <div class="col-lg-12">
                <!-- Tab nội dung -->
                <div class="tab-content">
                    <!-- Bắt đầu sản phẩm phổ biến -->
                    <div id="popular-product" class="tab-pane active show">
                        <div class="row">
                            @foreach($products as $product)
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
                                                <h3 class="pro-price-sale text-danger text-decoration-line-through">
                                                    {{ number_format($product->product_price, 0, ',', '.') }}₫
                                                </h3>
                                                <h3 class="pro-price">
                                                    {{ number_format($product->product_price_discount, 0, ',', '.') }}₫
                                                </h3>
                                            @else
                                                <h3 class="pro-price">
                                                    {{ number_format($product->product_price, 0, ',', '.') }}₫
                                                </h3>
                                            @endif
                                            <ul class="action-button">
                                                <li>
                                                    <a href="#" class="add-to-favorite" data-product-id="{{ $product->product_id }}" title="Thêm vào yêu thích" onclick="addToFavorite(event, {{ $product->product_id }})">
                                                        <i class="zmdi zmdi-favorite"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" title="Thêm vào giỏ hàng">
                                                        <i class="zmdi zmdi-shopping-cart-plus"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" title="Thêm vào giỏ hàng">
                                                        <i class="zmdi zmdi-shopping-cart-plus"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Kết thúc sản phẩm phổ biến -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- PHẦN SẢN PHẨM PHỔ BIẾN KẾT THÚC -->
