@extends('index.clientdashboard')

@section('content')

<!-- Shopee-style Breadcrumbs -->
<div class="shopee-breadcrumbs">
        <div class="container">
        <div class="breadcrumb-nav">
            <a href="{{ route('home') }}" class="breadcrumb-link">
                <i class="zmdi zmdi-home"></i>
                Trang chủ
            </a>
            <i class="zmdi zmdi-chevron-right breadcrumb-arrow"></i>
            <span class="breadcrumb-current">{{ $product->name ?? 'Sản phẩm' }}</span>
        </div>
    </div>
</div>

<!-- Main Product Section -->
<section class="product-detail-section">
        <div class="container">
        <div class="product-detail-container">
            <div class="row">

                <!-- Product Images -->
                <div class="col-lg-5">
                    <div class="product-images-container">
                        <!-- Main Image -->
                        <div class="main-image-wrapper">
                            <img id="product-image" src="{{ isset($variants[0]) ? (str_starts_with($variants[0]->image, 'http') ? $variants[0]->image : asset('storage/' . $variants[0]->image)) : asset('images/no-image.png') }}" 
                                 alt="{{ $product->name }}" 
                                 class="main-product-image"
                                 onerror="this.src='{{ asset('images/no-image.png') }}'">
                                    </div>
                        
                        <!-- Thumbnail Images -->
                        <div class="thumbnail-gallery">
                            @php $seenColorIds = []; @endphp
                                        @foreach($variants as $variant)
                                @if(!in_array($variant->color_id, $seenColorIds))
                                    @php $seenColorIds[] = $variant->color_id; @endphp
                                    <div class="thumbnail-item" onclick="changeMainImage('{{ str_starts_with($variant->image, 'http') ? $variant->image : asset('storage/' . $variant->image) }}')">
                                        <img src="{{ str_starts_with($variant->image, 'http') ? $variant->image : asset('storage/' . $variant->image) }}" 
                                             alt="Thumbnail" 
                                             class="thumbnail-image"
                                             onerror="this.src='{{ asset('images/no-image.png') }}'">
                                        </div>
                                @endif
                                        @endforeach
                                    </div>
                                </div>
                                            </div>
                <!-- Product Info -->
                <div class="col-lg-7">
                    <div class="product-info-container">
                        <!-- Product Title -->
                        <h1 class="product-title">{{ $product->name }}</h1>
                        
                        <!-- Product Meta -->
                        <div class="product-meta">
                            <div class="brand-badge">{{ $product->category->Name ?? 'Thương hiệu' }}</div>
                            <div class="view-count">
                                                <i class="zmdi zmdi-eye"></i> {{ $product->view_count }} lượt xem
                                            </div>
                                        </div>

                        <!-- Price Section -->
                        <div class="price-section">
                            @php
                                $hasSale = isset($variants[0]) && $variants[0]->price_sale && $variants[0]->price_sale < $variants[0]->price;
                            @endphp
                            <div id="current-price" class="current-price">
                                ₫{{ isset($variants[0]) ? number_format($hasSale ? $variants[0]->price_sale : $variants[0]->price, 0, ',', '.') : '0' }}
                            </div>
                            @if($hasSale)
                                <div id="original-price" class="original-price">₫{{ number_format($variants[0]->price, 0, ',', '.') }}</div>
                                <div id="savings-badge" class="savings-badge">Tiết kiệm ₫{{ number_format($variants[0]->price - $variants[0]->price_sale, 0, ',', '.') }}</div>
                            @endif
                        </div>
                        <!-- Variant Selection -->
                        <div class="variant-selection">
                            <!-- Color Selection -->
                            <div class="variant-group">
                                <label class="variant-label">Màu sắc:</label>
                                <div class="variant-options">
                                                    @foreach($colors as $color)
                                    <label class="variant-option">
                                                        <input type="radio" name="color" value="{{ $color->id }}" @if($loop->first) checked @endif>
                                        <span class="option-text">{{ $color->name }}</span>
                                                    </label>
                                                    @endforeach
                                                </div>
                                            </div>

                            <!-- Capacity Selection -->
                            <div class="variant-group">
                                <label class="variant-label">Dung lượng:</label>
                                <div class="variant-options">
                                                    @foreach($capacities as $capacity)
                                    <label class="variant-option">
                                                        <input type="radio" name="capacity" value="{{ $capacity->id }}" @if($loop->first) checked @endif>
                                        <span class="option-text">{{ $capacity->name }}</span>
                                                    </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                        <!-- Quantity Selection -->
                        <div class="quantity-section">
                            <label class="quantity-label">Số lượng:</label>
                            <div class="quantity-controls">
                                <button type="button" class="qty-btn" onclick="decreaseQuantity()">-</button>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="999" class="qty-input">
                                <button type="button" class="qty-btn" onclick="increaseQuantity()">+</button>
                            </div>
                            <div id="stock-info" class="mt-2" style="font-size: 14px; color:#555;">
                                <!-- Số lượng tồn kho sẽ được cập nhật ở đây -->
                            </div>
                        </div>
                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <!-- <button type="button" class="btn-buy-now" onclick="buyNow()">
                                <i class="zmdi zmdi-shopping-cart"></i>
                                Mua ngay
                            </button> -->
                            <button type="button" class="btn-add-cart" onclick="addToCart()">
                                <i class="zmdi zmdi-shopping-cart"></i>
                                Thêm vào giỏ
                            </button>
                            <button type="button" class="btn-favorite" onclick="addToWishlist()">
                                <i class="zmdi zmdi-favorite"></i>
                            </button>
                                            </div>

                        <!-- Social Share -->
                        <div class="social-share">
                            <span class="share-label">Chia sẻ:</span>
                            <div class="share-buttons">
                                <a href="#" class="share-btn facebook"><i class="zmdi zmdi-facebook"></i></a>
                                <a href="#" class="share-btn twitter"><i class="zmdi zmdi-twitter"></i></a>
                                <a href="#" class="share-btn pinterest"><i class="zmdi zmdi-pinterest"></i></a>
                                <a href="#" class="share-btn instagram"><i class="zmdi zmdi-instagram"></i></a>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
        </div>
    </div>
</section>

<!-- Product Details Tabs -->
<section class="product-tabs-section">
    <div class="container">
        <div class="product-tabs-container">
            <div class="tabs-navigation">
                <button class="tab-btn active" onclick="switchTab('description')">Mô tả sản phẩm</button>
                <button class="tab-btn" onclick="switchTab('comments')">Bình luận ({{ $comments->count() ?? 0 }})</button>
            </div>
            
                                    <div class="tab-content">
                <div id="description-tab" class="tab-panel active">
                    <div class="product-description">
                        {!! $product->description !!}
                                        </div>
                                    </div>
                
                <div id="comments-tab" class="tab-panel">
                    <div class="product-comments p-4">
                                    @if(session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                        
                                        @php $comments = $comments ?? collect(); @endphp
                        
                        @if($comments->count() > 0)
                            @foreach($comments as $comment)
                                @if(isset($comment->is_hidden) && $comment->is_hidden)
                                    @continue
                                @endif
                                <div class="comment-item mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>{{ $comment->user->name ?? 'Khách' }}</strong>
                                        <span class="text-muted small">{{ $comment->created_at->diffForHumans() }}</span>
                                                            </div>
                                    <div class="comment-content">
                                        {{ $comment->content }}
                                                    </div>
                                    
                                    @if($comment->replies && $comment->replies->count() > 0)
                                        <div class="replies mt-3 ms-4">
                                            @foreach($comment->replies as $reply)
                                                <div class="reply-item mb-2 p-2 border-start border-3 ps-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <strong class="small">{{ $reply->user->name ?? 'Khách' }}</strong>
                                                        <span class="text-muted small">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                                    <div class="small">
                                                        {{ $reply->content }}
                                    </div>
                                            </div>
                                            @endforeach
                                </div>
                                    @endif
                                    @auth
                                        @if(auth()->user()->role_id == 2)
                                        <div class="admin-reply-form mt-3 ms-4">
                                            <form method="POST" action="{{ route('product.comments.store', $product->id) }}">
                                                @csrf
                                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                <div class="mb-2">
                                                    <textarea name="content" class="form-control form-control-sm" rows="2" placeholder="Phản hồi của Admin..."></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Gửi phản hồi</button>
                                            </form>
                                        </div>
                                        @endif
                                    @endauth
                            </div>
                                                    @endforeach
                        @else
                            <p class="text-muted">Chưa có bình luận nào cho sản phẩm này.</p>
                                                @endif
                        @auth
                            <div class="mt-4">
                                <h6>Thêm bình luận</h6>
                                <form method="POST" action="{{ route('product.comments.store', $product->id) }}" class="mt-3">
                                    @csrf
                                    <div class="form-group">
                                        <textarea name="content" class="form-control" rows="3" placeholder="Nhập bình luận của bạn..." required></textarea>
                                    </div>
                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-primary btn-sm">Gửi bình luận</button>
                            </div>
                                </form>
                                                </div>
                        @else
                            <div class="alert alert-info mt-3">
                                Vui lòng <a href="{{ route('auth.login') }}">đăng nhập</a> để bình luận.
                                                </div>
                        @endauth
                                            </div>
                                    </div>
                            </div>
                        </div>
                    </div>
</section>



@endsection

@section('script-client')
<script>
// Center Notification (toast-like)
function showCenterNotice(message, type = 'success') {
    const wrapper = document.createElement('div');
    wrapper.style.position = 'fixed';
    wrapper.style.top = '50%';
    wrapper.style.left = '50%';
    wrapper.style.transform = 'translate(-50%, -50%)';
    wrapper.style.zIndex = '9999';
    wrapper.style.display = 'flex';
    wrapper.style.alignItems = 'center';
    wrapper.style.justifyContent = 'center';
    wrapper.style.pointerEvents = 'none';

    const box = document.createElement('div');
    box.style.minWidth = '300px';
    box.style.maxWidth = '420px';
    box.style.background = '#fff';
    box.style.borderRadius = '10px';
    box.style.boxShadow = '0 10px 30px rgba(0,0,0,0.15)';
    box.style.padding = '16px 18px';
    box.style.fontSize = '15px';
    box.style.fontWeight = '600';
    box.style.textAlign = 'center';
    box.style.border = type === 'success' ? '1px solid #28a745' : '1px solid #dc3545';
    box.style.color = type === 'success' ? '#155724' : '#721c24';
    box.style.background = type === 'success' ? '#d4edda' : '#f8d7da';

    box.textContent = message;
    wrapper.appendChild(box);
    document.body.appendChild(wrapper);

    setTimeout(() => wrapper.remove(), 2000);
}

// Prompt: ask user to login with explicit button
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
    loginBtn.href = '/login?redirect=' + encodeURIComponent(target);
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

// Change main image function
function changeMainImage(imageSrc) {
    document.getElementById('product-image').src = imageSrc;
}

// Quantity controls
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const maxQuantity = parseInt(quantityInput.getAttribute('max'));
    const currentQuantity = parseInt(quantityInput.value);
    
    if (currentQuantity < maxQuantity) {
        quantityInput.value = currentQuantity + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentQuantity = parseInt(quantityInput.value);
    
    if (currentQuantity > 1) {
        quantityInput.value = currentQuantity - 1;
    }
}

// Tab switching
function switchTab(tabName) {
    // Ẩn tất cả các tab panel
    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.remove('active');
    });
    
    // Bỏ active tất cả các nút tab
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Hiển thị tab được chọn
    document.getElementById(tabName + '-tab').classList.add('active');
    
    // Active nút tab được chọn
    document.querySelectorAll('.tab-btn').forEach(btn => {
        if (btn.getAttribute('onclick').includes(tabName)) {
            btn.classList.add('active');
        }
    });
    
    // Cập nhật URL với hash
    window.location.hash = tabName;
}

