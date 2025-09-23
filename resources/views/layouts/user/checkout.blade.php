@extends('index.clientdashboard')

@section('content')
<div class="text-center mb-5">
    <h2 class="fw-bold text-danger" style="font-size:2.5rem;">💳Thanh toán</h2>
</div>
<div class="checkout-steps mb-4">
    <ul class="nav justify-content-between text-center">
        <li class="flex-fill">
            <a class="step-item" href="#shopping-cart" data-bs-toggle="tab">
                <div class="step-number">01</div>
                <div class="step-label">Shopping cart</div>
            </a>
        </li>
        <li class="flex-fill">
            <a class="step-item active" href="#checkout" data-bs-toggle="tab">
                <div class="step-number">02</div>
                <div class="step-label">Checkout</div>
            </a>
        </li>
        <li class="flex-fill">
            <a class="step-item" href="#order-complete" data-bs-toggle="tab">
                <div class="step-number">03</div>
                <div class="step-label">Order complete</div>
            </a>
        </li>

    </ul>
</div>
<div class="container py-5">
    <div class="row">
        <!-- Cột trái -->
        <!-- Cột trái -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-gradient text-white rounded-top-4 fw-bold"
                    style="background: linear-gradient(90deg, #0d6efd, #6610f2);">
                    <i class="bi bi-receipt me-2"></i> Xác nhận đơn hàng
                </div>
                <div class="card-body">

                    <!-- Thông tin người nhận -->
                    <h5 class="mb-3 fw-semibold text-dark">
                        <i class="bi bi-person-circle me-2 text-primary"></i> Thông tin người nhận
                    </h5>
                    <ul class="list-group mb-4 shadow-sm rounded-3 overflow-hidden border-0" id="checkout-user-info">
                        <!-- Thông tin user render bằng JS -->
                    </ul>

                    <!-- Sản phẩm trong đơn hàng -->
                    <h5 class="mb-3 fw-semibold text-dark">
                        <i class="bi bi-bag-check me-2 text-success"></i> Sản phẩm trong đơn hàng
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>Sản phẩm</th>
                                    <th>Màu</th>
                                    <th>Dung lượng</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Tổng</th>
                                </tr>
                            </thead>
                            <tbody id="checkout-cart-body">
                                <!-- Render bằng JS -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Tổng kết -->
                    <div class="mt-4 p-3 bg-light rounded-3 shadow-sm">
                        <div class="d-flex justify-content-between mb-2">
                            <span><strong>Tổng tiền:</strong></span>
                            <span id="checkout-total" class="fw-bold text-danger fs-6">0đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><strong>Phí ship:</strong></span>
                            <span id="checkout-shipping" class="fw-bold text-warning fs-6">0đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2" id="checkout-discount-row" style="display:none;">
                            <span><strong>Giảm giá:</strong></span>
                            <span id="checkout-discount" class="fw-bold text-success fs-6">0đ</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2 mt-2">
                            <span><strong>Thành tiền:</strong></span>
                            <span id="checkout-final" class="fw-bold text-primary fs-5">0đ</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- Cột phải -->
<!-- Cột phải -->
<div class="col-lg-4 mb-4">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-gradient text-white rounded-top-4 fw-bold"
             style="background: linear-gradient(90deg, #20c997, #0dcaf0);">
            <i class="bi bi-credit-card-2-front me-2"></i> Thanh toán
        </div>
        <div class="card-body">
            
            <!-- Tóm tắt đơn hàng -->
            <div id="checkout-summary" class="mb-4 p-3 bg-light rounded-3 shadow-sm">
                <!-- Render bằng JS -->
            </div>

            <!-- Nút hành động -->
            <div class="d-grid gap-2">
                <button class="btn btn-success btn-lg rounded-3 fw-semibold" id="confirm-order-btn">
                    <i class="bi bi-check2-circle me-2"></i> Xác nhận đặt hàng
                </button>
                <button class="btn btn-danger btn-lg rounded-3 fw-semibold" id="vnpay-payment-btn" style="display:none;">
                    <i class="bi bi-bank me-2"></i> Thanh toán online VNPAY
                </button>
            </div>
        </div>
    </div>
</div>

    </div>
</div>
<!-- Modal: Loading khi đặt hàng COD -->
<div class="modal fade" id="placingOrderModal" tabindex="-1" aria-labelledby="placingOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-body p-4 text-center">
        <div class="spinner-border text-primary mb-3" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <h5 class="mb-1" id="placingOrderModalLabel">Đang xử lý đơn hàng</h5>
        <p class="modal-subtitle mb-3">Vui lòng đợi trong giây lát...</p>
        <div class="progress progress-soft mx-auto" style="max-width: 260px;">
          <div class="progress-bar progress-bar-animated progress-bar-indeterminate" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
    </div>
  </div>
  </div>
<!-- Modal: Thông báo đặt hàng thành công -->
<div class="modal fade" id="orderSuccessModal" tabindex="-1" aria-labelledby="orderSuccessModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="orderSuccessModalLabel">Đặt hàng thành công</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div class="text-success mb-2" style="font-size:48px; line-height:1;">
          <i class="bi bi-check-circle-fill"></i>
        </div>
        <p class="mb-1">Cảm ơn bạn đã đặt hàng!</p>
        <p class="mb-0">Mã đơn hàng của bạn: <strong id="orderSuccessCode">#000000</strong></p>
      </div>
      <div class="modal-footer border-0 justify-content-center gap-2">
        <button type="button" class="btn btn-outline-primary" id="orderSuccessViewBtn">Xem đơn hàng</button>
        <button type="button" class="btn btn-primary" id="orderSuccessConfirmBtn">Về trang chủ</button>
      </div>
    </div>
  </div>
</div>
@endsection

<style>
    .checkout-steps ul {
        margin: 0;
        padding: 0;
        list-style: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .checkout-steps .step-item {
        display: block;
        text-decoration: none;
        color: #666;
        position: relative;
        padding: 10px;
        transition: 0.3s ease;
    }

    .checkout-steps .step-number {
        width: 45px;
        height: 45px;
        line-height: 45px;
        margin: 0 auto 8px;
        border-radius: 50%;
        background: #e0e0e0;
        font-weight: bold;
        color: #fff;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .checkout-steps .step-label {
        font-size: 14px;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    /* Active */
    .checkout-steps .step-item.active .step-number {
        background: #28a745;
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
    }

    .checkout-steps .step-item.active .step-label {
        color: #28a745;
    }

    /* Hover */
    .checkout-steps .step-item:hover .step-number {
        background: #17a2b8;
    }

    .checkout-steps .step-item:hover .step-label {
        color: #17a2b8;
    }

    /* Thanh nối */
    .checkout-steps .step-item::after {
        content: "";
        position: absolute;
        top: 22px;
        right: -50%;
        width: 100%;
        height: 3px;
        background: #ddd;
        z-index: -1;
    }

    .checkout-steps li:last-child .step-item::after {
        display: none;
    }

    .checkout-steps .step-item.active::after {
        background: #28a745;
    }

    /* Modal polish */
    #placingOrderModal .modal-content,
    #orderSuccessModal .modal-content {
        border-radius: 1rem;
    }
    #orderSuccessModal .modal-header {
        background: linear-gradient(90deg, #22c55e, #16a34a);
        color: #fff;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }
    #orderSuccessModal .modal-title {
        font-weight: 700;
    }
    #orderSuccessModal .modal-body .bi-check-circle-fill {
        filter: drop-shadow(0 4px 10px rgba(34,197,94,.35));
    }
    .modal-subtitle {
        color: #6b7280;
        font-size: .95rem;
    }

    /* Soft progress (indeterminate) */
    .progress.progress-soft {
        height: 8px;
        background: #eef2ff;
        border-radius: 999px;
        overflow: hidden;
    }
    .progress-bar-indeterminate {
        position: relative;
        width: 30%;
        background: linear-gradient(90deg, #3b82f6, #60a5fa);
        border-radius: 999px;
        animation: indeterminate 1.2s infinite ease-in-out;
    }
    @keyframes indeterminate {
        0% { left: -30%; }
        50% { left: 50%; }
        100% { left: 110%; }
    }

    /* Success modal buttons */
    #orderSuccessModal .btn-primary {
        background: linear-gradient(90deg, #2563eb, #1d4ed8);
        border: none;
        box-shadow: 0 6px 18px rgba(29,78,216,.25);
    }
    #orderSuccessModal .btn-outline-primary {
        border-color: #2563eb;
        color: #2563eb;
    }
    #orderSuccessModal .btn-outline-primary:hover {
        background: #2563eb;
        color: #fff;
    }
</style>
@section('script-client')
<script>
    // Modal hiển thị loading khi đặt hàng (COD)
    let placingOrderModalInstance = null;
    let orderSuccessModalInstance = null;
    let lastCreatedOrderId = null;
    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('placingOrderModal');
        if (modalEl && window.bootstrap && bootstrap.Modal) {
            placingOrderModalInstance = new bootstrap.Modal(modalEl, {backdrop: 'static', keyboard: false});
        }
        const successEl = document.getElementById('orderSuccessModal');
        if (successEl && window.bootstrap && bootstrap.Modal) {
            orderSuccessModalInstance = new bootstrap.Modal(successEl, {backdrop: 'static', keyboard: false});
        }
        const successBtn = document.getElementById('orderSuccessConfirmBtn');
        successBtn?.addEventListener('click', function(){ window.location.href = '/'; });
        const viewBtn = document.getElementById('orderSuccessViewBtn');
        viewBtn?.addEventListener('click', function(){ window.location.href = '/account/order'; });
    });

    function formatCurrency(num) {
        const number = Math.floor(Number(num)) || 0;
        return number.toLocaleString('vi-VN') + 'đ';
    }

    function getCartTotal(cart) {
        return cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    }

    function renderCheckoutPage() {
        const cart = JSON.parse(localStorage.getItem('checkout_cart') || '[]');
        const user = JSON.parse(localStorage.getItem('checkout_user') || 'null');
        const voucher = JSON.parse(localStorage.getItem('checkout_voucher') || 'null');
        const voucherCode = localStorage.getItem('checkout_voucher_code') || '';
        if (!cart.length || !user) {
            alert('Không có dữ liệu đơn hàng. Vui lòng quay lại giỏ hàng!');
            window.location.href = '/cart';
            return;
        }
        // Render user info
        let voucherInfo = '';
        if (voucherCode) {
            voucherInfo = `<li class='list-group-item'><strong>Voucher:</strong> ${voucherCode}</li>`;
        }
        const userInfo = `
        <li class="list-group-item"><strong>Họ tên:</strong> ${user.fullname}</li>
        <li class="list-group-item"><strong>SĐT:</strong> ${user.phone}</li>
<li class="list-group-item"><strong>Địa chỉ:</strong> ${[user.street, user.ward, user.district, user.city].filter(Boolean).join(', ')}</li>
        <li class="list-group-item"><strong>Ghi chú:</strong> ${user.note || ''}</li>
        <li class="list-group-item"><strong>Thanh toán:</strong> ${user.payment === 'cod' ? 'COD' : 'Chuyển khoản'}</li>
        ${voucherInfo}
    `;
        document.getElementById('checkout-user-info').innerHTML = userInfo;
        // Render cart
        let total = 0;
        let cartHtml = '';
        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            cartHtml += `
            <tr>
                <td><img src="${item.image}" alt="${item.name}" style="width:40px;height:40px;object-fit:cover;margin-right:8px;">${item.name}</td>
                <td>${item.color}</td>
                <td>${item.capacity}</td>
                <td>${formatCurrency(item.price)}</td>
                <td>${item.quantity}</td>
                <td>${formatCurrency(itemTotal)}</td>
            </tr>
        `;
        });
        document.getElementById('checkout-cart-body').innerHTML = cartHtml;
        document.getElementById('checkout-total').innerText = formatCurrency(total);
        // Tính phí ship
        let shipping = 0;
        if (total > 2000000) {
            shipping = 0;
            document.getElementById('checkout-shipping').innerText = 'Miễn phí';
        } else if (total > 0) {
            shipping = 50000;
            document.getElementById('checkout-shipping').innerText = formatCurrency(shipping);
        } else {
            document.getElementById('checkout-shipping').innerText = '0đ';
        }
        // Tính giảm giá nếu có
        let discountAmount = 0;
        let voucher_id = null;
        if (voucher) {
            discountAmount = Math.floor(total * (voucher.discount / 100));
            if (voucher.min_money && discountAmount < voucher.min_money) discountAmount = voucher.min_money;
            if (voucher.max_money && discountAmount > voucher.max_money) discountAmount = voucher.max_money;
            voucher_id = voucher.id || null;
            document.getElementById('checkout-discount').innerText = '-' + formatCurrency(discountAmount);
            document.getElementById('checkout-discount-row').style.display = '';
        } else {
            document.getElementById('checkout-discount-row').style.display = 'none';
        }
        // Tổng cuối cùng
        document.getElementById('checkout-final').innerText = formatCurrency(total - discountAmount + shipping);
        // Render summary
        document.getElementById('checkout-summary').innerHTML = `
        <div><strong>Tổng tiền:</strong> <span style="color:#d9534f">${formatCurrency(total)}</span></div>
        <div><strong>Phí ship:</strong> <span style="color:#ff9800">${shipping === 0 ? 'Miễn phí' : formatCurrency(shipping)}</span></div>
        ${discountAmount > 0 ? `<div><strong>Giảm giá:</strong> <span style="color:#28a745">-${formatCurrency(discountAmount)}</span></div>` : ''}
        <div><strong>Thành tiền:</strong> <span style="color:#007bff">${formatCurrency(total - discountAmount + shipping)}</span></div>
        <div><strong>Người nhận:</strong> ${user.fullname}</div>
<div><strong>Địa chỉ:</strong> ${[user.street, user.ward, user.district, user.city].filter(Boolean).join(', ')}</div>
        <div><strong>Phương thức:</strong> ${user.payment === 'cod' ? 'COD' : 'Chuyển khoản'}</div>
    `;
    }
    document.addEventListener('DOMContentLoaded', renderCheckoutPage);

    document.getElementById('confirm-order-btn').onclick = async function() {
        // Chỉ dùng cho COD (nút này ẩn khi chọn VNPAY)
        const confirmBtn = document.getElementById('confirm-order-btn');
        try { confirmBtn.disabled = true; } catch (e) {}
        try { placingOrderModalInstance && placingOrderModalInstance.show(); } catch (e) {}
        const cart = JSON.parse(localStorage.getItem('checkout_cart') || '[]');
        const user = JSON.parse(localStorage.getItem('checkout_user') || 'null');
        const voucher = JSON.parse(localStorage.getItem('checkout_voucher') || 'null');
        const voucherCode = localStorage.getItem('checkout_voucher_code') || '';
        if (!cart.length || !user) {
            alert('Không có dữ liệu đơn hàng. Vui lòng quay lại giỏ hàng!');
            window.location.href = '/cart';
            return;
        }
        // Tính tổng tiền, phí ship, giảm giá (nếu có)
        let total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        let shipping = total > 2000000 ? 0 : (total > 0 ? 50000 : 0);
        let discountAmount = 0;
        let voucher_id = null;
        if (voucher) {
            discountAmount = Math.floor(total * (voucher.discount / 100));
            if (voucher.min_money && discountAmount < voucher.min_money) discountAmount = voucher.min_money;
            if (voucher.max_money && discountAmount > voucher.max_money) discountAmount = voucher.max_money;
            voucher_id = voucher.id || null;
        }
        console.log('voucher_id:', voucher_id);
        const finalTotal = total - discountAmount + shipping;
        if (user.payment === 'vnpay') {
            // Gọi API tạo đơn, sau đó chuyển hướng sang VNPAY\
            const fullAddress = [user.street, user.ward, user.district, user.city].filter(Boolean).join(', ');
            const res = await fetch('/api/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    user_id: 0,
                    name: user.fullname,
                    address: fullAddress,
                    email: user.email || '',
                    phone: user.phone,
                    note: user.note || '',
                    total_amount: finalTotal,
                    price: total,
                    status: 'chờ xử lí',
                    payment_method: 'vnpay',
                    voucher_id: voucher_id,
                    status_method: 'chưa thanh toán',
                    items: cart.map(item => ({
                        variant_id: item.variant_id,
                        quantity: item.quantity,
                        price: item.price
                    }))
                })
            });
            const data = await res.json();
            if (data.success) {
                // Đặt badge giỏ hàng về 0 trước khi chuyển hướng sang VNPAY
                try {
                    document.getElementById('cart-count-badge').innerText = '0';
                } catch (e) {}
                // Xóa dữ liệu tạm trên client
                try {
                    localStorage.removeItem('checkout_cart');
                    localStorage.removeItem('checkout_user');
                    localStorage.removeItem('checkout_voucher');
                    localStorage.removeItem('checkout_voucher_code');
                } catch (e) {}
                window.location.href = '/vnpay/payment?order_id=' + data.order_id;
            } else {
                alert('Có lỗi khi tạo đơn hàng, vui lòng thử lại!');
            }
            return;
        }
        // Gửi dữ liệu về API
        const fullAddress = [user.street, user.ward, user.district, user.city].filter(Boolean).join(', ');
        const res = await fetch('/api/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                user_id: 0, // khách vãng lai
                name: user.fullname,
                address: fullAddress,
                email: user.email || '',
                phone: user.phone,
                note: user.note || '',
                total_amount: finalTotal,
                price: total,
                status: 'chờ xử lí',
                payment_method: user.payment || 'COD',
                voucher_id: voucher_id,
                voucher_code: voucherCode,
                status_method: 'chưa thanh toán',
                items: cart.map(item => ({
                    variant_id: item.variant_id,
                    quantity: item.quantity,
                    price: item.price
                }))
            })
        });
        const data = await res.json();
        if (data.success) {
            // Đặt badge giỏ hàng về 0
            try {
                document.getElementById('cart-count-badge').innerText = '0';
            } catch (e) {}
            // Xóa dữ liệu tạm trên client
            try {
                localStorage.removeItem('checkout_cart');
                localStorage.removeItem('checkout_user');
                localStorage.removeItem('checkout_voucher');
                localStorage.removeItem('checkout_voucher_code');
            } catch (e) {}
            // Ẩn loading và hiển thị modal thành công với mã đơn
            try { placingOrderModalInstance && placingOrderModalInstance.hide(); } catch (e) {}
            try { document.getElementById('orderSuccessCode').innerText = '#' + String(data.order_code || '').toUpperCase(); } catch (e) {}
            try { lastCreatedOrderId = data.order_id || null; } catch (e) {}
            try { orderSuccessModalInstance && orderSuccessModalInstance.show(); } catch (e) { window.location.href = '/'; }
        } else {
            alert('Có lỗi khi đặt hàng, vui lòng thử lại!');
            try { placingOrderModalInstance && placingOrderModalInstance.hide(); } catch (e) {}
            try { confirmBtn.disabled = false; } catch (e) {}
            return;
        }
        try { confirmBtn.disabled = false; } catch (e) {}
    };

    // Hiển thị nút VNPAY nếu chọn phương thức thanh toán online
    function updateVnpayButton() {
        const user = JSON.parse(localStorage.getItem('checkout_user') || 'null');
        if (user && user.payment === 'vnpay') {
            document.getElementById('vnpay-payment-btn').style.display = '';
            document.getElementById('confirm-order-btn').style.display = 'none';
        } else {
            document.getElementById('vnpay-payment-btn').style.display = 'none';
            document.getElementById('confirm-order-btn').style.display = '';
        }
    }
    document.addEventListener('DOMContentLoaded', updateVnpayButton);

    document.getElementById('vnpay-payment-btn').onclick = async function() {
        const cart = JSON.parse(localStorage.getItem('checkout_cart') || '[]');
        const user = JSON.parse(localStorage.getItem('checkout_user') || 'null');
        const voucher = JSON.parse(localStorage.getItem('checkout_voucher') || 'null');
        if (!cart.length || !user) {
            alert('Không có dữ liệu đơn hàng. Vui lòng quay lại giỏ hàng!');
            window.location.href = '/cart';
            return;
        }
        let total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        let shipping = total > 2000000 ? 0 : (total > 0 ? 50000 : 0);
        let discountAmount = 0;
        let voucher_id = null;
        if (voucher) {
            discountAmount = Math.floor(total * (voucher.discount / 100));
            if (voucher.min_money && discountAmount < voucher.min_money) discountAmount = voucher.min_money;
            if (voucher.max_money && discountAmount > voucher.max_money) discountAmount = voucher.max_money;
            voucher_id = voucher.id || null;
        }
        const fullAddress = [user.street, user.ward, user.district, user.city].filter(Boolean).join(', ');
        const finalTotal = total - discountAmount + shipping;
        const res = await fetch('/api/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                user_id: 0,
                name: user.fullname,
                address: fullAddress,
                email: user.email || '',
                phone: user.phone,
                note: user.note || '',
                total_amount: finalTotal,
                price: total,
                status: 'chờ xử lí',
                payment_method: 'vnpay',
                voucher_id: voucher_id,
                status_method: 'chưa thanh toán',
                items: cart.map(item => ({
                    variant_id: item.variant_id,
                    quantity: item.quantity,
                    price: item.price
                }))
            })
        });
        const data = await res.json();
        if (data.success) {
            window.location.href = '/vnpay/payment?order_id=' + data.order_id;
        } else {
            alert('Có lỗi khi tạo đơn hàng, vui lòng thử lại!');
        }
    };
</script>
@endsection