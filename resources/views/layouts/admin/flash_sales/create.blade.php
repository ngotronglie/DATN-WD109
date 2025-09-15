@extends('index.admindashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tạo Flash Sale mới</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.flash-sales.store') }}" method="POST" id="flashSaleForm">
                        @csrf
                        <div class="row">
                            <!-- Thông tin cơ bản -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Tên Flash Sale <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}" required>
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
                                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Tạm dừng</option>
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
                                           id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_time">Thời gian kết thúc <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror"
                                           id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Chọn sản phẩm -->
                            <div class="col-12">
                                <hr>
                                <h4>Chọn sản phẩm cho Flash Sale</h4>
                                
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
                                                <th width="22%">Sản phẩm</th>
                                                <th width="10%">Ảnh</th>
                                                <th width="12%">Giá gốc</th>
                                                <th width="15%">Giá Flash Sale <span class="text-danger">*</span></th>
                                                <th width="18%">Số lượng <span class="text-danger">*</span></th>
                                                <th width="12%">Trạng thái</th>
                                                <th width="12%">Tiết kiệm</th>
                                                <th width="12%">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Sản phẩm sẽ được thêm vào đây -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary">Tạo Flash Sale</button>
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
    const variantSelect = document.getElementById('variant_select');
    const addProductBtn = document.getElementById('add_product_btn');
    const productsTable = document.getElementById('products-table').getElementsByTagName('tbody')[0];
    const form = document.getElementById('flashSaleForm');
    
    let productIndex = 0;
    let selectedVariants = new Set(); // Để tránh trùng lặp variant
    let productsData = []; // Lưu dữ liệu sản phẩm

    // Load sản phẩm theo danh mục
    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        
        if (!categoryId) {
            productSelect.innerHTML = '<option value="">-- Chọn danh mục trước --</option>';
            productSelect.disabled = true;
            variantSelect.innerHTML = '<option value="">-- Chọn sản phẩm trước --</option>';
            variantSelect.disabled = true;
            addProductBtn.disabled = true;
            return;
        }

        // Gọi API để lấy sản phẩm theo danh mục
        fetch(`{{ route('admin.flash-sales.products.byCategory') }}?category_id=${categoryId}`)
            .then(response => response.json())
            .then(data => {
                productsData = data;
                productSelect.innerHTML = '<option value="">-- Chọn sản phẩm --</option>';
                
                data.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = product.name;
                    productSelect.appendChild(option);
                });
                
                productSelect.disabled = false;
                variantSelect.innerHTML = '<option value="">-- Chọn sản phẩm trước --</option>';
                variantSelect.disabled = true;
                addProductBtn.disabled = true;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi khi tải danh sách sản phẩm');
            });
    });

    // Load variants theo sản phẩm
    productSelect.addEventListener('change', function() {
        const productId = this.value;
        
        if (!productId) {
            variantSelect.innerHTML = '<option value="">-- Chọn sản phẩm trước --</option>';
            variantSelect.disabled = true;
            addProductBtn.disabled = true;
            return;
        }

        const selectedProduct = productsData.find(p => p.id == productId);
        if (selectedProduct) {
            variantSelect.innerHTML = '<option value="">-- Chọn biến thể --</option>';
            
            selectedProduct.variants.forEach(variant => {
                if (!selectedVariants.has(variant.id)) {
                    const option = document.createElement('option');
                    option.value = variant.id;
                    option.textContent = `${variant.color_name} - ${variant.capacity_name} (${formatPrice(variant.price_sale || variant.price)})`;
                    option.dataset.variant = JSON.stringify(variant);
                    option.dataset.productName = selectedProduct.name;
                    variantSelect.appendChild(option);
                }
            });
            
            variantSelect.disabled = false;
        }
        
        addProductBtn.disabled = true;
    });

    // Enable/disable add button
    variantSelect.addEventListener('change', function() {
        addProductBtn.disabled = !this.value;
    });

    // Thêm variant vào bảng
    addProductBtn.addEventListener('click', function() {
        const selectedVariantOption = variantSelect.options[variantSelect.selectedIndex];
        
        if (!selectedVariantOption.value) return;

        const variantId = selectedVariantOption.value;
        const variantData = JSON.parse(selectedVariantOption.dataset.variant);
        const productName = selectedVariantOption.dataset.productName;
        const originalPrice = variantData.price_sale || variantData.price;
        const maxQuantity = variantData.quantity;
        const variantImage = variantData.image;

        // Thêm vào set để tránh trùng lặp
        selectedVariants.add(variantId);

        // Tạo row mới
        const row = productsTable.insertRow();
        row.innerHTML = `
            <td>
                <strong>${productName}</strong><br>
                <small class="text-muted">${variantData.color_name} - ${variantData.capacity_name}</small>
                <input type="hidden" name="products[${productIndex}][product_variant_id]" value="${variantId}">
            </td>
            <td>
                ${variantImage ? `<img src="${variantImage}" alt="${productName}" style="width: 40px; height: 40px; object-fit: cover;">` : '<div class="bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-image text-muted"></i></div>'}
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
                <input type="number" class="form-control sale-quantity-input" 
                       name="products[${productIndex}][sale_quantity]" 
                       required min="1" max="${maxQuantity}"
                       data-max-quantity="${maxQuantity}"
                       data-index="${productIndex}"
                       placeholder="Max: ${maxQuantity}">
            </td>
            <td>
                <select class="form-control" name="products[${productIndex}][status]">
                    <option value="active">Hoạt động</option>
                    <option value="featured">Nổi bật</option>
                    <option value="inactive">Tạm dừng</option>
                </select>
            </td>
            <td class="text-center">
                <span class="saving-amount" data-index="${productIndex}">-</span>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-product" 
                        data-variant-id="${variantId}" title="Xóa sản phẩm">
                    🗑️
                </button>
            </td>
        `;

        // Xóa option khỏi select
        selectedVariantOption.remove();
        
        // Reset selections
        variantSelect.value = '';
        addProductBtn.disabled = true;

        productIndex++;
    });

    // Xóa variant khỏi bảng
    productsTable.addEventListener('click', function(e) {
        const removeButton = e.target.closest('.remove-product');
        if (removeButton) {
            const variantId = removeButton.dataset.variantId;
            const row = removeButton.closest('tr');
            
            // Xác nhận trước khi xóa
            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi Flash Sale?')) {
                // Xóa khỏi set
                selectedVariants.delete(variantId);
                
                // Xóa row
                row.remove();
                
                // Reload lại danh sách variants trong product hiện tại
                if (productSelect.value) {
                    productSelect.dispatchEvent(new Event('change'));
                }
            }
        }
    });

    // Tính toán tiết kiệm khi nhập giá flash sale và validate số lượng
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

    // Validate form trước khi submit
    form.addEventListener('submit', function(e) {
        if (productsTable.rows.length === 0) {
            e.preventDefault();
            alert('Vui lòng thêm ít nhất một sản phẩm vào Flash Sale!');
            return;
        }

        // Kiểm tra thời gian
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

    // Helper function
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
