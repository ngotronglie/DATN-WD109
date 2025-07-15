@extends('index.clientdashboard')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">Xác nhận đơn hàng</div>
                <div class="card-body">
                    <h5>Thông tin người nhận</h5>
                    <ul class="list-group mb-3" id="checkout-user-info">
                        <!-- Thông tin user sẽ render bằng JS -->
                    </ul>
                    <h5 class="mt-4">Sản phẩm trong đơn hàng</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
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
                    <div class="mt-3">
                        <strong>Tổng tiền: </strong>
                        <span id="checkout-total" style="font-size:1.2em;color:#d9534f;font-weight:600;">0đ</span>
                    </div>
                    <div class="mt-2">
                        <strong>Phí ship: </strong>
                        <span id="checkout-shipping" style="color:#ff9800;font-weight:600;">0đ</span>
                    </div>
                    <div class="mt-2" id="checkout-discount-row" style="display:none;">
                        <strong>Giảm giá: </strong>
                        <span id="checkout-discount" style="color:#28a745;font-weight:600;">0đ</span>
                    </div>
                    <div class="mt-2">
                        <strong>Thành tiền: </strong>
                        <span id="checkout-final" style="font-size:1.2em;color:#007bff;font-weight:600;">0đ</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">Thanh toán</div>
                <div class="card-body">
                    <div id="checkout-summary">
                        <!-- Thông tin tổng kết sẽ render bằng JS -->
                    </div>
                    <button class="btn btn-primary w-100 mt-3" id="confirm-order-btn">Xác nhận đặt hàng</button>
                    <button class="btn btn-danger w-100 mt-3" id="vnpay-payment-btn" style="display:none;">Thanh toán online VNPAY</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script-client')
<script>
function formatCurrency(num) {
    return num.toLocaleString('vi-VN') + 'đ';
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
        <li class="list-group-item"><strong>Địa chỉ:</strong> ${user.address}</li>
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
        <div><strong>Địa chỉ:</strong> ${user.address}</div>
        <div><strong>Phương thức:</strong> ${user.payment === 'cod' ? 'COD' : 'Chuyển khoản'}</div>
    `;
}
document.addEventListener('DOMContentLoaded', renderCheckoutPage);

document.getElementById('confirm-order-btn').onclick = async function() {
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
        // Gọi API tạo đơn, sau đó chuyển hướng sang VNPAY
        const res = await fetch('/api/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                user_id: 0,
                name: user.fullname,
                address: `${user.address}`,
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
        return;
    }
    // Gửi dữ liệu về API
    const res = await fetch('/api/checkout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({
            user_id: 0, // khách vãng lai
            name: user.fullname,
            address: `${user.address}`,
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
        alert('Đặt hàng thành công! Mã đơn: ' + data.order_code);
        localStorage.removeItem('checkout_cart');
        localStorage.removeItem('checkout_user');
        localStorage.removeItem('checkout_voucher');
        window.location.href = '/';
    } else {
        alert('Có lỗi khi đặt hàng, vui lòng thử lại!');
    }
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
            address: `${user.address}`,
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
