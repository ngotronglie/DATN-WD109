@extends('index.clientdashboard')

@section('content')

        <!-- BREADCRUMBS SETCTION START -->
        <div class="breadcrumbs-section plr-200 mb-80 section">
            <div class="breadcrumbs overlay-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="breadcrumbs-inner">
                                <h1 class="breadcrumbs-title">Shopping Cart</h1>
                                <ul class="breadcrumb-list">
                                    <li><a href="index.html">Home</a></li>
                                    <li>Shopping Cart</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                        <label for="address" class="form-label">Địa chỉ nhận hàng</label>
                                        <input type="text" class="form-control" id="address" required>
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
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Đặt hàng</button>
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
const cartData = [
    {
        name: "iPhone 15 Pro Max",
        color: "Đen",
        capacity: "256GB",
        price: 32990000,
        quantity: 2,
        image: "https://picsum.photos/200"
    },
    {
        name: "Samsung S24 Ultra",
        color: "Tím",
        capacity: "512GB",
        price: 28990000,
        quantity: 1,
        image: "https://picsum.photos/200"
    }
];

function formatCurrency(num) {
    return num.toLocaleString('vi-VN') + 'đ';
}

function getCartTotal(cart) {
    return cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
}

function updateCartSummary() {
    const total = getCartTotal(cartData);
    document.getElementById('cart-total').innerText = formatCurrency(total);
    // Nếu có voucher hợp lệ thì tính lại
    if (window.currentVoucher) {
        const { discount, min_money, max_money } = window.currentVoucher;
        let discountAmount = Math.floor(total * (discount / 100));
        if (min_money && discountAmount < min_money) discountAmount = min_money;
        if (max_money && discountAmount > max_money) discountAmount = max_money;
        document.getElementById('cart-discount').innerText = '-' + formatCurrency(discountAmount);
        document.getElementById('discount-row').style.display = '';
        document.getElementById('cart-final').innerText = formatCurrency(total - discountAmount);
    } else {
        document.getElementById('discount-row').style.display = 'none';
        document.getElementById('cart-final').innerText = formatCurrency(total);
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
    cartData.splice(index, 1);
    renderCartTable(cartData);
}

function increaseQty(index) {
    cartData[index].quantity++;
    renderCartTable(cartData);
}

function decreaseQty(index) {
    if (cartData[index].quantity > 1) {
        cartData[index].quantity--;
        renderCartTable(cartData);
    }
}

// API giả lập kiểm tra voucher
async function checkVoucher(code) {
    try {
        const res = await fetch(`/api/voucher?code=${encodeURIComponent(code)}`);
        return await res.json();
    } catch (e) {
        return {success: false, message: 'Không thể kiểm tra mã giảm giá.'};
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

document.addEventListener('DOMContentLoaded', function() {
    renderCartTable(cartData);
});
</script>
<style>
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
.cart-summary { background: #fff; }
.voucher-box { border: 1.5px dashed #ccc; transition: border 0.2s; }
</style>
@endsection