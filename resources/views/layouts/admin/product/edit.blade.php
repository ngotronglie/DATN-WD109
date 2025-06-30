@extends('index.admindashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chỉnh sửa sản phẩm</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product->slug) }}" method="POST" id="productForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="form-group">
                                <label for="name">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $product->name) }}" required>
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
                                                {{ old('categories_id', $product->categories_id) == $category->ID ? 'selected' : '' }}>
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
                                        <option value="1" {{ old('is_active', $product->is_active) == '1' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ old('is_active', $product->is_active) == '0' ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                                            <option value="{{ $color->id }}" selected>{{ $color->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Dung lượng <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="capacity-select" data-choices data-choices-removeItem multiple>
                                                        @foreach($capacities as $capacity)
                                                            <option value="{{ $capacity->id }}" selected>{{ $capacity->name }}</option>
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
                                    <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
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

    // Dữ liệu biến thể hiện tại
    const existingVariants = @json($product->variants);

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

        // Xóa nội dung cũ của bảng
        variantsTable.innerHTML = '';

        // Tạo các biến thể mới
        selectedColors.forEach(color => {
            selectedCapacities.forEach(capacity => {
                const row = variantsTable.insertRow();
                const variantIndex = variantsTable.rows.length - 1;

                // Tìm biến thể hiện tại nếu có
                const existingVariant = existingVariants.find(v =>
                    v.color_id == color.id && v.capacity_id == capacity.id
                );

                // Thêm các ô dữ liệu
                row.innerHTML = `
                    <td>
                        ${color.name}
                        <input type="hidden" name="variants[${variantIndex}][color_id]" value="${color.id}">
                        ${existingVariant ? `<input type="hidden" name="variants[${variantIndex}][variant_id]" value="${existingVariant.id}">` : ''}
                    </td>
                    <td>
                        ${capacity.name}
                        <input type="hidden" name="variants[${variantIndex}][capacity_id]" value="${capacity.id}">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="variants[${variantIndex}][price]"
                               value="${existingVariant ? existingVariant.price : ''}" required min="0">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="variants[${variantIndex}][price_sale]"
                               value="${existingVariant ? (existingVariant.price_sale || '') : ''}" min="0">
                    </td>
                    <td>
                        <input type="number" class="form-control" name="variants[${variantIndex}][quantity]"
                               value="${existingVariant ? existingVariant.quantity : ''}" required min="0">
                    </td>
                    <td>
                        <div class="variant-images">
                            <div id="preview-${variantIndex}" class="image-preview-container">
                                ${existingVariant && existingVariant.image ? `
                                    <div class="image-preview">
                                        <img src="${existingVariant.image}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                        <button type="button" class="btn btn-danger btn-sm mt-1 remove-image"
                                                onclick="removeImage(this, ${variantIndex})">
                                            Xóa
                                        </button>
                                    </div>
                                ` : ''}
                            </div>
                            <input type="file" name="variants[${variantIndex}][image]"
                                   class="form-control variant-image-input"
                                   accept="image/*"
                                   style="display: none;"
                                   onchange="handleImageUpload(this, ${variantIndex})">
                            <button type="button" class="btn btn-primary btn-sm mt-2 add-image-btn"
                                    data-variant-index="${variantIndex}">
                                upload ảnh
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

    // Hàm set selected attribute trực tiếp cho options
    function setSelectedOptions() {
        const currentColors = [...new Set(existingVariants.map(v => v.color_id))];
        const currentCapacities = [...new Set(existingVariants.map(v => v.capacity_id))];

        // Set selected cho color options
        Array.from(colorSelect.options).forEach(option => {
            if (currentColors.includes(parseInt(option.value))) {
                option.setAttribute('selected', 'selected');
            } else {
                option.removeAttribute('selected');
            }
        });

        // Set selected cho capacity options
        Array.from(capacitySelect.options).forEach(option => {
            if (currentCapacities.includes(parseInt(option.value))) {
                option.setAttribute('selected', 'selected');
            } else {
                option.removeAttribute('selected');
            }
        });
    }

    // Khởi tạo form với dữ liệu hiện tại
    function initializeForm() {
        console.log('Initializing form with existing variants:', existingVariants);

        // Lấy tất cả màu sắc và dung lượng hiện tại
        const currentColors = [...new Set(existingVariants.map(v => v.color_id))];
        const currentCapacities = [...new Set(existingVariants.map(v => v.capacity_id))];

        console.log('Current colors:', currentColors);
        console.log('Current capacities:', currentCapacities);

        // Set selected attributes trực tiếp
        setSelectedOptions();

        // Chọn các option tương ứng
        currentColors.forEach(colorId => {
            const option = colorSelect.querySelector(`option[value="${colorId}"]`);
            if (option) {
                option.selected = true;
                console.log('Selected color:', colorId, option.text);
            } else {
                console.log('Color option not found:', colorId);
            }
        });

        currentCapacities.forEach(capacityId => {
            const option = capacitySelect.querySelector(`option[value="${capacityId}"]`);
            if (option) {
                option.selected = true;
                console.log('Selected capacity:', capacityId, option.text);
            } else {
                console.log('Capacity option not found:', capacityId);
            }
        });

        // Trigger change event để cập nhật choices library nếu có
        if (typeof Choices !== 'undefined') {
            console.log('Choices library detected');
            // Nếu sử dụng Choices library, cần cập nhật instance
            if (colorSelect.choices) {
                colorSelect.choices.setChoiceByValue(currentColors);
                console.log('Updated color choices');
            }
            if (capacitySelect.choices) {
                capacitySelect.choices.setChoiceByValue(currentCapacities);
                console.log('Updated capacity choices');
            }
        } else {
            // Nếu không có Choices library, trigger change event thủ công
            console.log('No Choices library, triggering manual change events');
            const colorEvent = new Event('change', { bubbles: true });
            const capacityEvent = new Event('change', { bubbles: true });
            colorSelect.dispatchEvent(colorEvent);
            capacitySelect.dispatchEvent(capacityEvent);
        }

        // Tạo bảng biến thể
        generateVariants();

        console.log('Form initialization completed');
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

    // Khởi tạo form khi trang load
    // Đợi một chút để đảm bảo choices library đã load xong
    setTimeout(initializeForm, 100);

    // Thêm một lần thử lại sau 500ms để đảm bảo hoạt động
    setTimeout(function() {
        // Kiểm tra xem có option nào được selected chưa
        const selectedColors = colorSelect.selectedOptions.length;
        const selectedCapacities = capacitySelect.selectedOptions.length;

        if (selectedColors === 0 || selectedCapacities === 0) {
            console.log('Retrying form initialization...');
            initializeForm();
        }
    }, 500);
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
