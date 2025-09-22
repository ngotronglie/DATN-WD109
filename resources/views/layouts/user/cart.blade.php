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

                            <div class="voucher-box p-2 rounded" id="voucher-box" style="background:#f8f9fa;">
                                <div class="mb-2">
                                    <label for="voucher-select" class="form-label mb-1" style="font-weight:600;">Chọn voucher</label>
                                    <select id="voucher-select" class="form-select">
                                        <option value="">-- Không áp dụng voucher --</option>
                                    </select>

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

                        <form id="order-form">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="fullname" name="fullname" required 
                                       placeholder="Nhập họ và tên đầy đủ">
                                <div class="invalid-feedback" id="fullname-error"></div>

                            </div>

                            <!-- SĐT -->
                            <div class="mb-3">

                                <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" required 
                                       placeholder="Nhập số điện thoại (10 số)">
                                <div class="invalid-feedback" id="phone-error"></div>

                            </div>

                            <!-- Email -->
                            <div class="mb-3">

                                <label for="email" class="form-label">Email nhận đơn hàng <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required 
                                       placeholder="Nhập email hợp lệ">
                                <div class="invalid-feedback" id="email-error"></div>

                            </div>

                            <!-- Địa chỉ -->
                     
                            
                            <div class="mb-3">
                                <label for="province" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                <select id="province" name="province" class="form-select" required>
                                    <option value="">-- Chọn tỉnh/thành phố --</option>
                                    @foreach($provinces as $province)
                                    <option value="{{ $province->ten_tinh }}" data-id="{{ $province->id }}">
                                        {{ $province->ten_tinh }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="province-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="district" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                <select id="district" name="district" class="form-select" required disabled>
                                    <option value="">-- Chọn quận/huyện --</option>
                                </select>
                                <div class="invalid-feedback" id="district-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="ward" class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                <select id="ward" name="ward" class="form-select" required disabled>
                                    <option value="">-- Chọn phường/xã --</option>
                                </select>
                                <div class="invalid-feedback" id="ward-error"></div>
                            </div>

                            <div class="mb-3">

                            <label for="address_detail" class="form-label">Địa chỉ (số nhà, tên đường) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="address_detail" name="address_detail" required 
                                placeholder="Nhập số nhà, tên đường">
                            <div class="invalid-feedback" id="address_detail-error"></div>
                            </div>
<!-- Ghi chú -->
                            <div class="mb-3">

                                <label for="note" class="form-label">Ghi chú</label>
                                <textarea class="form-control" id="note" name="note" rows="2" 
                                          placeholder="Ghi chú thêm cho đơn hàng (không bắt buộc)"></textarea>

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
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');

    async function ensureProvincesLoaded() {
        // If provinces are already rendered by server, skip
        try {
            const res = await fetch('/address/provinces');
            const data = await res.json();
            provinceSelect.innerHTML = '<option value="">-- Chọn tỉnh/thành phố --</option>';
            data.forEach(function(p){
                provinceSelect.innerHTML += `<option value="${p.ten_tinh}" data-id="${p.id}">${p.ten_tinh}</option>`;
            });
            return data;
        } catch (_) {
            return [];
        }
    }

    // Track requests to avoid race conditions when user changes fast
    let districtLoadSeq = 0;
    let wardLoadSeq = 0;

    // Khi chọn tỉnh/thành phố
    provinceSelect?.addEventListener('change', function() {
        const provinceId = this.options[this.selectedIndex]?.getAttribute('data-id');
        
        // Reset district và ward
        districtSelect.innerHTML = '<option value="">-- Chọn quận/huyện --</option>';
        wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
        districtSelect.disabled = true;
        wardSelect.disabled = true;
        
        if (!provinceId) return;
        
        // Load districts
        console.log('Loading districts for province:', provinceId);
        const mySeq = ++districtLoadSeq;
        fetch(`/address/districts/${provinceId}`)
            .then(res => {
                console.log('Districts response status:', res.status);
                return res.json();
            })
            .then(data => {
                if (mySeq !== districtLoadSeq) return; // ignore outdated
                console.log('Districts data received:', data);
                districtSelect.disabled = false;
                data.forEach(function(district) {
                    districtSelect.innerHTML += `<option value="${district.ten_quan_huyen}" data-id="${district.id}">${district.ten_quan_huyen}</option>`;
                });
            })
            .catch(error => {
                console.error('Error loading districts:', error);
                districtSelect.innerHTML = '<option value="">-- Lỗi tải dữ liệu --</option>';
            });
    });

    // Khi chọn quận/huyện
    districtSelect?.addEventListener('change', function() {
        const districtId = this.options[this.selectedIndex]?.getAttribute('data-id');
        
        // Reset ward
        wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
        wardSelect.disabled = true;
        
        if (!districtId) return;
        
        // Load wards
        console.log('Loading wards for district:', districtId);
        const myWardSeq = ++wardLoadSeq;
        fetch(`/address/wards/${districtId}`)
            .then(res => {
                console.log('Wards response status:', res.status);
                return res.json();
            })
            .then(data => {
                if (myWardSeq !== wardLoadSeq) return; // ignore outdated
                console.log('Wards data received:', data);
                wardSelect.disabled = false;
                data.forEach(function(ward) {
                    wardSelect.innerHTML += `<option value="${ward.ten_phuong_xa}">${ward.ten_phuong_xa}</option>`;
                });
            })
            .catch(error => {
                console.error('Error loading wards:', error);
                wardSelect.innerHTML = '<option value="">-- Lỗi tải dữ liệu --</option>';
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
            const isFlash = !!item.is_flash_sale;
            const priceHtml = isFlash
                ? `<div><span class="text-danger fw-bold">${formatCurrency(item.price)}</span> <small class="text-muted text-decoration-line-through ms-1">${formatCurrency(item.original_price || item.price)}</small></div><span class="badge bg-danger text-white flash-sale-badge"><i class="bi bi-lightning-fill me-1"></i>Flash Sale</span>`
                : `<span class="fw-semibold">${formatCurrency(item.price)}</span>`;
            tr.innerHTML = `
            <td>
    <div style="display: flex; align-items: center;" class="${isFlash ? 'flash-sale-item' : ''}">
        <!-- Ảnh sản phẩm -->
        <img src="${item.image}" alt="${item.name}" 
             style="width:40px; height:40px; object-fit:cover; margin-right:8px; ${isFlash ? 'border: 2px solid #dc3545; border-radius: 4px;' : ''}">

        <!-- Thông tin sản phẩm -->
        <div>
            <div style="font-weight: 500;">${item.name} ${isFlash ? '<i class="bi bi-lightning-fill text-danger ms-1" title="Sản phẩm Flash Sale"></i>' : ''}</div>
            <div style="font-size: 12px; color: #555;">
                <span>${item.color}</span> | <span>${item.capacity}</span>
            </div>
        </div>
    </div>
</td>

           
            <td>${priceHtml}</td>
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

    // Voucher listing and selection
    async function fetchActiveVouchers() {
        try {
            const res = await fetch('/vouchers/active');
            return await res.json();
        } catch (e) {
            return [];
        }
    }

    async function initVoucherSelect() {
        const select = document.getElementById('voucher-select');
        const msg = document.getElementById('voucher-message');
        const box = document.getElementById('voucher-box');
        const list = await fetchActiveVouchers();
        list.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v.id;
            opt.setAttribute('data-code', v.code);
            opt.setAttribute('data-discount', v.discount);
            opt.setAttribute('data-min', v.min_money ?? 0);
            opt.setAttribute('data-max', v.max_money ?? 0);
            opt.textContent = `${v.code} - Giảm ${v.discount}% (tối thiểu ${formatCurrency(v.min_money || 0)}, tối đa ${formatCurrency(v.max_money || 0)})`;
            select.appendChild(opt);
        });

        // Restore previous selection
        try {
            const saved = JSON.parse(localStorage.getItem('checkout_voucher') || 'null');
            if (saved && saved.id) {
                select.value = String(saved.id);
                window.currentVoucher = saved;
                box.style.border = '2px solid #28a745';
                msg.innerText = `Áp dụng: ${saved.code} - Giảm ${saved.discount}%`;
                updateCartSummary();
            }
        } catch (e) {}

        select.addEventListener('change', async function() {
            const option = this.options[this.selectedIndex];
            if (!this.value) {
                msg.innerText = 'Không áp dụng voucher';
                box.style.border = '';
                window.currentVoucher = null;
                localStorage.removeItem('checkout_voucher');
                updateCartSummary();
                return;
            }
            const code = option.getAttribute('data-code');
            try {
                const res = await fetch(`/api/voucher?code=${encodeURIComponent(code)}`);
                const data = await res.json();
                if (data && data.success) {
                    const applied = {
                        success: true,
                        id: this.value,
                        code: code,
                        discount: Number(data.discount ?? option.getAttribute('data-discount')),
                        min_money: Number(data.min_money ?? option.getAttribute('data-min')),
                        max_money: Number(data.max_money ?? option.getAttribute('data-max'))
                    };
                    window.currentVoucher = applied;
                    localStorage.setItem('checkout_voucher', JSON.stringify(applied));
                    msg.innerText = `Áp dụng: ${applied.code} - Giảm ${applied.discount}% (tối thiểu ${formatCurrency(applied.min_money)}, tối đa ${formatCurrency(applied.max_money)})`;
                    box.style.border = '2px solid #28a745';
                    updateCartSummary();
                } else {
                    // Nếu BE thông báo đã dùng hoặc không hợp lệ: xóa chọn và thông báo
                    window.currentVoucher = null;
                    localStorage.removeItem('checkout_voucher');
                    this.value = '';
                    box.style.border = '';
                    const message = data?.message || 'Mã giảm giá không hợp lệ hoặc đã hết hạn';
                    msg.innerText = message;
                    updateCartSummary();
                }
            } catch (e) {
                // Lỗi mạng: không áp dụng voucher
                window.currentVoucher = null;
                localStorage.removeItem('checkout_voucher');
                this.value = '';
                box.style.border = '';
                msg.innerText = 'Không thể xác thực mã giảm giá. Vui lòng thử lại.';
                updateCartSummary();
            }
        });
    }

    // Real-time validation
    function addRealTimeValidation() {
        const fields = ['fullname', 'phone', 'email', 'address_detail'];
        
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('blur', function() {
                    const value = this.value.trim();
                    let rules = {};
                    
                    switch(fieldId) {
                        case 'fullname':
                            rules = {
                                required: 'Vui lòng nhập họ và tên',
                                minLength: 2,
                                minLengthMessage: 'Họ và tên phải có ít nhất 2 ký tự'
                            };
                            break;
                        case 'phone':
                            rules = {
                                required: 'Vui lòng nhập số điện thoại',
                                pattern: /^0\d{9}$/,
                                patternMessage: 'Số điện thoại phải gồm 10 số và bắt đầu bằng 0'
                            };
                            break;
                        case 'email':
                            rules = {
                                required: 'Vui lòng nhập email',
                                pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                patternMessage: 'Email không hợp lệ'
                            };
                            break;
                        case 'address_detail':
                            rules = {
                                required: 'Vui lòng nhập địa chỉ chi tiết',
                                minLength: 5,
                                minLengthMessage: 'Địa chỉ phải có ít nhất 5 ký tự'
                            };
                            break;
                    }
                    
                    validateField(fieldId, value, rules);
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', async function() {
        await ensureProvincesLoaded();
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

        // Add real-time validation
        addRealTimeValidation();

        // Init voucher select list
        initVoucherSelect();

        // Helpers to load address levels programmatically (for prefilling)
        async function loadDistrictsByProvinceId(provinceId) {
            districtSelect.innerHTML = '<option value="">-- Chọn quận/huyện --</option>';
            wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
            districtSelect.disabled = true;
            wardSelect.disabled = true;
            if (!provinceId) return [];
            try {
                const res = await fetch(`/address/districts/${provinceId}`);
                const data = await res.json();
                districtSelect.disabled = false;
                data.forEach(function(district) {
                    districtSelect.innerHTML += `<option value="${district.ten_quan_huyen}" data-id="${district.id}">${district.ten_quan_huyen}</option>`;
                });
                return data;
            } catch (_) {
                districtSelect.innerHTML = '<option value="">-- Lỗi tải dữ liệu --</option>';
                return [];
            }
        }

        async function loadWardsByDistrictId(districtId) {
            wardSelect.innerHTML = '<option value="">-- Chọn phường/xã --</option>';
            wardSelect.disabled = true;
            if (!districtId) return [];
            try {
                const res = await fetch(`/address/wards/${districtId}`);
                const data = await res.json();
                wardSelect.disabled = false;
                data.forEach(function(ward) {
                    wardSelect.innerHTML += `<option value="${ward.ten_phuong_xa}">${ward.ten_phuong_xa}</option>`;
                });
                return data;
            } catch (_) {
                wardSelect.innerHTML = '<option value="">-- Lỗi tải dữ liệu --</option>';
                return [];
            }
        }

        // Lấy thông tin user và fill vào form nếu có
        try {
            const userRes = await fetch('/api/user', {
                credentials: 'same-origin'
            });
            const userData = await userRes.json();
            if (userData.success && userData.user) {
                document.getElementById('fullname').value = userData.user.name || '';
                document.getElementById('phone').value = userData.user.phone || '';
                document.getElementById('email').value = userData.user.email || '';
                document.getElementById('address_detail').value = userData.user.street || '';
                const savedProvinceName = userData.user.city || '';
                const savedDistrictName = userData.user.district || '';
                const savedWardName = userData.user.ward || '';

                // Prefill province by matching option text
                if (savedProvinceName) {
                    let selectedProvinceOption = null;
                    for (const opt of provinceSelect.options) {
                        if ((opt.textContent || '').trim() === savedProvinceName.trim()) {
                            selectedProvinceOption = opt;
                            break;
                        }
                    }
                    if (selectedProvinceOption) {
                        provinceSelect.value = selectedProvinceOption.value;
                        const provinceId = selectedProvinceOption.getAttribute('data-id');
                        // Load districts and then wards based on saved values
                        const districts = await loadDistrictsByProvinceId(provinceId);
                        if (savedDistrictName) {
                            let selectedDistrictOption = null;
                            for (const opt of districtSelect.options) {
                                if ((opt.textContent || '').trim() === savedDistrictName.trim()) {
                                    selectedDistrictOption = opt;
                                    break;
                                }
                            }
                            if (selectedDistrictOption) {
                                districtSelect.value = selectedDistrictOption.value;
                                const districtId = selectedDistrictOption.getAttribute('data-id');
                                await loadWardsByDistrictId(districtId);
                                if (savedWardName) {
                                    for (const opt of wardSelect.options) {
                                        if ((opt.textContent || '').trim() === savedWardName.trim()) {
                                            wardSelect.value = opt.value;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (e) {}
    });

    // Validation functions
    function validateField(fieldId, value, rules) {
        const field = document.getElementById(fieldId);
        const errorElement = document.getElementById(fieldId + '-error');
        
        // Clear previous errors
        field.classList.remove('is-invalid');
        errorElement.textContent = '';
        
        // Check required
        if (rules.required && (!value || value.trim() === '')) {
            field.classList.add('is-invalid');
            errorElement.textContent = rules.required;
            return false;
        }
        
        // Check pattern
        if (rules.pattern && value && !rules.pattern.test(value)) {
            field.classList.add('is-invalid');
            errorElement.textContent = rules.patternMessage;
            return false;
        }
        
        // Check min length
        if (rules.minLength && value && value.length < rules.minLength) {
            field.classList.add('is-invalid');
            errorElement.textContent = rules.minLengthMessage;
            return false;
        }
        
        return true;
    }

    function validateForm() {
        let isValid = true;
        
        // Validate fullname
        const fullname = document.getElementById('fullname').value.trim();
        isValid &= validateField('fullname', fullname, {
            required: 'Vui lòng nhập họ và tên',
            minLength: 2,
            minLengthMessage: 'Họ và tên phải có ít nhất 2 ký tự'
        });
        
        // Validate phone
        const phone = document.getElementById('phone').value.trim();
        isValid &= validateField('phone', phone, {
            required: 'Vui lòng nhập số điện thoại',
            pattern: /^0\d{9}$/,
            patternMessage: 'Số điện thoại phải gồm 10 số và bắt đầu bằng 0'
        });
        
        // Validate email
        const email = document.getElementById('email').value.trim();
        isValid &= validateField('email', email, {
            required: 'Vui lòng nhập email',
            pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            patternMessage: 'Email không hợp lệ'
        });
        
        // Validate address detail
        const addressDetail = document.getElementById('address_detail').value.trim();
        isValid &= validateField('address_detail', addressDetail, {
            required: 'Vui lòng nhập địa chỉ chi tiết',
            minLength: 5,
            minLengthMessage: 'Địa chỉ phải có ít nhất 5 ký tự'
        });
        
        // Validate province
        const province = document.getElementById('province').value;
        isValid &= validateField('province', province, {
            required: 'Vui lòng chọn tỉnh/thành phố'
        });
        
        // Validate district
        const district = document.getElementById('district').value;
        isValid &= validateField('district', district, {
            required: 'Vui lòng chọn quận/huyện'
        });
        
        // Validate ward
        const ward = document.getElementById('ward').value;
        isValid &= validateField('ward', ward, {
            required: 'Vui lòng chọn phường/xã'
        });
        
        return isValid;
    }

    document.getElementById('go-to-checkout').onclick = function(e) {
        if (e) e.preventDefault();
        
        // Validate form
        if (!validateForm()) {
            // Scroll to first error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
            return;
        }
        // Lưu cartData, user info và voucher vào localStorage
        const userInfo = {
           fullname: document.getElementById('fullname').value.trim(),
            phone: document.getElementById('phone').value.trim(),
            email: document.getElementById('email').value.trim(),
            street: document.getElementById('address_detail').value.trim(),
            ward: document.getElementById('ward').value,
            district: document.getElementById('district').value,
            city: document.getElementById('province').value,
            note: document.getElementById('note').value.trim(),
            payment: document.querySelector('input[name="payment"]:checked')?.value || 'cod'
        };


        localStorage.setItem('checkout_cart', JSON.stringify(cartData));
        localStorage.setItem('checkout_user', JSON.stringify(userInfo));
        if (window.currentVoucher) {
            localStorage.setItem('checkout_voucher', JSON.stringify(window.currentVoucher));
        } else {
            localStorage.removeItem('checkout_voucher');
        }
        window.location.href = '/checkout';
    };
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

    .cart-summary {
        background: #fff;
    }

    .voucher-box {
        border: 1.5px dashed #ccc;
        transition: border 0.2s;
    }

    /* Validation styles */
    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }

    .form-label .text-danger {
        font-weight: bold;
    }

    /* Disabled select styling */
    .form-select:disabled {
        background-color: #e9ecef;
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Loading state for selects */
    .form-select.loading {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%236c757d'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m6 1.5v3m0 3v3'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
    }

    /* Flash Sale Styles */
    .flash-sale-item {
        background: linear-gradient(90deg, rgba(220, 53, 69, 0.05), rgba(255, 255, 255, 0.05));
        border-radius: 8px;
        padding: 8px;
        margin: -4px;
        border-left: 4px solid #dc3545;
    }

    .flash-sale-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        animation: flashPulse 2s infinite;
        box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
    }

    @keyframes flashPulse {
        0%, 100% { 
            transform: scale(1);
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
        }
        50% { 
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.5);
        }
    }

    /* Flash sale row highlight */
    tr:has(.flash-sale-item) {
        background: rgba(220, 53, 69, 0.02);
        border-left: 3px solid #dc3545;
    }

    tr:has(.flash-sale-item):hover {
        background: rgba(220, 53, 69, 0.05);
    }
</style>


@endsection

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush