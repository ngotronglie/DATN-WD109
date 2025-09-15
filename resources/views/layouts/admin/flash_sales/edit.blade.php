@extends('index.admindashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chỉnh sửa Flash Sale: {{ $flashSale->name }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.flash-sales.update', $flashSale->id) }}" method="POST" id="flashSaleForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <!-- Thông tin cơ bản -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Tên Flash Sale <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $flashSale->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active">Trạng thái</label>
                                    <select class="form-control @error('is_active') is-invalid @enderror"
                                            id="is_active" name="is_active">
                                        <option value="1" {{ old('is_active', $flashSale->is_active) == '1' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ old('is_active', $flashSale->is_active) == '0' ? 'selected' : '' }}>Tạm dừng</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Thời gian -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_time">Thời gian bắt đầu <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror"
                                           id="start_time" name="start_time" 
                                           value="{{ old('start_time', $flashSale->start_time->format('Y-m-d\TH:i')) }}" 
                                           required {{ $flashSale->isOngoing() ? 'readonly' : '' }}>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($flashSale->isOngoing())
                                        <small class="text-muted">Flash sale đang diễn ra, không thể thay đổi thời gian bắt đầu</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_time">Thời gian kết thúc <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror"
                                           id="end_time" name="end_time" 
                                           value="{{ old('end_time', $flashSale->end_time->format('Y-m-d\TH:i')) }}" required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Chọn sản phẩm -->
                            <div class="col-12">
                                <hr>
                                <h4>Quản lý sản phẩm Flash Sale</h4>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="category_select">Chọn danh mục</label>
                                        <select class="form-control" id="category_select">
                                            <option value="">-- Chọn danh mục --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->ID }}">{{ $category->Name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="product_select">Chọn sản phẩm</label>
                                        <select class="form-control" id="product_select" disabled>
                                            <option value="">-- Chọn danh mục trước --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="variant_select">Chọn biến thể</label>
                                        <select class="form-control" id="variant_select" disabled>
                                            <option value="">-- Chọn sản phẩm trước --</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-success mb-3" id="add_product_btn" disabled>
                                    <i class="fas fa-plus"></i> Thêm biến thể vào Flash Sale
                                </button>
                            </div>

                            <!-- Bảng sản phẩm đã chọn -->
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="products-table">
                                        <thead>
                                            <tr>
                                                <th width="18%">Sản phẩm</th>
                                                <th width="7%">Ảnh</th>
                                                <th width="9%">Giá gốc</th>
                                                <th width="11%">Giá Flash Sale <span class="text-danger">*</span></th>
                                                <th width="15%">Số lượng <span class="text-danger">*</span></th>
                                                <th width="7%">Ưu tiên</th>
                                                <th width="9%">Trạng thái</th>
                                                <th width="7%">Đã bán</th>
                                                <th width="10%">Tiết kiệm</th>
                                                <th width="10%">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($flashSale->flashSaleProducts as $index => $flashSaleProduct)
                                            <tr data-existing="true">
                                                <td>
                                                    {{ $flashSaleProduct->productVariant->product->name }}
                                                    ({{ $flashSaleProduct->productVariant->color->name ?? 'N/A' }} - 
                                                     {{ $flashSaleProduct->productVariant->capacity->name ?? 'N/A' }})
                                                    <input type="hidden" name="products[{{ $index }}][product_variant_id]" 
                                                           value="{{ $flashSaleProduct->product_variant_id }}">
                                                    <input type="hidden" name="products[{{ $index }}][id]" 
                                                           value="{{ $flashSaleProduct->id }}">
                                                </td>
                                                <td>
                                                    @if($flashSaleProduct->productVariant->image)
                                                        <img src="{{ asset('storage/' . $flashSaleProduct->productVariant->image) }}" 
                                                             alt="{{ $flashSaleProduct->productVariant->product->name }}" 
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <strong>{{ number_format($flashSaleProduct->original_price) }}đ</strong>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control sale-price-input" 
                                                           name="products[{{ $index }}][sale_price]" 
                                                           value="{{ $flashSaleProduct->sale_price }}"
                                                           required min="1" max="{{ $flashSaleProduct->original_price - 1 }}" 
                                                           data-original-price="{{ $flashSaleProduct->original_price }}"
                                                           data-index="{{ $index }}">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control sale-quantity-input" 
                                                           name="products[{{ $index }}][sale_quantity]" 
                                                           value="{{ $flashSaleProduct->sale_quantity }}"
                                                           required min="1" 
                                                           max="{{ $flashSaleProduct->productVariant->quantity + $flashSaleProduct->sale_quantity }}"
                                                           data-max-quantity="{{ $flashSaleProduct->productVariant->quantity + $flashSaleProduct->sale_quantity }}"
                                                           data-index="{{ $index }}"
                                                           placeholder="Max: {{ $flashSaleProduct->productVariant->quantity + $flashSaleProduct->sale_quantity }}">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" 
                                                           name="products[{{ $index }}][priority]" 
                                                           value="{{ $flashSaleProduct->priority ?? 0 }}"
                                                           min="0" max="999" placeholder="0">
                                                </td>
                                                <td>
                                                    <select class="form-control" name="products[{{ $index }}][status]">
                                                        <option value="active" {{ ($flashSaleProduct->status ?? 'active') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                                        <option value="featured" {{ ($flashSaleProduct->status ?? 'active') == 'featured' ? 'selected' : '' }}>Nổi bật</option>
                                                        <option value="inactive" {{ ($flashSaleProduct->status ?? 'active') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info">
                                                        {{ $flashSaleProduct->initial_stock - $flashSaleProduct->remaining_stock }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $saving = $flashSaleProduct->original_price - $flashSaleProduct->sale_price;
                                                        $percentage = round(($saving / $flashSaleProduct->original_price) * 100);
                                                    @endphp
                                                    <span class="saving-amount text-success" data-index="{{ $index }}">
                                                        {{ number_format($saving) }}đ<br>
                                                        <small>({{ $percentage }}%)</small>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm remove-product" 
                                                            data-product-id="{{ $flashSaleProduct->product_variant_id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary">Cập nhật Flash Sale</button>
                                <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_select');
    const productSelect = document.getElementById('product_select');
    const addProductBtn = document.getElementById('add_product_btn');
    const productsTable = document.getElementById('products-table').getElementsByTagName('tbody')[0];
    const form = document.getElementById('flashSaleForm');
    
    let productIndex = {{ $flashSale->flashSaleProducts->count() }};
    let selectedProducts = new Set();

    // Thêm các sản phẩm hiện có vào set
    @foreach($flashSale->flashSaleProducts as $flashSaleProduct)
        selectedProducts.add('{{ $flashSaleProduct->product_variant_id }}');
    @endforeach

    // Load sản phẩm theo danh mục
    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        
        if (!categoryId) {
            productSelect.innerHTML = '<option value="">-- Chọn danh mục trước --</option>';
            productSelect.disabled = true;
            addProductBtn.disabled = true;
            return;
        }

        fetch(`{{ route('admin.flash-sales.products.byCategory') }}?category_id=${categoryId}`)
            .then(response => response.json())
            .then(data => {
                productSelect.innerHTML = '<option value="">-- Chọn sản phẩm --</option>';
                
                data.forEach(product => {
                    if (!selectedProducts.has(product.id.toString())) {
                        const option = document.createElement('option');
                        option.value = product.id;
                        option.textContent = product.name;
                        option.dataset.price = product.price;
                        option.dataset.quantity = product.quantity;
                        option.dataset.image = product.image;
                        productSelect.appendChild(option);
                    }
                });
                
                productSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi khi tải danh sách sản phẩm');
            });
    });

    productSelect.addEventListener('change', function() {
        addProductBtn.disabled = !this.value;
    });

    // Thêm sản phẩm vào bảng
    addProductBtn.addEventListener('click', function() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        
        if (!selectedOption.value) return;

        const productId = selectedOption.value;
        const productName = selectedOption.textContent;
        const originalPrice = parseFloat(selectedOption.dataset.price);
        const maxQuantity = parseInt(selectedOption.dataset.quantity);
        const productImage = selectedOption.dataset.image;

        selectedProducts.add(productId);

        const row = productsTable.insertRow();
        row.innerHTML = `
            <td>
                ${productName}
                <input type="hidden" name="products[${productIndex}][product_variant_id]" value="${productId}">
            </td>
            <td>
                <img src="${productImage}" alt="${productName}" style="width: 50px; height: 50px; object-fit: cover;">
            </td>
            <td class="text-center">
                <strong>${formatPrice(originalPrice)}</strong>
            </td>
            <td>
                <input type="number" class="form-control sale-price-input" 
                       name="products[${productIndex}][sale_price]" 
                       required min="1" max="${originalPrice - 1}" 
                       data-original-price="${originalPrice}"
                       data-index="${productIndex}">
            </td>
            <td>
                <input type="number" class="form-control" 
                       name="products[${productIndex}][sale_quantity]" 
                       required min="1" max="${maxQuantity}"
                       placeholder="Max: ${maxQuantity}">
            </td>
            <td class="text-center">
                <span class="badge bg-info">0</span>
            </td>
            <td class="text-center">
                <span class="saving-amount" data-index="${productIndex}">-</span>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-product" 
                        data-product-id="${productId}">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        selectedOption.remove();
        productSelect.value = '';
        addProductBtn.disabled = true;

        productIndex++;
    });

    // Xóa sản phẩm
    productsTable.addEventListener('click', function(e) {
        if (e.target.closest('.remove-product')) {
            const button = e.target.closest('.remove-product');
            const productId = button.dataset.productId;
            const row = button.closest('tr');
            
            selectedProducts.delete(productId);
            row.remove();
            
            if (categorySelect.value) {
                categorySelect.dispatchEvent(new Event('change'));
            }
        }
    });

    // Tính toán tiết kiệm và validate số lượng
    productsTable.addEventListener('input', function(e) {
        if (e.target.classList.contains('sale-price-input')) {
            const salePrice = parseFloat(e.target.value) || 0;
            const originalPrice = parseFloat(e.target.dataset.originalPrice);
            const index = e.target.dataset.index;
            const savingSpan = document.querySelector(`span.saving-amount[data-index="${index}"]`);
            
            // Xóa class lỗi trước đó
            e.target.classList.remove('is-invalid');
            
            if (salePrice >= originalPrice) {
                // Hiển thị lỗi nếu giá flash sale >= giá gốc
                e.target.classList.add('is-invalid');
                savingSpan.innerHTML = '<span class="text-danger">Giá phải nhỏ hơn giá gốc!</span>';
                savingSpan.className = 'saving-amount text-danger';
            } else if (salePrice > 0 && salePrice < originalPrice) {
                const saving = originalPrice - salePrice;
                const percentage = Math.round((saving / originalPrice) * 100);
                savingSpan.innerHTML = `${formatPrice(saving)}<br><small>(${percentage}%)</small>`;
                savingSpan.className = 'saving-amount text-success';
            } else {
                savingSpan.textContent = '-';
                savingSpan.className = 'saving-amount';
            }
        }
        
        if (e.target.classList.contains('sale-quantity-input')) {
            const saleQuantity = parseInt(e.target.value) || 0;
            const maxQuantity = parseInt(e.target.dataset.maxQuantity);
            
            // Xóa class lỗi trước đó
            e.target.classList.remove('is-invalid');
            
            if (saleQuantity > maxQuantity) {
                // Hiển thị lỗi nếu số lượng > tồn kho
                e.target.classList.add('is-invalid');
            }
        }
    });

    // Validate form
    form.addEventListener('submit', function(e) {
        if (productsTable.rows.length === 0) {
            e.preventDefault();
            alert('Vui lòng thêm ít nhất một sản phẩm vào Flash Sale!');
            return;
        }

        const startTime = new Date(document.getElementById('start_time').value);
        const endTime = new Date(document.getElementById('end_time').value);
        
        if (endTime <= startTime) {
            e.preventDefault();
            alert('Thời gian kết thúc phải sau thời gian bắt đầu!');
            return;
        }

        // Kiểm tra giá flash sale
        const salePriceInputs = productsTable.querySelectorAll('.sale-price-input');
        let hasInvalidPrice = false;
        
        salePriceInputs.forEach(input => {
            const salePrice = parseFloat(input.value) || 0;
            const originalPrice = parseFloat(input.dataset.originalPrice);
            
            if (salePrice >= originalPrice) {
                hasInvalidPrice = true;
                input.classList.add('is-invalid');
            }
        });
        
        if (hasInvalidPrice) {
            e.preventDefault();
            alert('Giá Flash Sale phải nhỏ hơn giá gốc!');
            return;
        }

        // Kiểm tra số lượng flash sale
        const saleQuantityInputs = productsTable.querySelectorAll('.sale-quantity-input');
        let hasInvalidQuantity = false;
        
        saleQuantityInputs.forEach(input => {
            const saleQuantity = parseInt(input.value) || 0;
            const maxQuantity = parseInt(input.dataset.maxQuantity);
            
            if (saleQuantity > maxQuantity) {
                hasInvalidQuantity = true;
                input.classList.add('is-invalid');
            }
        });
        
        if (hasInvalidQuantity) {
            e.preventDefault();
            alert('Số lượng Flash Sale không được lớn hơn tồn kho!');
            return;
        }
    });

    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(price);
    }
});
</script>

<style>
.table th, .table td {
    vertical-align: middle;
}
.saving-amount {
    font-weight: bold;
}
</style>
@endsection
