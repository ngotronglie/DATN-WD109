{{-- Shopee Style --}}
@props(['products' => collect(), 'limit' => 8])

@if($products->isNotEmpty())
<section class="featured-products-section py-3 px-0">
    <div class="container-fluid px-0">
        {{-- Section Title --}}
        <div class="section-title text-center mb-5">
            <h2 class="mb-3">SẢN PHẨM NỔI BẬT</h2>
            <div class="title-divider">
                <span class="divider-line"></span>
                <i class="zmdi zmdi-star"></i>
                <span class="divider-line"></span>
            </div>
        </div>

        {{-- Products Grid --}}
        <div class="row g-0">
            @foreach($products->take($limit) as $product)
                <div class="col-lg-1 col-md-2 col-sm-2 col-2 mb-1">
                    <div class="featured-product-card">
                        <a href="{{ url('product/' . $product->product_slug) }}" class="text-decoration-none">
                            {{-- Product Image --}}
                            <div class="product-image-container">
                                <img src="{{ \Illuminate\Support\Str::startsWith($product->product_image, ['http://','https://','/']) ? $product->product_image : asset($product->product_image) }}" 
                                     alt="{{ $product->product_name }}" 
                                     class="product-img"
                                     onerror="this.src='{{ asset('images/no-image.png') }}'">
                                @if ($product->product_price_discount > 0)
                                    <div class="discount-tag">-{{ round((($product->product_price - $product->product_price_discount) / $product->product_price) * 100) }}%</div>
                                @endif
                            </div>

                            {{-- Product Info --}}
                            <div class="product-details">
                                <div class="product-name" title="{{ $product->product_name }}">{{ $product->product_name }}</div>
                                
                                <div class="product-price">
                                    @if ($product->product_price_discount > 0)
                                        <div class="sale-price">₫{{ number_format($product->product_price_discount, 0, ',', '.') }}</div>
                                        <div class="original-price">
                                            <span>₫{{ number_format($product->product_price, 0, ',', '.') }}</span>
                                        </div>
                                    @else
                                        <div class="sale-price">₫{{ number_format($product->product_price, 0, ',', '.') }}</div>
                                    @endif
                                </div>
                                
                                {{-- Product Actions --}}
                                <div class="product-actions">
                                    <button class="action-btn add-to-favorite" data-product-id="{{ $product->product_id }}" title="Thêm vào yêu thích" onclick="addToFavorite(event, {{ $product->product_id }}); return false;">
                                        <i class="zmdi zmdi-favorite"></i>
                                    </button>
                                    <button class="action-btn add-to-cart" title="Thêm vào giỏ hàng" 
                                            onclick="showVariantPopup(event, {{ $product->product_id }}, '{{ $product->product_name }}', '{{ \Illuminate\Support\Str::startsWith($product->product_image, ['http://','https://','/']) ? $product->product_image : asset($product->product_image) }}')">
                                        <i class="zmdi zmdi-shopping-cart"></i>
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Product Variant Popup --}}
<div id="variantPopup" class="variant-popup-overlay">
    <div class="variant-popup-content">
        <div class="popup-header">
            <h3>Chọn phân loại hàng</h3>
            <button class="popup-close" onclick="closeVariantPopup()">
                <i class="zmdi zmdi-close"></i>
            </button>
        </div>
        
        <div class="popup-body">
            <div class="product-info">
                <div class="product-image">
                    <img id="popupProductImage" src="" alt="">
                </div>
                <div class="product-details">
                    <h4 id="popupProductName"></h4>
                    <div class="product-price">
                        <span id="popupProductPrice" class="current-price"></span>
                        <span id="popupOriginalPrice" class="original-price"></span>
                    </div>
                    <div class="stock-info">
                        Kho: <span id="popupStock">0</span>
                    </div>
                </div>
            </div>

            <div class="variant-options">
                <div class="color-section" id="colorSection" style="display: none;">
                    <label>Màu sắc:</label>
                    <div class="color-options" id="colorOptions"></div>
                </div>

                <div class="capacity-section" id="capacitySection" style="display: none;">
                    <label>Dung lượng:</label>
                    <div class="capacity-options" id="capacityOptions"></div>
                </div>
            </div>

            <div class="quantity-section">
                <label>Số lượng:</label>
                <div class="quantity-controls">
                    <button type="button" class="qty-btn" onclick="decreaseQuantity()">-</button>
                    <input type="number" id="quantity" value="1" min="1" max="999">
                    <button type="button" class="qty-btn" onclick="increaseQuantity()">+</button>
                </div>
            </div>
        </div>

        <div class="popup-footer">
            <button class="add-to-cart-btn" onclick="addSelectedVariantToCart()" id="addToCartBtn">
                <i class="zmdi zmdi-shopping-cart"></i>
                Thêm vào giỏ hàng
            </button>
        </div>
    </div>
