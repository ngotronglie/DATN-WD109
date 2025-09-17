@extends('index.clientdashboard')

@section('content')

<!-- BREADCRUMBS / HEADER -->
<div class="text-center mb-5">
    <h2 class="fw-bold text-danger" style="font-size:2.5rem;">🛒 Giỏ hàng của bạn</h2>
    <p class="text-muted">Kiểm tra sản phẩm và thông tin giao hàng trước khi đặt</p>
</div>
<!-- STEP TABS -->
<div class="checkout-steps mb-4">
    <ul class="nav justify-content-between text-center">
        <li class="flex-fill">
            <a class="step-item active" href="#shopping-cart" data-bs-toggle="tab">
                <div class="step-number">01</div>
                <div class="step-label">Shopping cart</div>
            </a>
        </li>
        <li class="flex-fill">
            <a class="step-item" href="#checkout" data-bs-toggle="tab">
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

<!-- PAGE CONTENT -->
<section id="page-content" class="page-wrapper section">
    <div class="container">
        <div class="row gx-4 gy-4">

            <!-- CART TABLE -->
            <div class="col-lg-8">
                <div class="card shadow border-0 rounded-4">
                    <!-- Header -->
                    <div class="card-header bg-gradient text-white fw-bold rounded-top-4"
                        style="background: linear-gradient(90deg, #343a40, #495057);">
                        <i class="bi bi-cart-check me-2"></i> Chi tiết giỏ hàng
                    </div>

                    <div class="card-body p-0">
                        <!-- Bảng giỏ hàng -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th class="text-start">Sản phẩm</th>
                                        <th>Màu</th>
                                        <th>Dung lượng</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Tổng</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cart-table-body">
                                    <!-- Render dữ liệu JS -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Tóm tắt giỏ hàng -->
                        <div class="cart-summary mt-3 p-3 bg-light rounded-4 shadow-sm">
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-cash-coin me-2 text-success"></i><strong>Tổng tiền:</strong></span>
                                <span id="cart-total" class="text-danger fw-semibold">0đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="bi bi-truck me-2 text-warning"></i><strong>Phí ship:</strong></span>
                                <span id="cart-shipping" class="text-warning fw-semibold">0đ</span>
                            </div>

                            <!-- Mã giảm giá -->
                            <!-- Áp mã giảm giá -->
                            <div class="voucher-box mb-3" style="max-width: 250px;">
                                <label for="voucher-input" class="form-label fw-semibold">
                                    <i class="bi bi-ticket-perforated text-danger"></i> Mã giảm giá
                                </label>
                                <div class="position-relative">
                                    <input type="text" class="form-control rounded-3 pe-5" id="voucher-input" placeholder="Nhập mã">
                                    <button type="button" id="apply-voucher-btn"
                                        class="btn btn-success btn-apply-voucher">
                                        Áp dụng
                                    </button>
                                </div>
                                <small id="voucher-message" class="text-success"></small>
                            </div>



                            <div class="d-flex justify-content-between mb-2" id="discount-row" style="display:none;">
                                <span><i class="bi bi-percent me-2 text-success"></i><strong>Giảm giá:</strong></span>
                                <span id="cart-discount" class="text-success fw-semibold">0đ</span>
                            </div>

                            <div class="d-flex justify-content-between border-top pt-2 mt-2">
                                <span><i class="bi bi-currency-exchange me-2 text-primary"></i><strong>Thành tiền:</strong></span>
                                <span id="cart-final" class="text-primary fw-bold fs-5">0đ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- ORDER INFO & PAYMENT -->
            <div class="col-lg-4">
                <div class="card shadow border-0 rounded-4">
                    <!-- Header -->
                    <div class="card-header bg-gradient text-white fw-bold rounded-top-4"
                        style="background: linear-gradient(90deg, #198754, #20c997);">
                        <i class="bi bi-truck me-2"></i> Thông tin giao hàng
                    </div>

                    <div class="card-body">
                        <form>
                            <!-- Họ và tên -->
                            <div class="mb-3">
                                <label for="fullname" class="form-label"><i class="bi bi-person"></i> Họ và tên</label>
                                <input type="text" class="form-control rounded-3" id="fullname" placeholder="Nhập họ tên" required>
                            </div>

                            <!-- SĐT -->
                            <div class="mb-3">
                                <label for="phone" class="form-label"><i class="bi bi-telephone"></i> Số điện thoại</label>
                                <input type="text" class="form-control rounded-3" id="phone" placeholder="Nhập số điện thoại" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label"><i class="bi bi-envelope"></i> Email</label>
                                <input type="email" class="form-control rounded-3" id="email" placeholder="Nhập email" required>
                            </div>

                            <!-- Địa chỉ -->
                            <div class="mb-3">
                                <label for="address_detail" class="form-label"><i class="bi bi-geo-alt"></i> Địa chỉ</label>
                                <input type="text" class="form-control rounded-3" id="address_detail" placeholder="Số nhà, đường..." required>
                            </div>

                            <!-- Phường/Xã -->
                            <div class="mb-3">
                                <label for="ward" class="form-label"><i class="bi bi-building"></i> Phường/Xã</label>
                                <select id="ward" class="form-select rounded-3" required>
                                    <option value="">-- Chọn phường/xã --</option>
                                </select>
                            </div>

                            <!-- Tỉnh/Thành phố -->
                            <div class="mb-3">
                                <label for="province" class="form-label"><i class="bi bi-map"></i> Tỉnh/Thành phố</label>
                                <select id="province" class="form-select rounded-3" required>
                                    <option value="">-- Chọn tỉnh --</option>
                                    @foreach($provinces as $province)
                                    <option value="{{ $province->ten_tinh }}" data-id="{{ $province->id }}">
                                        {{ $province->ten_tinh }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Ghi chú -->
                            <div class="mb-3">
                                <label for="note" class="form-label"><i class="bi bi-pencil-square"></i> Ghi chú</label>
                                <textarea class="form-control rounded-3" id="note" rows="2" placeholder="Ghi chú thêm (nếu có)"></textarea>
                            </div>

                            <!-- Phương thức thanh toán -->
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-credit-card"></i> Phương thức thanh toán</label>
                                <div class="p-2 bg-light rounded-3 border">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="payment" id="cod" value="cod" checked>
                                        <label class="form-check-label fw-semibold" for="cod">
                                            <i class="bi bi-cash-coin text-success"></i> Thanh toán khi nhận hàng (COD)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment" id="vnpay" value="vnpay">
                                        <label class="form-check-label fw-semibold" for="vnpay">
                                            <i class="bi bi-bank text-primary"></i> Thanh toán qua VNPAY
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Nút đặt hàng -->
                            <div class="d-grid mt-4">
                                <button class="btn btn-success btn-lg rounded-3 shadow-sm" id="go-to-checkout" type="button">
                                    <i class="bi bi-cart-check me-2"></i> Đặt hàng ngay
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
</section>
@endsection
<style>
    .btn-apply-voucher {
    position: absolute;
    top: 50%;
    right: 6px;
    transform: translateY(-50%);
    padding: 4px 10px;
    font-size: 0.8rem;
    border-radius: 6px;
    height: 28px;
    line-height: 1;
}
    .checkout-steps ul {
        margin: 0;
        padding: 0;
        list-style: none;
        display: flex;
    }

    .checkout-steps .step-item {
        display: block;
        text-decoration: none;
        color: #666;
        position: relative;
        padding: 10px;
    }

    .checkout-steps .step-number {
        width: 40px;
        height: 40px;
        line-height: 40px;
        margin: 0 auto 8px;
        border-radius: 50%;
        background: #ddd;
        font-weight: bold;
        color: #fff;
        transition: all 0.3s;
    }

    .checkout-steps .step-label {
        font-size: 14px;
        font-weight: 600;
    }

    .checkout-steps .step-item.active .step-number {
        background: #28a745;
        /* xanh lá */
    }

    .checkout-steps .step-item.active .step-label {
        color: #28a745;
    }

    .checkout-steps .step-item::after {
        content: "";
        position: absolute;
        top: 20px;
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
</style>
<!-- End page content -->


@section('script-client')
<script>
    const provinceSelect = document.getElementById('province');
    const wardSelect = document.getElementById('ward');

    provinceSelect?.addEventListener('change', function() {
        const provinceId = this.options[this.selectedIndex]?.getAttribute('data-id');
        if (!provinceId) return;
        fetch(`/address/wards/${provinceId}`)
            .then(res => res.json())
            .then(data => {
                wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
                data.forEach(function(ward) {
                    wardSelect.innerHTML += `<option value="${ward.ten_phuong_xa}">${ward.ten_phuong_xa}</option>`;
                });
            });
    });


    let cartData = [];

    function formatCurrency(num) {
        const number = Math.floor(Number(num)) || 0;
        return number.toLocaleString('vi-VN') + 'đ';
    }

    function getCartTotal(cart) {
        return cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    }

    function updateCartSummary() {
        const total = getCartTotal(cartData);
        let shipping = 0;
        if (total > 2000000) {
            shipping = 0;
            document.getElementById('cart-shipping').innerText = 'Miễn phí';
        } else if (total > 0) {
            shipping = 50000;
            document.getElementById('cart-shipping').innerText = formatCurrency(shipping);
        } else {
            document.getElementById('cart-shipping').innerText = '0đ';
        }
        document.getElementById('cart-total').innerText = formatCurrency(total);
        // Nếu có voucher hợp lệ thì tính lại
        let discountAmount = 0;
        if (window.currentVoucher) {
            const {
                discount,
                min_money,
                max_money
            } = window.currentVoucher;
            discountAmount = Math.floor(total * (discount / 100));
            if (min_money && discountAmount < min_money) discountAmount = min_money;
            if (max_money && discountAmount > max_money) discountAmount = max_money;
            document.getElementById('cart-discount').innerText = '-' + formatCurrency(discountAmount);
            document.getElementById('discount-row').style.display = '';
            document.getElementById('cart-final').innerText = formatCurrency(total - discountAmount + shipping);
        } else {
            document.getElementById('discount-row').style.display = 'none';
            document.getElementById('cart-final').innerText = formatCurrency(total + shipping);
        }
    }

    function renderCartTable(cart) {
        const tbody = document.getElementById('cart-table-body');
        tbody.innerHTML = '';
        cart.forEach((item, idx) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
            <td>
                <img src="${item.image}" alt="${item.name}" style="width:40px;height:40px;object-fit:cover;margin-right:8px;">
                ${item.name}
            </td>
            <td>${item.color}</td>
            <td>${item.capacity}</td>
            <td>${formatCurrency(item.price)}</td>
            <td>
                <button class="btn btn-sm btn-light qty-btn-cart" onclick="decreaseQty(${idx})">-</button>
                <span class="cart-qty-value" id="cart-qty-${idx}">${item.quantity}</span>
                <button class="btn btn-sm btn-light qty-btn-cart" onclick="increaseQty(${idx})">+</button>
            </td>
            <td>${formatCurrency(item.price * item.quantity)}</td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="removeCartItem(${idx})">Xóa</button>
            </td>
        `;
            tbody.appendChild(tr);
        });
        updateCartSummary();
    }

    function removeCartItem(index) {
        const item = cartData[index];
        fetch('/api/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    variant_id: item.variant_id
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    cartData.splice(index, 1);
                    renderCartTable(cartData);
                } else {
                    alert(res.message || 'Lỗi xóa sản phẩm');
                }
            });
    }

    function increaseQty(index) {
        const item = cartData[index];
        const newQty = item.quantity + 1;
        fetch('/api/cart/update-qty', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    variant_id: item.variant_id,
                    quantity: newQty
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    cartData[index].quantity = newQty;
                    renderCartTable(cartData);
                } else {
                    alert(res.message || 'Lỗi cập nhật số lượng');
                }
            });
    }

    function decreaseQty(index) {
        const item = cartData[index];
        if (item.quantity > 1) {
            const newQty = item.quantity - 1;
            fetch('/api/cart/update-qty', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        variant_id: item.variant_id,
                        quantity: newQty
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        cartData[index].quantity = newQty;
                        renderCartTable(cartData);
                    } else {
                        alert(res.message || 'Lỗi cập nhật số lượng');
                    }
                });
        }
    }

    // API giả lập kiểm tra voucher
    async function checkVoucher(code) {
        try {
            const res = await fetch(`/api/voucher?code=${encodeURIComponent(code)}`);
            return await res.json();
        } catch (e) {
            return {
                success: false,
                message: 'Không thể kiểm tra mã giảm giá.'
            };
        }
    }

    document.getElementById('apply-voucher-btn').onclick = async function() {
        const code = document.getElementById('voucher-input').value.trim();
        const msg = document.getElementById('voucher-message');
        const box = document.getElementById('voucher-box');
        if (!code) {
            msg.innerText = 'Vui lòng nhập mã giảm giá.';
            box.style.border = '';
            window.currentVoucher = null;
            updateCartSummary();
            return;
        }
        msg.innerText = 'Đang kiểm tra...';
        const res = await checkVoucher(code);
        if (res.success) {
            msg.innerText = `Áp dụng thành công: Giảm ${res.discount}% (tối thiểu ${formatCurrency(res.min_money)}, tối đa ${formatCurrency(res.max_money)})`;
            box.style.border = '2px solid #28a745';
            window.currentVoucher = res;
            updateCartSummary();
        } else {
            msg.innerText = res.message || 'Mã không hợp lệ.';
            box.style.border = '2px solid #dc3545';
            window.currentVoucher = null;
            updateCartSummary();
        }
    };

    document.addEventListener('DOMContentLoaded', async function() {
        try {
            const res = await fetch('/api/cart');
            const data = await res.json();
            if (data.success) {
                cartData = data.data;
            } else {
                cartData = [];
            }
        } catch (e) {
            cartData = [];
        }
        renderCartTable(cartData);

        // Lấy thông tin user và fill vào form nếu có
        try {
            const userRes = await fetch('/api/user', {
                credentials: 'same-origin'
            });
            const userData = await userRes.json();
            if (userData.success && userData.user) {
                document.getElementById('fullname').value = userData.user.name || '';
                document.getElementById('phone').value = userData.user.phone || '';
                document.getElementById('fullname').value = userData.user.name || '';
                document.getElementById('phone').value = userData.user.phone || '';
                document.getElementById('email').value = userData.user.email || '';
                document.getElementById('address_detail').value = userData.user.street || '';
                document.getElementById('ward').value = userData.user.ward || '';
                document.getElementById('district').value = userData.user.district || '';
                document.getElementById('province').value = userData.user.city || '';
                document.getElementById('email').value = userData.user.email || '';
            }
        } catch (e) {}
    });

    document.getElementById('go-to-checkout').onclick = function(e) {
        if (e) e.preventDefault();
        // viết require số điện thoại ở đây
        const phone = document.getElementById('phone').value.trim();
        // Kiểm tra rỗng hoặc không đúng định dạng (10 số, bắt đầu bằng 0)
        if (!phone) {
            alert('Vui lòng nhập số điện thoại!');
            return;
        }
        if (!/^0\d{9}$/.test(phone)) {
            alert('Số điện thoại phải gồm 10 số và bắt đầu bằng 0!');
            return;
        }
        // Lưu cartData, user info và voucher vào localStorage
        const userInfo = {
            fullname: document.getElementById('fullname').value,
            phone: document.getElementById('phone').value,
            email: document.getElementById('email').value,
            street: document.getElementById('address_detail').value,
            ward: document.getElementById('ward')?.value || '',
            district: document.getElementById('district')?.value || '',
            city: document.getElementById('province')?.value || '',
            note: document.getElementById('note').value,
            payment: document.querySelector('input[name="payment"]:checked')?.value || 'cod'
        };


        localStorage.setItem('checkout_cart', JSON.stringify(cartData));
        localStorage.setItem('checkout_user', JSON.stringify(userInfo));
        const voucherCode = document.getElementById('voucher-input')?.value.trim() || '';
        if (window.currentVoucher) {
            localStorage.setItem('checkout_voucher', JSON.stringify(window.currentVoucher));
        } else {
            localStorage.removeItem('checkout_voucher');
        }
        if (voucherCode) {
            localStorage.setItem('checkout_voucher_code', voucherCode);
        } else {
            localStorage.removeItem('checkout_voucher_code');
        }
        window.location.href = '/checkout';
    };
</script>

@endsection

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush