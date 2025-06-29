


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
                                                            <a href="#" class="add-to-favorite" data-product-id="{{ $product->product_id }}" title="Thêm vào yêu thích" onclick="addToFavorite(event, {{ $product->product_id }})">
                                                                <i class="zmdi zmdi-favorite"></i>
                                                            </a>
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

        <style>
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
        // Hàm thêm vào yêu thích
        function addToFavorite(event, productId) {
            event.preventDefault();
            
            // Kiểm tra xem user đã đăng nhập chưa
            @auth
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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Thay đổi màu icon thành đỏ
                        const icon = event.target.querySelector('i');
                        if (icon) {
                            icon.style.color = '#e74c3c';
                        }
                        
                        // Hiển thị thông báo thành công
                        showModal('Đã thêm sản phẩm vào danh sách yêu thích!', 'success');
                    } else {
                        showModal(data.message || 'Có lỗi xảy ra!', 'error');
                    }
                })
                .catch(error => {
                    showModal('Có lỗi xảy ra khi thêm vào yêu thích!', 'error');
                });
            @else
                // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
                showModal('Vui lòng đăng nhập để thêm sản phẩm vào yêu thích!', 'warning');
                setTimeout(function() {
                    window.location.href = '{{ route("auth.login") }}';
                }, 2000);
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
            
            // Tự động đóng sau 5 giây
            setTimeout(function() {
                closeModal();
            }, 5000);
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