// Kiểm tra hash URL khi tải trang
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.hash) {
        const tabName = window.location.hash.substring(1);
        if (tabName === 'comments') {
            switchTab('comments');
        }
    }
});

// Get variant data from server via AJAX
function getVariantData(colorId, capacityId) {
    console.log('Fetching variant data for:', { colorId, capacityId });
    
    return fetch('{{ route("get.variant") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_id: {{ $product->id }},
            color_id: colorId,
            capacity_id: capacityId
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
            .then(data => {
        console.log('Variant API response:', data);
        
        if (!data) {
            throw new Error('No data received from server');
        }
        
        if (data.success === false) {
            console.error('API Error:', data.message || 'Unknown error');
            return null;
        }
        
        // Map the response to match the expected format
        const variantData = {
            id: data.id,
            image: data.image || '{{ asset("images/no-image.png") }}',
            price: parseFloat(data.price) || 0,
            price_sale: data.price_sale ? parseFloat(data.price_sale) : null,
            original_price: data.original_price ? parseFloat(data.original_price) : parseFloat(data.price) || 0,
            stock: parseInt(data.stock) || 0,
            is_flash_sale: data.is_flash_sale || false,
            flash_sale_price: data.flash_sale_price ? parseFloat(data.flash_sale_price) : null
        };
        
        console.log('Processed variant data:', variantData);
        return variantData;
    })
    .catch(error => {
        console.error('Error in getVariantData:', error);
        return null;
    });
}

// Available combinations (for disabling options)
const availableCombinations = {
    @foreach($variants as $variant)
    '{{ $variant->color_id }}_{{ $variant->capacity_id }}': true,
    @endforeach
};

// Map to find variantId by color-capacity
const variantIdMap = {
    @foreach($variants as $variant)
    '{{ $variant->color_id }}_{{ $variant->capacity_id }}': {{ $variant->id }},
    @endforeach
};

// Auth flag from server
const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

// Update capacity options based on selected color
function updateCapacityOptions() {
    const selectedColor = document.querySelector('input[name="color"]:checked').value;
    const capacityInputs = document.querySelectorAll('input[name="capacity"]');
    const currentCapacity = document.querySelector('input[name="capacity"]:checked');
    let hasAvailableCapacity = false;
    
    capacityInputs.forEach(input => {
        const capacityId = input.value;
        const combinationKey = selectedColor + '_' + capacityId;
        const isAvailable = availableCombinations[combinationKey];
        
        const label = input.nextElementSibling;
        const wrapper = input.closest('.variant-option') || input.parentElement;
        
        if (isAvailable) {
            input.disabled = false;
            label.style.opacity = '1';
            label.style.cursor = 'pointer';
            label.classList.remove('disabled');
            if (wrapper) wrapper.classList.remove('hidden');
            hasAvailableCapacity = true;
                    } else {
            input.disabled = true;
            label.style.opacity = '0.4';
            label.style.cursor = 'not-allowed';
            label.classList.add('disabled');
            if (wrapper) wrapper.classList.add('hidden');
        }
    });
    
    // If current capacity is not available, select the first available one
    if (currentCapacity && currentCapacity.disabled) {
        const firstAvailable = document.querySelector('input[name="capacity"]:not(:disabled)');
        if (firstAvailable) {
            firstAvailable.checked = true;
        }
    }
}

// Hide colors that have no capacity at all
function filterColorOptions() {
    const colorInputs = Array.from(document.querySelectorAll('input[name="color"]'));
    const capacityInputs = Array.from(document.querySelectorAll('input[name="capacity"]'));
    let selectedColor = document.querySelector('input[name="color"]:checked');
    let selectedWrapper = selectedColor ? (selectedColor.closest('.variant-option') || selectedColor.parentElement) : null;
    let selectedVisible = selectedWrapper ? !selectedWrapper.classList.contains('hidden') : false;

    colorInputs.forEach(input => {
        const colorId = input.value;
        const wrapper = input.closest('.variant-option') || input.parentElement;
        const hasAny = capacityInputs.some(cap => availableCombinations[colorId + '_' + cap.value]);
        if (hasAny) {
            if (wrapper) wrapper.classList.remove('hidden');
        } else {
            if (wrapper) wrapper.classList.add('hidden');
        }
    });

    if (!selectedVisible) {
        const firstVisible = colorInputs.find(inp => {
            const w = inp.closest('.variant-option') || inp.parentElement;
            return w && !w.classList.contains('hidden');
        });
        if (firstVisible) {
            firstVisible.checked = true;
        }
    }
}

// Update product when variant changes
async function updateProductVariant() {
    const selectedColor = document.querySelector('input[name="color"]:checked');
    const selectedCapacity = document.querySelector('input[name="capacity"]:checked');
    
    if (!selectedColor || !selectedCapacity) {
        console.log('No color or capacity selected');
        return;
    }
    
    const colorId = selectedColor.value;
    const capacityId = selectedCapacity.value;
    
    console.log('Updating variant for:', { colorId, capacityId });
    
    try {
        const variant = await getVariantData(colorId, capacityId);
        
        if (!variant) {
            console.error('Variant not found for:', { colorId, capacityId });
            return;
        }
        
        console.log('Variant data:', variant);
        
        // Update image
        const mainImage = document.getElementById('product-image');
        if (mainImage && variant.image) {
            mainImage.src = variant.image;
            mainImage.onerror = function() {
                this.src = '{{ asset("images/no-image.png") }}';
            };
        }
        
        // Xác định giá hiển thị
        let displayPrice, originalPrice;
        
        // Nếu có giá khuyến mãi
        if (variant.price_sale && variant.price_sale < variant.price) {
            displayPrice = parseFloat(variant.price_sale);
            originalPrice = parseFloat(variant.price);
            console.log('Using sale price:', { displayPrice, originalPrice });
        }
        // Giá thường
        else {
            displayPrice = parseFloat(variant.price);
            originalPrice = displayPrice;
            console.log('Using regular price:', { displayPrice });
        }
            
        console.log('Price calculation:', {displayPrice, originalPrice});
        
        // Update price display
        const currentPriceElement = document.getElementById('current-price');
        const originalPriceElement = document.getElementById('original-price');
        const savingsBadge = document.getElementById('savings-badge');
        
        if (currentPriceElement) {
            // Format và hiển thị giá hiện tại
            currentPriceElement.textContent = '₫' + Math.round(displayPrice).toLocaleString('vi-VN');
            
            // Hiển thị giá gốc và % giảm giá nếu có khác biệt
            if (displayPrice < originalPrice) {
                if (originalPriceElement) {
                    originalPriceElement.textContent = '₫' + Math.round(originalPrice).toLocaleString('vi-VN');
                    originalPriceElement.style.display = 'inline';
                    originalPriceElement.classList.add('text-decoration-line-through', 'text-muted', 'ms-2');
                }
                if (savingsBadge) {
                    const savingsPercent = Math.round(((originalPrice - displayPrice) / originalPrice) * 100);
                    savingsBadge.textContent = `Tiết kiệm ${savingsPercent}%`;
                    savingsBadge.style.display = 'inline-block';
                    savingsBadge.className = 'ms-2 badge bg-success';
                }
                } else {
                if (originalPriceElement) {
                    originalPriceElement.style.display = 'none';
                    originalPriceElement.classList.remove('text-decoration-line-through', 'text-muted', 'ms-2');
                }
                if (savingsBadge) {
                    savingsBadge.style.display = 'none';
                    savingsBadge.classList.remove('ms-2', 'badge', 'bg-success');
                }
            }
        }
        
        // Update stock and quantity
        let stock = variant.stock || 0;
        
        const quantityInput = document.getElementById('quantity');
        if (quantityInput) {
            quantityInput.setAttribute('max', Math.max(1, stock));
            
            // Reset quantity if current value exceeds max
            if (parseInt(quantityInput.value) > stock) {
                quantityInput.value = Math.min(1, stock);
            }
        }
        // Update stock display and action buttons
        const stockInfo = document.getElementById('stock-info');
        if (stockInfo) {
            if (stock > 0) {
                stockInfo.textContent = `Còn ${stock} sản phẩm trong kho`;
                stockInfo.style.color = '#198754';
            } else {
                stockInfo.textContent = 'Hết hàng';
                stockInfo.style.color = '#dc3545';
            }
        }
        const btnBuy = document.querySelector('.btn-buy-now');
        const btnAdd = document.querySelector('.btn-add-cart');
        if (btnBuy) btnBuy.disabled = stock <= 0;
        if (btnAdd) btnAdd.disabled = stock <= 0;
        
        // Update variant ID in form
        const variantIdInput = document.getElementById('variant-id');
        if (variantIdInput) {
            variantIdInput.value = variant.id;
        }
        
        // Update URL with variant ID without page reload
        if (history.pushState) {
            const url = new URL(window.location);
            url.searchParams.set('variant', variant.id);
            window.history.pushState({}, '', url);
        }    
    } catch (error) {
        console.error('Error updating variant:', error);
    }
}

// Add event listeners to variant options
document.addEventListener('DOMContentLoaded', function() {
    // Color change
    document.querySelectorAll('input[name="color"]').forEach(radio => {
        radio.addEventListener('change', function() {
            console.log('Color changed to:', this.value);
            updateCapacityOptions();
            
            // Find the first available capacity for the selected color
            const selectedColor = this.value;
            const capacities = Array.from(document.querySelectorAll('input[name="capacity"]:not(:disabled)'));
            
            // Only auto-select capacity if no capacity is selected or if the current one is disabled
            const currentCapacity = document.querySelector('input[name="capacity"]:checked');
            if (!currentCapacity || currentCapacity.disabled) {
                const firstAvailable = capacities[0];
                if (firstAvailable) {
                    firstAvailable.checked = true;
                    console.log('Auto-selected capacity:', firstAvailable.value);
                }
            }
            
            updateProductVariant();
        });
    });
    
    // Capacity change
    document.querySelectorAll('input[name="capacity"]').forEach(radio => {
        radio.addEventListener('change', function() {
            console.log('Capacity changed to:', this.value);
            updateProductVariant();
        });
    });
    
    // Initialize color and capacity options on page load
    filterColorOptions();
    updateCapacityOptions();
    
    // Auto-select first available color and capacity
    const firstAvailableColor = document.querySelector('input[name="color"]:not(:disabled)');
    if (firstAvailableColor) {
        firstAvailableColor.checked = true;
        console.log('Initial color selected:', firstAvailableColor.value);
        
        // Update capacities based on selected color
        updateCapacityOptions();
        
        // Select first available capacity
        const firstAvailableCapacity = document.querySelector('input[name="capacity"]:not(:disabled)');
        if (firstAvailableCapacity) {
            firstAvailableCapacity.checked = true;
            console.log('Initial capacity selected:', firstAvailableCapacity.value);
        }
    }
    
    // Initialize product variant on page load
    updateProductVariant();
});

// Add to cart function
async function addToCart() {
    const quantity = parseInt(document.getElementById('quantity').value || '1', 10);
    const colorInput = document.querySelector('input[name="color"]:checked');
    const capacityInput = document.querySelector('input[name="capacity"]:checked');
    if (!colorInput || !capacityInput) {
        showCenterNotice('Vui lòng chọn đầy đủ màu sắc và dung lượng.', 'error');
        return;
    }

    const colorId = colorInput.value;
    const capacityId = capacityInput.value;
    const key = `${colorId}_${capacityId}`;
    const variantId = variantIdMap[key];
    
    const cartData = {
        product_id: {{ $product->id }},
        product_variant_id: variantId,
            color_id: colorId,
            capacity_id: capacityId,
        quantity: quantity
    };

    if (!isLoggedIn) {
        // Lưu ý định thêm giỏ hàng để tự động thực hiện sau đăng nhập
        try {
            localStorage.setItem('post_login_cart', JSON.stringify({
                ...cartData,
                ts: Date.now()
            }));
        } catch (_) {}
        const currentPath = window.location.pathname + window.location.search;
        showLoginPrompt(currentPath, 'cart');
        return;
    }

    try {
        const res = await fetch('/api/add-to-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(cartData)
        });
        
        if (res.status === 401) {
            const currentPath = window.location.pathname + window.location.search;
            showLoginPrompt(currentPath, 'cart');
            return;
        }
        
        const data = await res.json();
        if (data && data.success) {
            showCenterNotice('Đã thêm vào giỏ hàng!', 'success');
            // Cập nhật badge giỏ hàng ở header
            try {
                if (typeof window.setCartBadge === 'function' && data.cart_count !== undefined) {
                    window.setCartBadge(data.cart_count);
                } else if (typeof window.refreshCartBadgeByApi === 'function') {
                    window.refreshCartBadgeByApi();
                }
            } catch (_) {}
        } else {
            showCenterNotice(data?.message || 'Không thể thêm vào giỏ hàng.', 'error');
        }
    } catch (e) {
        console.error('Error adding to cart:', e);
        showCenterNotice('Có lỗi xảy ra. Vui lòng thử lại.', 'error');
    }
}