</div>

{{-- Shopee Style CSS --}}
<style>
.featured-products-section {
    margin: 20px 0 10px;
    background: #fff;
    border-top: 1px solid #f5f5f5;
    border-bottom: 1px solid #f5f5f5;
}

.products-header {
    padding: 15px 0;
    border-bottom: 1px solid #f5f5f5;
}

.products-icon {
    font-size: 20px;
    color: #ee4d2d;
}

.section-title h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 1rem;
}

.title-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    max-width: 300px;
}

.divider-line {
    flex: 1;
    height: 2px;
    background-color: #e0e0e0;
}

.title-divider i {
    margin: 0 15px;
    color: #ff6b00;
    font-size: 1.5rem;
}

.view-all-link {
    color: #ee4d2d;
    text-decoration: none;
    font-size: 14px;
}

.view-all-link:hover {
    color: #d73211;
}

/* Product Cards - Shopee Style */
.featured-product-card {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid #f0f0f0;
    position: relative;
    padding: 10px;
    margin: 2px;
}

.featured-product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    z-index: 1;
}

.featured-product-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.product-image-container {
    position: relative;
    width: 100%;
    padding-top: 100%; /* 1:1 Aspect Ratio */
    background: #f5f5f5;
    overflow: hidden;
    border-radius: 4px;
    margin-bottom: 8px;
}

.product-image-container img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 10px;
    transition: transform 0.3s ease;
}

.featured-product-card:hover .product-image-container img {
    transform: scale(1.05);
}

.product-image-container { 
    aspect-ratio: 1.2 / 1; 
    overflow: hidden;
    border-radius: 6px;
    margin: 0 auto;
    width: 80%;
}

/* Force image to fill container and fit the tile */
.featured-products-section .product-image-container .product-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.discount-tag {
    position: absolute;
    top: 8px;
    right: 8px;
    background: #ff6b00;
    color: white;
    font-size: 12px;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 4px;
    z-index: 2;
}

.product-details {
    padding: 2px;
}

.product-name {
    font-size: 13px;
    line-height: 1.3;
    height: 28px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    margin-bottom: 6px;
    color: #333;
    text-align: center;
    font-weight: 600;
}

/* Kiểu giá thông thường */
.product-price {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 8px;
}

.sale-price {
    color: #ee4d2d;
    font-size: 16px;
    font-weight: 600;
    line-height: 1.4;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
}

.discount-badge {
    background: #ee4d2d;
    color: white;
    font-size: 11px;
    padding: 0 4px;
    border-radius: 2px;
    font-weight: 500;
    line-height: 1.4;
}

