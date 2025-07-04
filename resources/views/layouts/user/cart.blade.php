@extends('index.clientdashboard')

@section('content')

<!-- BREADCRUMBS SETCTION START -->
<!-- BREADCRUMBS SETCTION START -->
<div class="breadcrumbs section plr-200 mb-80" style="margin-top: 15px;">
    <div class="breadcrumbs overlay-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumbs-inner">
                        <ul class="d-flex align-items-center gap-2">
                            <li><a href="{{ route('home') }}">Trang chủ</a></li>
                            <li><span class="text-muted">/</span></li>
                            <li>Giỏ hàng</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Tiêu đề Giỏ hàng ở giữa -->
<div class="text-center mb-4">
    <h2 class="fw-bold" style="font-size: 2.2rem; color: #dc3545;">🛒 Giỏ hàng</h2>
</div>
<!-- BREADCRUMBS SETCTION END -->

<!-- Start page content -->
<section id="page-content" class="page-wrapper section">
    <div class="container">
        <div class="row">
            <!-- Cart Table -->
            <div class="col-lg-9 mb-4">
                <div class="card">
                    <div class="card-header bg-dark text-white">Giỏ hàng</div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>màu</th>
                                    <th>dung lượng</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Tổng</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cart-table-body">
                                <!-- Dữ liệu sẽ được render bằng JS -->
                            </tbody>
                        </table>
                        <!-- Tổng tiền và voucher -->
                        <div class="cart-summary mt-3 p-3 border rounded">
                            <div class="d-flex align-items-center mb-2">
                                <strong class="me-2">Tổng tiền:</strong>
                                <span id="cart-total" style="font-size:1.2em;color:#d9534f;font-weight:600;">0đ</span>
                            </div>
                            <div class="d-flex align-items-center mt-2">
                                <strong class="me-2">Phí ship:</strong>
                                <span id="cart-shipping" style="color:#ff9800;font-weight:600;">0đ</span>
                            </div>
                            <div class="voucher-box p-2 rounded" id="voucher-box" style="background:#f8f9fa;">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" id="voucher-input" placeholder="Nhập mã giảm giá">
                                    <button class="btn btn-success" type="button" id="apply-voucher-btn">Áp dụng</button>
                                </div>
                                <div id="voucher-message" class="small"></div>
                            </div>
                            <div class="d-flex align-items-center mt-2" id="discount-row" style="display:none;">
                                <strong class="me-2">Giảm giá:</strong>
                                <span id="cart-discount" style="color:#28a745;font-weight:600;">0đ</span>
                            </div>
                            <div class="d-flex align-items-center mt-2">
                                <strong class="me-2">Thành tiền:</strong>
                                <span id="cart-final" style="font-size:1.2em;color:#007bff;font-weight:600;">0đ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Order Info & Payment -->
            <div class="col-lg-3 mb-4">
                <div class="card">
                    <div class="card-header bg-dark text-white">Thông tin đơn hàng</div>
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="fullname" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email nhận đơn hàng</label>
                                <input type="text" class="form-control" id="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ nhận hàng</label>
                                <input type="text" class="form-control" id="address" required>
                            </div>
                            <div class="mb-3">
                                <label for="note" class="form-label">Ghi chú</label>
                                <input type="text" class="form-control" id="note">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phương thức thanh toán</label>
                                <div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment" id="cod" value="cod" checked>
                                        <label class="form-check-label" for="cod">Thanh toán khi nhận hàng (COD)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment" id="bank" value="bank">
                                        <label class="form-check-label" for="bank">Chuyển khoản ngân hàng</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment" id="vnpay" value="vnpay">
                                        <label class="form-check-label" for="vnpay">Thanh toán online VNPAY</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Thêm nút Đặt hàng ở dưới bảng giỏ hàng -->
                            <div class="text-end mt-3">
                                <button class="btn btn-lg btn-success" id="go-to-checkout" type="button">Đặt hàng</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End page content -->

@endsection

@section('script-client')
<script>
    let cartData = [];

    function formatCurrency(num) {
        return num.toLocaleString('vi-VN') + 'đ';
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
                document.getElementById('address').value = userData.user.address || '';
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
            address: document.getElementById('address').value,
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
<style>
      /* Làm chữ trong các input rõ ràng, to, đậm */
    #phone,
    #voucher-input,
    #cart-total,
    #cart-shipping,
    #cart-final,
    #cart-discount {
        font-size: 1.1rem !important;
        font-weight: 600;
        font-style: normal;
        font-family: 'Arial', sans-serif;
    }
    .qty-btn-cart {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: none;
        background: #eee;
        color: #333;
        font-size: 1.1em;
        font-weight: bold;
        margin: 0 2px;
        padding: 0;
        transition: background 0.2s;
    }

    .qty-btn-cart:hover {
        background: #ccc;
    }

    .cart-qty-value {
        display: inline-block;
        min-width: 24px;
        text-align: center;
        font-weight: 500;
    }

    .cart-summary {
        background: #fff;
    }

    .voucher-box {
        border: 1.5px dashed #ccc;
        transition: border 0.2s;
    }
</style>
@endsection

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush