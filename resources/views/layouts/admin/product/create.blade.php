@extends('index.admindashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thêm sản phẩm mới</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.store') }}" method="POST" id="productForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group">
                                <label for="name">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="categories_id">Danh mục <span class="text-danger">*</span></label>
                                    <select class="form-control @error('categories_id') is-invalid @enderror"
                                            id="categories_id" name="categories_id" required>
                                        <option value="">Chọn danh mục</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->ID }}"
                                                {{ old('categories_id') == $category->ID ? 'selected' : '' }}>
                                                {{ $category->Name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categories_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="is_active">Trạng thái</label>
                                    <select class="form-control @error('is_active') is-invalid @enderror"
                                            id="is_active" name="is_active">
                                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="default_price">Giá mặc định</label>
                                    <input type="number" class="form-control" id="default_price"
                                           placeholder="Nhập giá mặc định cho tất cả biến thể" min="0">
                                    <small class="form-text text-muted">Giá này sẽ được áp dụng cho tất cả biến thể khi tạo mới</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="default_price_sale">Giá khuyến mãi mặc định</label>
                                    <input type="number" class="form-control" id="default_price_sale"
                                           placeholder="Nhập giá khuyến mãi mặc định" min="0">
                                    <small class="form-text text-muted">Giá khuyến mãi này sẽ được áp dụng cho tất cả biến thể khi tạo mới</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="default_quantity">Số lượng mặc định</label>
                                    <input type="number" class="form-control" id="default_quantity"
                                           placeholder="Nhập số lượng mặc định cho tất cả biến thể" min="0">
                                    <small class="form-text text-muted">Số lượng này sẽ được áp dụng cho tất cả biến thể khi tạo mới</small>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <h4>Biến thể sản phẩm</h4>
                                    <div class="variant-item border p-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Màu sắc <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="color-select" data-choices data-choices-removeItem multiple>
                                                        @foreach($colors as $color)
                                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Dung lượng <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="capacity-select" data-choices data-choices-removeItem multiple>
                                                        @foreach($capacities as $capacity)
                                                            <option value="{{ $capacity->id }}">{{ $capacity->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bảng hiển thị biến thể -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="variants-table">
                                            <thead>
                                                <tr>
                                                    <th>Màu sắc</th>
                                                    <th>Dung lượng</th>
                                                    <th>Giá <span class="text-danger">*</span></th>
                                                    <th>Giá khuyến mãi</th>
                                                    <th>Số lượng <span class="text-danger">*</span></th>
                                                    <th>Ảnh sản phẩm</th>
                                                    <th>Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Dữ liệu sẽ được thêm vào đây bằng JavaScript -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
                                </div>
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
    const colorSelect = document.getElementById('color-select');
    const capacitySelect = document.getElementById('capacity-select');
    const variantsTable = document.getElementById('variants-table').getElementsByTagName('tbody')[0];
    const form = document.getElementById('productForm');

    // Thêm event listener cho nút thêm ảnh
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-image-btn')) {
            const variantIndex = e.target.getAttribute('data-variant-index');
            const fileInput = document.querySelector(`input[name="variants[${variantIndex}][image]"]`);
            if (fileInput) {
                fileInput.click();
            }
        }
    });

    // Hàm xử lý upload ảnh
    window.handleImageUpload = function(input, variantIndex) {
        const previewContainer = document.getElementById(`preview-${variantIndex}`);
        if (!previewContainer) return;

        previewContainer.innerHTML = ''; // Chỉ 1 ảnh, xóa cũ

        if (input.files && input.files.length > 0) {
            const file = input.files[0];
            if (!file.type.startsWith('image/')) {
                alert('Vui lòng chỉ chọn file ảnh!');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'image-preview';
                previewDiv.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                    <button type="button" class="btn btn-danger btn-sm mt-1 remove-image"
                            onclick="removeImage(this, ${variantIndex})">
                        Xóa
                    </button>
                `;
                previewContainer.appendChild(previewDiv);
            };
            reader.readAsDataURL(file);
        }
    };

    // Hàm xóa ảnh
    window.removeImage = function(button, variantIndex) {
        const previewDiv = button.closest('.image-preview');
        if (previewDiv) {
            previewDiv.remove();
        }
        // Xóa file khỏi input
        const input = document.querySelector(`input[name="variants[${variantIndex}][image]"]`);
        if (input) input.value = '';
    };

    // Hàm tạo các biến thể
    function generateVariants() {
        const selectedColors = Array.from(colorSelect.selectedOptions).map(option => ({
            id: option.value,
            name: option.text
        }));
        const selectedCapacities = Array.from(capacitySelect.selectedOptions).map(option => ({
            id: option.value,
            name: option.text
        }));

        // Lấy giá trị mặc định
        const defaultPrice = document.getElementById('default_price').value || '';
        const defaultPriceSale = document.getElementById('default_price_sale').value || '';
        const defaultQuantity = document.getElementById('default_quantity').value || '';

        // Xóa nội dung cũ của bảng
        variantsTable.innerHTML = '';

        // Tạo các biến thể mới
        selectedColors.forEach(color => {
            selectedCapacities.forEach(capacity => {
                const row = variantsTable.insertRow();
                const variantIndex = variantsTable.rows.length - 1;

                // Thêm các ô dữ liệu
                row.innerHTML = `
                    <td>
                        ${color.name}
                        <input type="hidden" name="variants[${variantIndex}][color_id]" value="${color.id}">
                    </td>
                    <td>
                        ${capacity.name}
                        <input type="hidden" name="variants[${variantIndex}][capacity_id]" value="${capacity.id}">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="variants[${variantIndex}][price]"
                               value="${defaultPrice}" required min="0">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="variants[${variantIndex}][price_sale]"
                               value="${defaultPriceSale}" min="0">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="variants[${variantIndex}][quantity]"
                               value="${defaultQuantity}" required min="0">
                    </td>
                    <td>
                        <div class="variant-images">
                            <div id="preview-${variantIndex}" class="image-preview-container"></div>
                            <input type="file" name="variants[${variantIndex}][image]"
                                   class="form-control variant-image-input"
                                   accept="image/*"
                                   style="display: none;"
                                   onchange="handleImageUpload(this, ${variantIndex})">
                            <button type="button" class="btn btn-primary btn-sm mt-2 add-image-btn"
                                    data-variant-index="${variantIndex}">
                                Thêm ảnh
                            </button>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm delete-variant">
                            <svg width="18px" height="18px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M10 12V17" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M14 12V17" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M4 7H20" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M6 10V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V10" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                        </button>
                    </td>
                `;
            });
        });

        // Cập nhật lại index cho các input sau khi xóa
        updateInputIndexes();
    }

    // Hàm cập nhật lại index cho các input
    function updateInputIndexes() {
        const rows = variantsTable.getElementsByTagName('tr');
        for (let i = 0; i < rows.length; i++) {
            const inputs = rows[i].getElementsByTagName('input');
            for (let input of inputs) {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/variants\[\d+\]/, `variants[${i}]`));
                }
            }
            // Cập nhật ID của preview container và onchange event
            const previewContainer = rows[i].querySelector('.image-preview-container');
            const imageInput = rows[i].querySelector('.variant-image-input');
            if (previewContainer) {
                previewContainer.id = `preview-${i}`;
            }
            if (imageInput) {
                imageInput.setAttribute('onchange', `handleImageUpload(this, ${i})`);
            }
        }
    }

    // Xử lý xóa biến thể
    variantsTable.addEventListener('click', function(e) {
        if (e.target.closest('.delete-variant')) {
            if (confirm('Bạn có chắc chắn muốn xóa biến thể này?')) {
                const row = e.target.closest('tr');
                row.remove();
                updateInputIndexes();
            }
        }
    });

    // Lắng nghe sự kiện thay đổi của select
    colorSelect.addEventListener('change', generateVariants);
    capacitySelect.addEventListener('change', generateVariants);

    // Lắng nghe sự kiện thay đổi của giá và số lượng mặc định
    document.getElementById('default_price').addEventListener('input', updateExistingVariants);
    document.getElementById('default_price_sale').addEventListener('input', updateExistingVariants);
    document.getElementById('default_quantity').addEventListener('input', updateExistingVariants);

    // Hàm cập nhật giá trị cho các biến thể hiện tại
    function updateExistingVariants() {
        const defaultPrice = document.getElementById('default_price').value;
        const defaultPriceSale = document.getElementById('default_price_sale').value;
        const defaultQuantity = document.getElementById('default_quantity').value;

        // Cập nhật giá cho tất cả biến thể hiện tại
        const priceInputs = variantsTable.querySelectorAll('input[name*="[price]"]');
        priceInputs.forEach(input => {
            if (defaultPrice) {
                input.value = defaultPrice;
            }
        });

        // Cập nhật giá khuyến mãi cho tất cả biến thể hiện tại
        const priceSaleInputs = variantsTable.querySelectorAll('input[name*="[price_sale]"]');
        priceSaleInputs.forEach(input => {
            if (defaultPriceSale) {
                input.value = defaultPriceSale;
            }
        });

        // Cập nhật số lượng cho tất cả biến thể hiện tại
        const quantityInputs = variantsTable.querySelectorAll('input[name*="[quantity]"]');
        quantityInputs.forEach(input => {
            if (defaultQuantity) {
                input.value = defaultQuantity;
            }
        });
    }

    // Xử lý submit form
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Kiểm tra xem có biến thể nào được tạo không
        if (variantsTable.rows.length === 0) {
            alert('Vui lòng chọn ít nhất một màu sắc và một dung lượng!');
            return;
        }

        // Kiểm tra các trường bắt buộc
        const requiredInputs = variantsTable.querySelectorAll('input[required]');
        let isValid = true;

        requiredInputs.forEach(input => {
            if (!input.value) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            alert('Vui lòng điền đầy đủ thông tin cho tất cả các biến thể!');
            return;
        }

        // Nếu mọi thứ hợp lệ, submit form
        form.submit();
    });
});
</script>

<style>
.variant-images {
    position: relative;
    min-height: 100px;
}
.image-preview-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 10px;
    min-height: 50px;
}
.image-preview {
    position: relative;
    display: inline-block;
    margin: 5px;
}
.image-preview img {
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 100px;
    height: 100px;
    object-fit: cover;
}
.image-preview button {
    position: absolute;
    top: -10px;
    right: -10px;
    padding: 2px 6px;
    font-size: 12px;
    z-index: 1;
}
.add-image-btn {
    margin-top: 10px;
}
</style>
@endsection