.original-price {
    color: #9e9e9e;
    font-size: 13px;
    text-decoration: line-through;
    margin-top: 2px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Product Actions */
.product-actions {
    display: flex;
    justify-content: center;
    gap: 6px;
    margin-top: 8px;
    padding: 4px 0;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    color: #666;
    text-align: center;
    transition: all 0.2s ease;
    text-decoration: none;
    font-size: 12px;
}

.action-btn:hover {
    background: #f8f8f8;
    color: #333;
    transform: none;
    box-shadow: none;
}

.action-btn.add-to-favorite {
    color: #999;
}

.action-btn.add-to-favorite[data-favorited="true"],
.action-btn.add-to-favorite:hover {
    color: #ff4757;
    background: #fff;
}

.action-btn.add-to-cart {
    color: #666;
    background: #fff;
}

.action-btn.add-to-cart:hover {
    color: #2ecc71;
    background: #f8f8f8;
}

/* Hiệu ứng khi hover vào sản phẩm */
.featured-product-card:hover .sale-price {
    color: #ff1a2b;
    text-shadow: 0 0 6px rgba(255, 26, 43, 0.3);
}

.featured-product-card:hover .sale-price::before {
    background: rgba(255, 66, 79, 0.15);
    transform: translateY(-50%) skewX(-15deg) scaleX(1.05);
}

.featured-product-card:hover .original-price {
    opacity: 1;
    background: #ebebeb;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
}

/* Thêm hiệu ứng nhấp nháy nhẹ cho giá sale */
@keyframes pricePulse {
    0% { opacity: 0.9; }
    50% { opacity: 1; }
    100% { opacity: 0.9; }
}

.sale-price {
    animation: pricePulse 2s infinite ease-in-out;
}

.featured-product-card:hover .sale-price {
    animation: none;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal-overlay.show {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    background: white;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    transform: scale(0.7);
    transition: transform 0.3s ease;
}

.modal-overlay.show .modal-content {
    transform: scale(1);
}

.modal-icon {
    font-size: 60px;
    margin-bottom: 20px;
    color: #28a745;
}

.modal-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
}

.modal-message {
    font-size: 16px;
    color: #666;
    line-height: 1.5;
    margin-bottom: 25px;
}

.modal-button {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.modal-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.modal-button:active {
    transform: translateY(0);
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 20px;
    background: none;
    border: none;
    font-size: 24px;
    color: #999;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: #f8f9fa;
    color: #333;
}

.success-icon {
    color: #28a745;
}

.error-icon {
    color: #dc3545;
}

.warning-icon {
    color: #ffc107;
}

.info-icon {
    color: #17a2b8;
}

/* Responsive */
@media (max-width: 768px) {
    .products-header {
        padding: 10px 0;
    }
    
    .section-title {
        font-size: 14px;
    }
    
    .product-image-container { 
        aspect-ratio: 1.2 / 1; 
        width: 80%;
    }
}

@media (max-width: 576px) {
    .product-image-container { 
        aspect-ratio: 1.2 / 1; 
        width: 75%;
    }
    
    .sale-price { font-size: 14px; }
    
    .original-price { font-size: 9px; }
    
    .product-name { font-size: 11px; }
}

/* Variant Popup Styles */
.variant-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: 10000;
    display: none;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.variant-popup-overlay.show {
    display: flex;
    opacity: 1;
}

.variant-popup-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    transform: scale(0.8);
    transition: transform 0.3s ease;
}

.variant-popup-overlay.show .variant-popup-content {
    transform: scale(1);
}

.popup-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #f0f0f0;
    background: #fafafa;
    border-radius: 12px 12px 0 0;
}

.popup-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.popup-close {
    background: none;
    border: none;
    font-size: 20px;
    color: #999;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.popup-close:hover {
    background: #f0f0f0;
    color: #333;
}

.popup-body {
    padding: 25px;
}

.product-info {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f0f0f0;
}

.product-image {
    flex-shrink: 0;
}

.product-image img {
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 8px;
    border: 1px solid #f0f0f0;
}

.product-details h4 {
    margin: 0 0 10px 0;
    font-size: 16px;
    font-weight: 600;
    color: #333;
    line-height: 1.4;
}

.product-price {
    margin-bottom: 8px;
}

.current-price {
    color: #ee4d2d;
    font-size: 18px;
    font-weight: 600;
    margin-right: 10px;
}

.original-price {
    color: #999;
    text-decoration: line-through;
    font-size: 14px;
}

.stock-info {
    color: #666;
    font-size: 14px;
}

.variant-options {
    margin-bottom: 25px;
}

.color-section, .capacity-section {
    margin-bottom: 20px;
}

.color-section label, .capacity-section label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.color-options, .capacity-options {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.color-option, .capacity-option {
    padding: 8px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
    min-width: 60px;
    text-align: center;
}

.color-option:hover, .capacity-option:hover {
    border-color: #ee4d2d;
    background: #fff5f5;
}

.color-option.selected, .capacity-option.selected {
    border-color: #ee4d2d;
    background: #ee4d2d;
    color: white;
}

.quantity-section {
    margin-bottom: 25px;
}

.quantity-section label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0;
    width: fit-content;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    overflow: hidden;
}

.qty-btn {
    background: #f8f8f8;
    border: none;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 18px;
    font-weight: 600;
    color: #666;
    transition: all 0.2s ease;
}

.qty-btn:hover {
    background: #e0e0e0;
    color: #333;
}

.qty-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

#quantity {
    border: none;
    width: 60px;
    height: 40px;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
    background: white;
    outline: none;
}

.popup-footer {
    padding: 20px 25px;
    border-top: 1px solid #f0f0f0;
    background: #fafafa;
    border-radius: 0 0 12px 12px;
}

