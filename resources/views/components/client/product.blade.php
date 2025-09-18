<!-- FEATURED PRODUCTS SECTION START -->
<div class="featured-products-section section-bg-tb pt-80 pb-55">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2 class="mb-3">SẢN PHẨM NỔI BẬT</h2>
            <div class="title-divider">
                <span class="divider-line"></span>
                <i class="zmdi zmdi-star"></i>
                <span class="divider-line"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="tab-content">
                    <div id="popular-product" class="tab-pane active show">
                        <div class="row">
                            @foreach ($products as $product)
                            <!-- product-item start -->
                            <div class="col-lg-3 col-md-4">
                                <div class="product-item">
                                    <div class="product-img">
                                        <a href="{{ url('product/' . $product->product_slug) }}">
                                            <img src="{{ \Illuminate\Support\Str::startsWith($product->product_image, ['http://','https://','/']) ? $product->product_image : asset($product->product_image) }}"
                                                alt="{{ $product->product_name }}" />
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <h6 class="product-title">
                                            <a href="{{ url('product/' . $product->product_slug) }}">{{ $product->product_name }}</a>
                                        </h6>
                                        @if ($product->product_price_discount > 0)
                                        <div class="price-container">
                                            <span class="sale-price text-danger">
                                                {{ number_format($product->product_price_discount) }} đ
                                            </span>
                                            <span class="original-price text-muted text-decoration-line-through">
                                                {{ number_format($product->product_price) }} đ
                                            </span>
                                        </div>
                                        @else
                                        <div class="price-container">
                                            <span class="sale-price">
                                                {{ number_format($product->product_price) }} đ
                                            </span>
                                        </div>
                                        @endif
                                        <div class="product-actions">
                                            <a href="#" class="action-btn add-to-favorite" data-product-id="{{ $product->product_id }}" title="Thêm vào yêu thích" onclick="addToFavorite(event, {{ $product->product_id }}); return false;">
                                                <i class="zmdi zmdi-favorite"></i>
                                            </a>
                                            <a href="{{ url('cart/add/' . $product->product_id) }}" class="action-btn add-to-cart" title="Thêm vào giỏ hàng">
                                                <i class="zmdi zmdi-shopping-cart"></i>
                                            </a>
                                        </div>
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
<!-- PRODUCT TAB SECTION END -->

<style>
    /* Featured Products Title Styling */
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

    /* Price Styles */
    .price-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1px;
        margin: 0 0 12px 0;
        width: 100%;
    }
    
    .sale-price {
        font-size: 1.4rem;
        font-weight: 700;
        color: #ee4d2d;
        line-height: 1.2;
        width: 100%;
        text-align: center;
        display: block;
    }
    
    .original-price {
        font-size: 0.9rem;
        color: #666;
        text-decoration: line-through;
        width: 100%;
        text-align: center;
        display: block;
        margin: 0 auto;
        padding: 0;
    }
    
    /* Product Item Styles */
    .product-item {
        position: relative;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 30px;
        border: 1px solid #eee;
    }
    
    .product-img {
        position: relative;
        overflow: hidden;
        background: #f9f9f9;
        padding-top: 100%;
    }
    
    .product-img img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 15px;
        transition: transform 0.5s ease;
    }
    
    .product-info {
        padding: 15px;
        position: relative;
    }
    
    .product-title {
        margin: 0 0 8px 0;
        line-height: 1.4;
        min-height: 42px;
        max-height: 54px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .product-title a {
        color: #333;
        font-size: 1.2rem;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
        line-height: 1.4;
    }
    
    .pro-price {
    color: #d70018;
    font-size: 13px;   /* giảm vừa phải, dễ đọc */
    font-weight: 600;
    margin: 5px 0;
}

.pro-price-sale {
    color: #666;
    font-size: 11px;   /* nhỏ hơn giá bán, nhưng vẫn nhìn rõ */
    text-decoration: line-through;
    margin-bottom: 3px;
}

    
    /* Product Actions */
    .product-actions {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 10px;
        padding: 5px 0;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        color: #666;
        text-align: center;
        transition: all 0.2s ease;
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
    
    
    /* Hover Effects */
    .product-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .product-item:hover .product-img img {
        transform: scale(1.05);
    }
    
    .product-item:hover .action-button {
        opacity: 1;
        transform: translateY(0);
    }
    
    .action-button a:hover {
        background: #ff6b00;
        color: #fff;
        transform: rotate(360deg);
    }
    
    .product-title a:hover {
        color: #ff6b00;
    }

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
</script>