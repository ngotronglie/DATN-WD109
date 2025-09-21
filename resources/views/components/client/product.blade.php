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
                        </a>

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
                                    <button type="button" class="action-btn add-to-cart" title="Thêm vào giỏ hàng" data-product-id="{{ $product->product_id }}" data-product-slug="{{ $product->product_slug }}">
                                        <i class="zmdi zmdi-shopping-cart"></i>
                                    </button>
                                </div>
                            </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

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
</style>

<script>
    // Chặn chuyển trang khi click các nút action trong card (capturing)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.product-actions .action-btn')) {
            e.preventDefault();
            e.stopPropagation();
        }
        const cartBtn = e.target.closest('.product-actions .action-btn.add-to-cart');
        if (cartBtn) {
            console.log('Cart button clicked, opening variant selector...');
            if (typeof window.openVariantSelector === 'function') { 
                window.openVariantSelector(e, cartBtn); 
            } else {
                console.error('openVariantSelector function not found');
            }
        }
    }, true);

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
        // Nếu chưa đăng nhập, hiển thị prompt đăng nhập giống như trang chi tiết sản phẩm
        const currentPath = window.location.pathname + window.location.search;
        showLoginPrompt(currentPath, 'favorite');
        @endauth
    }

    // Prompt: ask user to login with explicit button (giống như trang chi tiết sản phẩm)
    function showLoginPrompt(redirectPath, purpose) {
        const overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.inset = '0';
        overlay.style.background = 'rgba(0,0,0,0.45)';
        overlay.style.zIndex = '10000';
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';

        const box = document.createElement('div');
        box.style.background = '#fff';
        box.style.borderRadius = '10px';
        box.style.boxShadow = '0 10px 30px rgba(0,0,0,0.2)';
        box.style.padding = '20px 22px';
        box.style.maxWidth = '440px';
        box.style.width = 'calc(100% - 40px)';
        box.style.textAlign = 'center';

        const title = document.createElement('div');
        title.textContent = (purpose === 'favorite')
            ? 'Bạn cần đăng nhập để thêm vào yêu thích'
            : (purpose === 'cart'
                ? 'Bạn cần đăng nhập để thêm vào giỏ hàng'
                : 'Bạn cần đăng nhập để tiếp tục mua hàng');
        title.style.fontWeight = '700';
        title.style.fontSize = '16px';
        title.style.marginBottom = '8px';

        const desc = document.createElement('div');
        desc.textContent = (purpose === 'favorite')
            ? 'Đăng nhập để lưu sản phẩm vào danh sách yêu thích.'
            : (purpose === 'cart'
                ? 'Đăng nhập để thêm sản phẩm vào giỏ hàng.'
                : 'Vui lòng đăng nhập để chuyển đến trang thanh toán.');
        desc.style.color = '#555';
        desc.style.marginBottom = '16px';

        const actions = document.createElement('div');
        actions.style.display = 'flex';
        actions.style.gap = '10px';
        actions.style.justifyContent = 'center';

        const loginBtn = document.createElement('a');
        const target = typeof redirectPath === 'string' && redirectPath.startsWith('/') ? redirectPath : '/checkout';
        loginBtn.href = '{{ route("auth.login") }}?redirect=' + encodeURIComponent(target);
        loginBtn.textContent = 'Đăng nhập';
        loginBtn.style.background = '#ee4d2d';
        loginBtn.style.color = '#fff';
        loginBtn.style.padding = '10px 16px';
        loginBtn.style.borderRadius = '6px';
        loginBtn.style.textDecoration = 'none';
        loginBtn.style.fontWeight = '600';

        const cancelBtn = document.createElement('button');
        cancelBtn.textContent = 'Để sau';
        cancelBtn.style.background = '#f1f3f5';
        cancelBtn.style.border = '1px solid #dee2e6';
        cancelBtn.style.color = '#333';
        cancelBtn.style.padding = '10px 16px';
        cancelBtn.style.borderRadius = '6px';
        cancelBtn.style.cursor = 'pointer';
        cancelBtn.onclick = () => document.body.removeChild(overlay);

        actions.appendChild(loginBtn);
        actions.appendChild(cancelBtn);
        box.appendChild(title);
        box.appendChild(desc);
        box.appendChild(actions);
        overlay.appendChild(box);
        document.body.appendChild(overlay);
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

    // ======================
    // Variant selection popup
    // ======================
    let __variantDataCache = new Map();

    window.openVariantSelector = function(event, el) {
        event.preventDefault();
        event.stopPropagation();
        const productId = el.getAttribute('data-product-id');
        const slug = el.getAttribute('data-product-slug');

        // Build modal UI
        buildVariantModal();

        const overlay = document.getElementById('variantSelectModal');
        overlay.classList.add('show');

        const titleEl = overlay.querySelector('.variant-modal-title');
        const imgEl = overlay.querySelector('.variant-image');
        const colorSelect = overlay.querySelector('#variantColorSelect');
        const capacitySelect = overlay.querySelector('#variantCapacitySelect');
        const qtyInput = overlay.querySelector('#variantQty');
        const priceEl = overlay.querySelector('.variant-price');
        const stockEl = overlay.querySelector('.variant-stock');
        const addBtn = overlay.querySelector('#variantAddToCartBtn');

        // Fetch options (with cache)
        const cacheKey = `pid:${productId}`;
        const fetchPromise = __variantDataCache.has(cacheKey)
            ? Promise.resolve(__variantDataCache.get(cacheKey))
            : fetch(`/api/product-options?product_id=${productId}`)
                .then(r => r.json())
                .then(d => { if (d.success) { __variantDataCache.set(cacheKey, d); return d; } throw new Error(d.message || 'Load options failed'); });

        // Reset UI
        colorSelect.innerHTML = '<option value="">Đang tải...</option>';
        capacitySelect.innerHTML = '<option value="">Đang tải...</option>';
        priceEl.textContent = '...';
        stockEl.textContent = '';
        qtyInput.value = 1;
        addBtn.disabled = true;

        fetchPromise.then(data => {
            console.log('API response:', data);
            titleEl.textContent = data.product?.name || 'Chọn biến thể';
            if (data.product?.image) imgEl.src = data.product.image;

            // Populate colors
            colorSelect.innerHTML = '';
            if (data.colors && data.colors.length > 0) {
                (data.colors || []).forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id; opt.textContent = c.name; colorSelect.appendChild(opt);
                });
            } else {
                colorSelect.innerHTML = '<option value="">Không có màu sắc</option>';
            }

            // Helper: filter capacities for selected color
            const getCapacitiesForColor = (colorId) => {
                const set = new Map();
                (data.combinations || []).forEach(cb => {
                    if (String(cb.color_id) === String(colorId)) {
                        const cap = (data.capacities || []).find(x => String(x.id) === String(cb.capacity_id));
                        if (cap) set.set(String(cap.id), cap);
                    }
                });
                return Array.from(set.values());
            };

            const findCombo = (colorId, capacityId) => {
                return (data.combinations || []).find(cb => String(cb.color_id) === String(colorId) && String(cb.capacity_id) === String(capacityId));
            };

            const renderCapacityOptions = () => {
                const colorId = colorSelect.value;
                const caps = colorId ? getCapacitiesForColor(colorId) : (data.capacities || []);
                capacitySelect.innerHTML = '';
                if (caps.length > 0) {
                    caps.forEach(cap => {
                        const opt = document.createElement('option');
                        opt.value = cap.id; opt.textContent = cap.name; capacitySelect.appendChild(opt);
                    });
                } else {
                    capacitySelect.innerHTML = '<option value="">Không có dung lượng</option>';
                }
            };

            const updatePreview = () => {
                const colorId = colorSelect.value;
                const capacityId = capacitySelect.value;
                const combo = findCombo(colorId, capacityId);
                if (!combo) {
                    priceEl.textContent = 'Hết hàng / Không có biến thể';
                    stockEl.textContent = '';
                    addBtn.disabled = true;
                    return;
                }
                if (combo.image) imgEl.src = combo.image;
                // Sử dụng effective_price từ API response
                const finalPrice = combo.effective_price || (combo.is_flash_sale ? combo.flash_sale_price : (combo.price_sale || combo.price));
                
                if (combo.is_flash_sale) {
                    priceEl.textContent = `Giá Flash Sale: ₫${formatNumber(finalPrice)} (Giá gốc: ₫${formatNumber(combo.original_price)})`;
                } else if (combo.price_sale && combo.price_sale > 0) {
                    priceEl.textContent = `Giá khuyến mãi: ₫${formatNumber(combo.price_sale)} (Giá gốc: ₫${formatNumber(combo.price)})`;
                } else {
                    priceEl.textContent = `Giá: ₫${formatNumber(combo.price)}`;
                }
                stockEl.textContent = `Còn: ${combo.stock}`;
                addBtn.disabled = combo.stock < 1;
                addBtn.dataset.variantId = combo.variant_id;
                addBtn.dataset.isFlash = combo.is_flash_sale ? '1' : '0';
                addBtn.dataset.flashSaleId = combo.flash_sale_id || '';
                addBtn.dataset.flashSalePrice = combo.flash_sale_price || '';
                addBtn.dataset.finalPrice = finalPrice; // Lưu giá cuối cùng để sử dụng khi thêm vào giỏ
            };

            // Initial rendering
            if (colorSelect.options.length > 0) {
                colorSelect.selectedIndex = 0;
            }
            renderCapacityOptions();
            updatePreview();

            colorSelect.onchange = () => { renderCapacityOptions(); updatePreview(); };
            capacitySelect.onchange = () => updatePreview();

            // Qty controls
            overlay.querySelector('#qtyMinus').onclick = () => { const v = Math.max(1, parseInt(qtyInput.value||'1')-1); qtyInput.value = v; };
            overlay.querySelector('#qtyPlus').onclick = () => { const v = Math.max(1, parseInt(qtyInput.value||'1')+1); qtyInput.value = v; };

            // Add to cart
            addBtn.onclick = () => {
                const qty = Math.max(1, parseInt(qtyInput.value||'1'));
                const variantId = addBtn.dataset.variantId;
                if (!variantId) return;

                const fd = new FormData();
                fd.append('variant_id', variantId);
                fd.append('quantity', qty);
                if (addBtn.dataset.isFlash === '1') {
                    fd.append('is_flash_sale', '1');
                    if (addBtn.dataset.flashSaleId) fd.append('flash_sale_id', addBtn.dataset.flashSaleId);
                    if (addBtn.dataset.flashSalePrice) fd.append('flash_sale_price', addBtn.dataset.flashSalePrice);
                }
                fd.append('_token', '{{ csrf_token() }}');

                fetch('/api/add-to-cart', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            showModal('Đã thêm vào giỏ hàng!', 'success');
                            // Update cart count if element exists
                            const countEl = document.querySelector('#cart-count, .cart-count');
                            if (countEl && res.cart_count !== undefined) {
                                countEl.textContent = res.cart_count;
                            }
                            closeVariantModal();
                        } else {
                            showModal(res.message || 'Không thể thêm vào giỏ', 'error');
                        }
                    })
                    .catch(() => showModal('Lỗi mạng khi thêm giỏ hàng', 'error'));
            };
        }).catch(err => {
            console.error('Error loading variant data:', err);
            titleEl.textContent = 'Không tải được dữ liệu biến thể';
            priceEl.textContent = 'Lỗi tải dữ liệu';
            stockEl.textContent = '';
            addBtn.disabled = true;
            colorSelect.innerHTML = '<option value="">Lỗi tải màu sắc</option>';
            capacitySelect.innerHTML = '<option value="">Lỗi tải dung lượng</option>';
        });
    }

    function buildVariantModal() {
        if (document.getElementById('variantSelectModal')) return;
        const html = `
            <div class="modal-overlay show" id="variantSelectModal">
                <div class="modal-content" style="max-width: 520px; text-align: left;">
                    <button class="modal-close" onclick="closeVariantModal()"><i class="zmdi zmdi-close"></i></button>
                    <div style="display:flex; gap:16px; align-items:flex-start;">
                        <img class="variant-image" src="https://via.placeholder.com/120x120/f5f5f5/999999?text=No+Image" alt="variant" style="width:120px;height:120px;object-fit:cover;border-radius:8px;background:#f5f5f5;">
                        <div style="flex:1;">
                            <h4 class="variant-modal-title" style="margin:0 0 8px; font-weight:700; font-size:18px;">Chọn biến thể</h4>
                            <div class="variant-price" style="color:#ee4d2d; font-weight:700; margin-bottom:4px;">...</div>
                            <div class="variant-stock" style="color:#666; font-size:13px; margin-bottom:12px;"></div>

                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                                <label style="display:block;">
                                    <span style="display:block; font-size:12px; color:#666; margin-bottom:6px;">Màu sắc</span>
                                    <select id="variantColorSelect" style="width:100%; padding:8px 10px; border:1px solid #e0e0e0; border-radius:6px;"></select>
                                </label>
                                <label style="display:block;">
                                    <span style="display:block; font-size:12px; color:#666; margin-bottom:6px;">Dung lượng</span>
                                    <select id="variantCapacitySelect" style="width:100%; padding:8px 10px; border:1px solid #e0e0e0; border-radius:6px;"></select>
                                </label>
                            </div>

                            <div style="display:flex; align-items:center; gap:10px; margin-top:14px;">
                                <span style="font-size:12px; color:#666;">Số lượng</span>
                                <div style="display:inline-flex; align-items:center; border:1px solid #e0e0e0; border-radius:6px; overflow:hidden;">
                                    <button type="button" id="qtyMinus" style="width:32px; height:32px; border:0; background:#f8f9fa;">-</button>
                                    <input id="variantQty" type="number" value="1" min="1" style="width:50px; height:32px; border:0; text-align:center;">
                                    <button type="button" id="qtyPlus" style="width:32px; height:32px; border:0; background:#f8f9fa;">+</button>
                                </div>
                            </div>

                            <div style="display:flex; justify-content:flex-end; margin-top:16px;">
                                <button id="variantAddToCartBtn" class="modal-button" style="border-radius:6px; padding:10px 18px;">Thêm vào giỏ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        document.body.insertAdjacentHTML('beforeend', html);
    }

    function closeVariantModal() {
        const modal = document.getElementById('variantSelectModal');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => modal.remove(), 200);
        }
    }

    function formatNumber(n) {
        try { return (Math.round((n||0))||0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); } catch(e) { return n; }
    }
</script>