.add-to-cart-btn {
    width: 100%;
    background: linear-gradient(135deg, #ee4d2d, #ff6b47);
    color: white;
    border: none;
    padding: 15px 20px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.add-to-cart-btn:hover {
    background: linear-gradient(135deg, #d73211, #ee4d2d);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(238, 77, 45, 0.3);
}

.add-to-cart-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .variant-popup-content {
        width: 95%;
        margin: 20px;
    }
    
    .popup-header, .popup-body, .popup-footer {
        padding: 15px 20px;
    }
    
    .product-info {
        flex-direction: column;
        text-align: center;
    }
    
    .product-image img {
        width: 100px;
        height: 100px;
        margin: 0 auto;
    }
    
    .color-options, .capacity-options {
        justify-content: center;
    }
    
    .quantity-controls {
        margin: 0 auto;
    }
}
</style>

<script>
    // Cache cho trạng thái yêu thích
    const favoriteCache = new Map();

    // Kiểm tra trạng thái yêu thích khi trang load
    @auth
    document.addEventListener('DOMContentLoaded', function() {
        checkFavoriteStatus();
    });

    // Hàm kiểm tra trạng thái yêu thích của tất cả sản phẩm
    function checkFavoriteStatus() {
        const favoriteButtons = document.querySelectorAll('.add-to-favorite');

        favoriteButtons.forEach(button => {
            const productId = button.getAttribute('data-product-id');
            const icon = button.querySelector('i');

            // Kiểm tra cache trước
            if (favoriteCache.has(productId)) {
                const isFavorite = favoriteCache.get(productId);
                if (isFavorite) {
                    icon.style.color = '#e74c3c';
                    button.setAttribute('data-favorited', 'true');
                }
                return;
            }

            // Gọi API kiểm tra trạng thái yêu thích
            fetch(`/favorites/check/${productId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Lưu vào cache
                    favoriteCache.set(productId, data.is_favorite);

                    if (data.is_favorite) {
                        // Nếu đã yêu thích thì đổi màu icon thành đỏ
                        icon.style.color = '#e74c3c';
                        button.setAttribute('data-favorited', 'true');
                    }
                })
                .catch(error => {
                    console.log('Lỗi khi kiểm tra trạng thái yêu thích:', error);
                });
        });
    }
    @endauth

    // Hàm thêm vào yêu thích
    function addToFavorite(event, productId) {
        event.preventDefault();

        // Lấy button và icon chính xác
        const button = event.target.closest('.add-to-favorite');
        const icon = button.querySelector('i');

        // Kiểm tra xem user đã đăng nhập chưa
        @auth
        // Kiểm tra nếu đã yêu thích rồi thì hiển thị thông báo
        if (button.getAttribute('data-favorited') === 'true') {
            showModal('Sản phẩm đã có trong danh sách yêu thích!', 'info');
            return;
        }

        showModal('Đang thêm sản phẩm vào yêu thích...', 'info');

        // Tạo form data
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('_token', '{{ csrf_token() }}');

        // Gửi request
        fetch('/favorites', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Thay đổi màu icon thành đỏ
                    icon.style.color = '#e74c3c';
                    button.setAttribute('data-favorited', 'true');

                    // Cập nhật cache
                    favoriteCache.set(productId, true);

                    // Hiển thị thông báo thành công
                    showModal('Đã thêm sản phẩm vào danh sách yêu thích!', 'success');
                } else {
                    // Kiểm tra nếu sản phẩm đã có trong yêu thích thì hiển thị thông báo thông tin
                    if (data.message && data.message.includes('đã có trong danh sách yêu thích')) {
                        icon.style.color = '#e74c3c';
                        button.setAttribute('data-favorited', 'true');

                        // Cập nhật cache
                        favoriteCache.set(productId, true);

                        showModal(data.message, 'info');
                    } else {
                        showModal(data.message || 'Có lỗi xảy ra!', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showModal('Có lỗi xảy ra khi thêm vào yêu thích!', 'error');
            });
        @else
        // Nếu chưa đăng nhập, chỉ hiển thị thông báo
        showModal('Vui lòng đăng nhập để thêm sản phẩm vào yêu thích!', 'warning');
        @endauth
    }

    // Hiển thị modal thông báo ở giữa màn hình
    function showModal(message, type = 'info') {
        const icons = {
            success: 'zmdi-check-circle success-icon',
            error: 'zmdi-close-circle error-icon',
            warning: 'zmdi-alert-triangle warning-icon',
            info: 'zmdi-info info-icon'
        };

        const titles = {
            success: 'Thành công!',
            error: 'Có lỗi!',
            warning: 'Cảnh báo!',
            info: 'Thông báo'
        };

        const modalHtml = `
                <div class="modal-overlay" id="notificationModal">
                    <div class="modal-content">
                        <button class="modal-close" onclick="closeModal()">
                            <i class="zmdi zmdi-close"></i>
                        </button>
                        <i class="zmdi ${icons[type]} modal-icon"></i>
                        <h3 class="modal-title">${titles[type]}</h3>
                        <p class="modal-message">${message}</p>
                    </div>
                </div>
            `;

        // Xóa modal cũ nếu có
        const oldModal = document.getElementById('notificationModal');
        if (oldModal) {
            oldModal.remove();
        }

        // Thêm modal mới
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Hiển thị animation
        setTimeout(() => {
            const modal = document.getElementById('notificationModal');
            if (modal) {
                modal.classList.add('show');
            }
        }, 100);

        // Tự động đóng sau 3 giây
        setTimeout(function() {
            closeModal();
        }, 3000);
    }

    // Đóng modal
    function closeModal() {
        const modal = document.getElementById('notificationModal');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => modal.remove(), 300);
        }
    }

    // Biến lưu trữ thông tin sản phẩm và biến thể
    let currentProductData = null;
    let selectedVariant = null;
    let selectedColorId = null;
    let selectedCapacityId = null;

    // Hiển thị popup chọn biến thể hoặc thêm trực tiếp vào giỏ hàng
    function showVariantPopup(event, productId) {
        event.preventDefault();
        event.stopPropagation();

        @guest
        showModal('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!', 'warning');
        return;
        @endguest

        // Gọi API kiểm tra số lượng biến thể trước
        fetch(`/api/product-variants-popup/${productId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const variants = data.data.variants;
                    
                    // Nếu chỉ có 1 biến thể, thêm trực tiếp vào giỏ hàng
                    if (variants.length === 1) {
                        addSingleVariantToCart(variants[0]);
                    } else {
                        // Nếu có nhiều biến thể, hiển thị popup
                        showVariantSelectionPopup(data.data);
                    }
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showModal('Có lỗi khi tải thông tin sản phẩm!', 'error');
            });
    }

    // Thêm sản phẩm có 1 biến thể trực tiếp vào giỏ hàng
    function addSingleVariantToCart(variant) {
        showModal('Đang thêm sản phẩm vào giỏ hàng...', 'info');

        // Tạo form data
        const formData = new FormData();
        formData.append('product_variant_id', variant.id);
        formData.append('quantity', 1);
        formData.append('_token', '{{ csrf_token() }}');

        // Gửi request
        fetch('/api/add-to-cart', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showModal('Đã thêm sản phẩm vào giỏ hàng!', 'success');
                
                // Cập nhật số lượng giỏ hàng nếu có element hiển thị
                if (typeof updateCartCount === 'function') {
                    updateCartCount();
                }
            } else {
                showModal(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showModal('Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
        });
    }

    // Hiển thị popup chọn biến thể cho sản phẩm có nhiều biến thể
    function showVariantSelectionPopup(data) {
        // Hiển thị popup
        const popup = document.getElementById('variantPopup');
        popup.style.display = 'flex';
        setTimeout(() => popup.classList.add('show'), 10);

        // Hiển thị thông tin sản phẩm và biến thể
        currentProductData = data;
        displayProductVariants(data);
    }

    // Hiển thị thông tin sản phẩm và biến thể
    function displayProductVariants(data) {
        // Hiển thị thông tin sản phẩm
        const productImage = document.getElementById('popupProductImage');
        const productName = document.getElementById('popupProductName');
        
        // Hiển thị ảnh sản phẩm
        console.log('Product image data:', data.product.product_image); // Debug log
        
        if (data.product.product_image) {
            let imageSrc = data.product.product_image;
            console.log('Original image src:', imageSrc); // Debug log
            
            productImage.src = imageSrc;
            productImage.alt = data.product.product_name || 'Ảnh sản phẩm';
            productImage.style.display = 'block';
            
            console.log('Final image src:', productImage.src); // Debug log
            
            // Xử lý lỗi khi load ảnh
            productImage.onerror = function() {
                console.log('Image load error, using fallback'); // Debug log
                this.src = 'https://via.placeholder.com/150x150?text=No+Image';
                this.alt = 'Không có ảnh';
            };
            
            // Xử lý khi ảnh load thành công
            productImage.onload = function() {
                console.log('Image loaded successfully'); // Debug log
            };
        } else {
            console.log('No product image, using fallback'); // Debug log
            productImage.src = 'https://via.placeholder.com/150x150?text=No+Image';
            productImage.alt = 'Không có ảnh';
        }
        
        // Hiển thị tên sản phẩm
        if (data.product.product_name) {
            productName.textContent = data.product.product_name;
            productName.title = data.product.product_name;
        } else {
            productName.textContent = 'Tên sản phẩm';
        }
        
        // Hiển thị giá sản phẩm ban đầu
        const currentPrice = data.product.product_price_discount > 0 ? 
            data.product.product_price_discount : data.product.product_price;
        document.getElementById('popupProductPrice').textContent = '₫' + new Intl.NumberFormat('vi-VN').format(currentPrice);
        
        // Hiển thị giá gốc nếu có giảm giá
        const originalPriceEl = document.getElementById('popupOriginalPrice');
        if (data.product.product_price_discount > 0 && data.product.product_price_discount < data.product.product_price) {
            originalPriceEl.textContent = '₫' + new Intl.NumberFormat('vi-VN').format(data.product.product_price);
            originalPriceEl.style.display = 'inline';
        } else {
            originalPriceEl.style.display = 'none';
        }

        // Reset selections
        selectedColorId = null;
        selectedCapacityId = null;
        selectedVariant = null;

        // Hiển thị màu sắc
        const colorSection = document.getElementById('colorSection');
        const colorOptions = document.getElementById('colorOptions');
        
        if (data.colors && data.colors.length > 0) {
            colorSection.style.display = 'block';
            colorOptions.innerHTML = '';
            
            data.colors.forEach(color => {
                const colorDiv = document.createElement('div');
                colorDiv.className = 'color-option';
                colorDiv.textContent = color.name;
                colorDiv.onclick = () => selectColor(color.id, colorDiv);
                colorOptions.appendChild(colorDiv);
            });

            // Tự động chọn màu đầu tiên nếu chỉ có 1 màu
            if (data.colors.length === 1) {
                selectColor(data.colors[0].id, colorOptions.firstChild);
            }
        } else {
            colorSection.style.display = 'none';
        }

        // Hiển thị dung lượng
        const capacitySection = document.getElementById('capacitySection');
        const capacityOptions = document.getElementById('capacityOptions');
        
        if (data.capacities && data.capacities.length > 0) {
            capacitySection.style.display = 'block';
            capacityOptions.innerHTML = '';
            
            data.capacities.forEach(capacity => {
                const capacityDiv = document.createElement('div');
                capacityDiv.className = 'capacity-option';
                capacityDiv.textContent = capacity.name;
                capacityDiv.onclick = () => selectCapacity(capacity.id, capacityDiv);
                capacityOptions.appendChild(capacityDiv);
            });

            // Tự động chọn dung lượng đầu tiên nếu chỉ có 1 dung lượng
            if (data.capacities.length === 1) {
                selectCapacity(data.capacities[0].id, capacityOptions.firstChild);
            }
        } else {
            capacitySection.style.display = 'none';
        }

        // Nếu chỉ có 1 biến thể, tự động chọn
        if (data.variants.length === 1) {
            selectedVariant = data.variants[0];
            updateProductInfo();
        }

        // Reset quantity
        document.getElementById('quantity').value = 1;
        
        // Enable add to cart button
        document.getElementById('addToCartBtn').disabled = false;
        document.getElementById('addToCartBtn').innerHTML = '<i class="zmdi zmdi-shopping-cart"></i> Thêm vào giỏ hàng';
    }

    // Chọn màu sắc
    function selectColor(colorId, element) {
        selectedColorId = colorId;
        
        // Remove selected class from all color options
        document.querySelectorAll('.color-option').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');
        
        findMatchingVariant();
    }

    // Chọn dung lượng
    function selectCapacity(capacityId, element) {
        selectedCapacityId = capacityId;
        
        // Remove selected class from all capacity options
        document.querySelectorAll('.capacity-option').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');
        
        findMatchingVariant();
    }

    // Tìm biến thể phù hợp
    function findMatchingVariant() {
        if (!currentProductData || !currentProductData.variants) return;

        selectedVariant = currentProductData.variants.find(variant => {
            const colorMatch = !selectedColorId || variant.color_id == selectedColorId;
            const capacityMatch = !selectedCapacityId || variant.capacity_id == selectedCapacityId;
            return colorMatch && capacityMatch;
        });

        updateProductInfo();
    }

    // Cập nhật thông tin giá và kho
    function updateProductInfo() {
        if (selectedVariant) {
            // Cập nhật giá
            const currentPrice = selectedVariant.price_sale || selectedVariant.price;
            document.getElementById('popupProductPrice').textContent = '₫' + new Intl.NumberFormat('vi-VN').format(currentPrice);
            
            // Hiển thị giá gốc nếu có giá sale
            const originalPriceEl = document.getElementById('popupOriginalPrice');
            if (selectedVariant.price_sale && selectedVariant.price_sale < selectedVariant.price) {
                originalPriceEl.textContent = '₫' + new Intl.NumberFormat('vi-VN').format(selectedVariant.price);
                originalPriceEl.style.display = 'inline';
            } else {
                originalPriceEl.style.display = 'none';
            }

            // Cập nhật số lượng kho
            document.getElementById('popupStock').textContent = selectedVariant.quantity || 0;
            
            // Cập nhật max quantity
            const quantityInput = document.getElementById('quantity');
            quantityInput.max = selectedVariant.quantity || 0;
            
            // Disable add to cart nếu hết hàng
            const addToCartBtn = document.getElementById('addToCartBtn');
            if (selectedVariant.quantity <= 0) {
                addToCartBtn.disabled = true;
                addToCartBtn.innerHTML = 'Hết hàng';
            } else {
                addToCartBtn.disabled = false;
                addToCartBtn.innerHTML = '<i class="zmdi zmdi-shopping-cart"></i> Thêm vào giỏ hàng';
            }
        }
    }

    // Tăng số lượng
    function increaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value) || 1;
        const maxValue = parseInt(quantityInput.max) || 999;
        
        if (currentValue < maxValue) {
            quantityInput.value = currentValue + 1;
        }
    }

    // Giảm số lượng
    function decreaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value) || 1;
        
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    }

    // Thêm biến thể đã chọn vào giỏ hàng
    function addSelectedVariantToCart() {
        if (!selectedVariant) {
            showModal('Vui lòng chọn phân loại sản phẩm!', 'warning');
            return;
        }

        const quantity = parseInt(document.getElementById('quantity').value) || 1;
        
        if (quantity > selectedVariant.quantity) {
            showModal('Số lượng vượt quá kho hàng!', 'warning');
            return;
        }

        // Disable button và hiển thị loading
        const addToCartBtn = document.getElementById('addToCartBtn');
        addToCartBtn.disabled = true;
        addToCartBtn.innerHTML = '<div class="loading-spinner"></div> Đang thêm...';

        // Tạo form data
        const formData = new FormData();
        formData.append('product_variant_id', selectedVariant.id);
        formData.append('quantity', quantity);
        formData.append('_token', '{{ csrf_token() }}');

        // Gửi request
        fetch('/api/add-to-cart', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showModal('Đã thêm sản phẩm vào giỏ hàng!', 'success');
                closeVariantPopup();
                
                // Cập nhật số lượng giỏ hàng nếu có element hiển thị
                if (typeof updateCartCount === 'function') {
                    updateCartCount();
                }
            } else {
                showModal(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
                addToCartBtn.disabled = false;
                addToCartBtn.innerHTML = '<i class="zmdi zmdi-shopping-cart"></i> Thêm vào giỏ hàng';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showModal('Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
            addToCartBtn.disabled = false;
            addToCartBtn.innerHTML = '<i class="zmdi zmdi-shopping-cart"></i> Thêm vào giỏ hàng';
        });
    }

    // Đóng popup
    function closeVariantPopup() {
        const popup = document.getElementById('variantPopup');
        popup.classList.remove('show');
        setTimeout(() => {
            popup.style.display = 'none';
            // Reset data
            currentProductData = null;
            selectedVariant = null;
            selectedColorId = null;
            selectedCapacityId = null;
        }, 300);
    }

    // Đóng popup khi click outside
    document.addEventListener('click', function(event) {
        const popup = document.getElementById('variantPopup');
        const popupContent = document.querySelector('.variant-popup-content');
        
        if (popup && popup.classList.contains('show') && !popupContent.contains(event.target)) {
            closeVariantPopup();
        }
    });
</script>