// Add to wishlist function
function addToWishlist() {
    const productId = {{ $product->id }};
    if (typeof isLoggedIn !== 'undefined' && !isLoggedIn) {
        // Mark intent to auto-add after login and redirect back to this page
        try { localStorage.setItem('post_login_favorite', JSON.stringify({ product_id: productId, ts: Date.now() })); } catch (_) {}
        const currentPath = window.location.pathname + window.location.search;
        showLoginPrompt(currentPath, 'favorite');
        return;
    }
    fetch('/favorites', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(async (res) => {
        if (res.status === 401) {
            const currentPath = window.location.pathname + window.location.search;
            showLoginPrompt(currentPath, 'favorite');
            return null;
        }
        return res.json();
    })
    .then((data) => {
        if (!data) return;
        if (data.success) {
            const favBtn = document.querySelector('.btn-favorite');
            if (favBtn) {
                favBtn.classList.add('active');
            }
            showCenterNotice('Đã thêm vào danh sách yêu thích!', 'success');
        } else if (data.message) {
            showCenterNotice(data.message, 'error');
        }
    })
    .catch(() => showCenterNotice('Có lỗi xảy ra. Vui lòng thử lại.', 'error'));
}

// Buy Now: add to cart then go to checkout
async function buyNow() {
    const quantity = parseInt(document.getElementById('quantity').value || '1', 10);
    const colorInput = document.querySelector('input[name="color"]:checked');
    const capacityInput = document.querySelector('input[name="capacity"]:checked');
    if (!colorInput || !capacityInput) {
        showCenterNotice('Vui lòng chọn đầy đủ màu sắc và dung lượng.', 'error');
        return;
    }

    const colorId = colorInput.value;
    const capacityId = capacityInput.value;
    const key = `${colorId}_${capacityId}`;
    const variantId = variantIdMap[key];
    if (!variantId) {
        showCenterNotice('Phiên bản sản phẩm không khả dụng.', 'error');
        return;
    }

    // Build checkout_cart item for checkout page only (no server cart)
    const priceText = document.getElementById('current-price')?.textContent || '0';
    const priceNumber = parseInt(priceText.replace(/[^\d]/g, ''), 10) || 0;
    const item = {
        variant_id: variantId,
        quantity: quantity,
        price: priceNumber,
        name: `{{ addslashes($product->name) }}`,
        color: colorInput.nextElementSibling?.textContent?.trim() || '',
        capacity: capacityInput.nextElementSibling?.textContent?.trim() || '',
        image: document.getElementById('product-image')?.src || ''
    };
    
    localStorage.setItem('checkout_cart', JSON.stringify([item]));
    const existingUser = localStorage.getItem('checkout_user');
    if (!existingUser) {
        localStorage.setItem('checkout_user', JSON.stringify({
            fullname: '',
            email: '',
            phone: '',
            street: '',
            ward: '',
            district: '',
            city: '',
            note: '',
            payment: 'cod'
        }));
    }
    if (!isLoggedIn) {
        // Show prompt; only redirect if user confirms (to checkout)
        showLoginPrompt('/checkout');
        return;
    }
    window.location.href = '/checkout';
}
</script>

</script>
<style>
/* Shopee-style Breadcrumbs */
.shopee-breadcrumbs {
    background: #fff;
    padding: 16px 0;
    border-bottom: 1px solid #f0f0f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.breadcrumb-link {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #ee4d2d;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s;
    font-weight: 500;
}

.breadcrumb-link:hover {
    background: #fff5f5;
    color: #d73502;
}

.breadcrumb-arrow {
    color: #ccc;
    font-size: 16px;
    margin: 0 4px;
}

.breadcrumb-current {
    color: #333;
    font-weight: 600;
    padding: 4px 8px;
    background: #f8f9fa;
    border-radius: 4px;
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Main Product Section */
.product-detail-section {
    background: #fff;
    padding: 20px 0;
}

.product-detail-container {
    background: #fff;
        border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
}

/* Product Images */
.product-images-container {
    padding: 20px;
}

.main-image-wrapper {
    position: relative;
    background: #f8f8f8;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 15px;
}

.main-product-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 8px;
}

.thumbnail-gallery {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.thumbnail-item {
    width: 60px;
    height: 60px;
    border-radius: 6px;
    overflow: hidden;
        cursor: pointer;
    border: 2px solid transparent;
    transition: border-color 0.2s;
    }

.thumbnail-item:hover {
    border-color: #ee4d2d;
    }

.thumbnail-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
}

/* Product Info */
.product-info-container {
    padding: 20px;
}

/* Product Title */
.product-title {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
    line-height: 1.3;
}

/* Product Meta */
.product-meta {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.brand-badge {
    background: #ee4d2d;
    color: white;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.view-count {
    color: #666;
    font-size: 14px;
}

/* Price Section */
.price-section {
    margin-bottom: 20px;
}

.current-price {
    font-size: 32px;
    font-weight: 700;
    color: #ee4d2d;
    margin-bottom: 5px;
}

.original-price {
    font-size: 18px;
    color: #999;
    text-decoration: line-through;
    margin-bottom: 8px;
}

.savings-badge {
    background: #d4edda;
    color: #155724;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    display: inline-block;
}

/* Variant Selection */
.variant-selection {
    margin-bottom: 20px;
}

.variant-group {
    margin-bottom: 15px;
}

.variant-label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.variant-options {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.variant-option {
    position: relative;
    cursor: pointer;
}

.variant-option input[type="radio"] {
    display: none;
}

.option-text {
    display: block;
    padding: 8px 16px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fff;
    transition: all 0.2s;
    font-size: 14px;
}

.variant-option input[type="radio"]:checked + .option-text {
    border-color: #ee4d2d;
    background: #fff5f5;
    color: #ee4d2d;
}

.variant-option input[type="radio"]:disabled + .option-text {
    opacity: 0.4;
    cursor: not-allowed;
    background: #f8f9fa;
    color: #999;
    border-color: #e9ecef;
}

.variant-option input[type="radio"]:disabled + .option-text:hover {
    background: #f8f9fa;
    color: #999;
    border-color: #e9ecef;
}

/* Hide unavailable capacity options for selected color */
.variant-option.hidden {
    display: none;
}

/* Quantity Section */
.quantity-section {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.quantity-label {
    font-weight: 600;
    color: #333;
}

.quantity-controls {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    overflow: hidden;
}

.qty-btn {
    background: #f8f9fa;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.2s;
    width: 40px;
    height: 40px;
}

.qty-btn:hover {
    background: #e9ecef;
}

.qty-input {
    border: none;
    text-align: center;
    width: 60px;
    height: 40px;
    font-weight: 600;
    font-size: 16px;
}

.qty-input:focus {
    outline: none;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}

.btn-buy-now {
    flex: 1;
    background: #ee4d2d;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-buy-now:hover {
    background: #d73502;
}

.btn-add-cart {
    flex: 1;
    background: #fff;
    color: #ee4d2d;
    border: 1px solid #ee4d2d;
    padding: 12px 20px;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-add-cart:hover {
    background: #ee4d2d;
    color: white;
}

.btn-favorite {
    background: #fff;
    color: #666;
    border: 1px solid #ddd;
    padding: 12px;
    border-radius: 4px;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.2s;
    min-width: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-favorite:hover {
    background: #fff5f5;
    color: #ee4d2d;
    border-color: #ee4d2d;
}

/* Social Share */
.social-share {
    display: flex;
    align-items: center;
    gap: 10px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
}

.share-label {
    font-size: 14px;
    color: #666;
}

.share-buttons {
    display: flex;
    gap: 8px;
}

.share-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: white;
    font-size: 14px;
    transition: transform 0.2s;
}

.share-btn:hover {
    transform: scale(1.1);
}

.share-btn.facebook { background: #3b5998; }
.share-btn.twitter { background: #1da1f2; }
.share-btn.pinterest { background: #bd081c; }
.share-btn.instagram { background: #e4405f; }

/* Product Tabs */
.product-tabs-section {
    background: #fff;
    padding: 20px 0;
    margin-top: 20px;
}

.product-tabs-container {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.tabs-navigation {
    display: flex;
    border-bottom: 1px solid #e0e0e0;
}

.tab-btn {
    background: none;
    border: none;
    padding: 15px 25px;
    font-size: 16px;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    transition: all 0.2s;
    border-bottom: 2px solid transparent;
}

.tab-btn.active {
    color: #ee4d2d;
    border-bottom-color: #ee4d2d;
}

.tab-btn:hover {
    color: #ee4d2d;
}

.tab-content {
    padding: 20px;
}

.tab-panel {
    display: none;
}

.tab-panel.active {
    display: block;
}

.product-description {
    line-height: 1.6;
    color: #333;
}

/* Responsive */
@media (max-width: 768px) {
    .product-title {
        font-size: 20px;
    }
    
    .current-price {
        font-size: 28px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .main-product-image {
        height: 300px;
    }
    
    .product-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
}
</style>
@endsection
