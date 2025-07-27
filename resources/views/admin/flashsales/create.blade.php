@extends('layouts.admin.index')

@section('content')
    <div class="container">
        <h1>Tạo mới Flash Sale</h1>
        <form action="{{ route('admin.flash-sales.store') }}" method="POST">
            @csrf {{-- Token bảo mật --}}

            {{-- Tên chương trình khuyến mãi --}}
            <div class="mb-3">
                <label for="name" class="form-label">Tên chương trình khuyến mãi</label>
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="name"
                    name="name"
                    placeholder="Nhập tên chương trình khuyến mãi"
                    value="{{ old('name') }}"
                    required
                >
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
    <label class="form-label">Chọn sản phẩm</label>
    <select
        name="product_id"
        id="product_id"
        class="form-control @error('product_id') is-invalid @enderror"
        onchange="loadVariants(this)"
        required
    >
        <option value="">-- Chọn sản phẩm --</option>
        @foreach($products as $product)
            <option
                value="{{ $product->id }}"
                data-variants="{{ json_encode($product->variants) }}"
            >
                {{ $product->name }}
            </option>
        @endforeach
    </select>

    {{-- Thêm khu vực hiển thị tiêu đề và biến thể đã chọn --}}

</div>

            {{-- Button Action --}}
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Lưu</button>
                <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>

    {{-- Modal Hiển Thị Danh Sách Biến Thể --}}
    <div class="modal fade" id="variantsModal" tabindex="-1" aria-labelledby="variantsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="variantsModalLabel">Danh sách biến thể</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Danh sách các biến thể --}}
                    <table class="table">
                        <thead>
                        <tr>
                            <th>️Ảnh</th>
                            <th>Màu sắc</th>
                            <th>Dung lượng</th>
                            <th>Giá gốc</th>
                            <th>Giá Flash Sale</th>
                            <th>Tồn kho hiện tại</th>
                            <th>Số lượng Flash Sale</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody id="variantsList">
                            {{-- Nội dung của các biến thể (được thêm qua JS) --}}
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="saveVariants()">Lưu</button>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        function loadVariants(selectElement) {
            const modal = new bootstrap.Modal(document.getElementById('variantsModal'));
            const variantsData = selectElement.options[selectElement.selectedIndex].getAttribute('data-variants');
            const variantsList = document.getElementById('variantsList');

            if (variantsData) {
                const variants = JSON.parse(variantsData);
                variantsList.innerHTML = ''; // Xóa danh sách cũ
                if (variants.length) {
                    variants.forEach(variant => {
                        const listRow = document.createElement('tr');

                        listRow.innerHTML = `
<td>
                                ${variant.image ? `<img src="${variant.image}" alt="Variant Image" style="width: 50px; height: 50px; object-fit: cover;">` : 'N/A'}
                            </td>
                            <td class="variant-color">${variant.color?.name || 'Không có'}</td>
                            <td class="variant-capacity">${variant.capacity?.name || 'Không có'}</td>
                            <td>${variant.price} VND</td>
                            <td>
                                <input type="number" name="variants[${variant.id}][flash_price]" class="form-control" placeholder="Giá khuyến mãi">
                            </td>
                            <td>${variant.quantity || 0}</td>
                            <td>
                                <input type="number" name="variants[${variant.id}][quantity_limit]" class="form-control" placeholder="Số lượng Flash Sale">
                            </td>
                            <td>
                                <input type="checkbox" name="variants[${variant.id}][include]" value="1">
                            </td>
                        `;
                        variantsList.appendChild(listRow);
                    });
                } else {
                    variantsList.innerHTML = '<tr><td colspan="9">Không có biến thể nào.</td></tr>';
                }
                modal.show(); // Mở modal
            } else {
                variantsList.innerHTML = '<tr><td colspan="7">Không có biến thể nào.</td></tr>';
            }
        }
    </script>
    <script>
    let selectedVariants = {}; // Để lưu các biến thể đã chọn

function saveVariants() {
    const modalVariantsList = document.querySelectorAll('#variantsList tr'); // Phần tử trong modal
    const productId = document.getElementById('product_id').value; // ID sản phẩm đang chọn

    // Kiểm tra nếu danh sách selectedVariants chưa tồn tại, tạo mới
    if (!selectedVariants[productId]) {
        selectedVariants[productId] = [];
    }

    modalVariantsList.forEach(row => {
        const checkbox = row.querySelector('input[type="checkbox"]');
        if (checkbox && checkbox.checked) { // Chỉ lấy nếu checkbox được chọn
            const variantId = checkbox.getAttribute('name').match(/\[(\d+)\]/)[1];
            const flashPrice = row.querySelector('input[name^="variants"][name$="[flash_price]"]').value;
            const quantityLimit = row.querySelector('input[name^="variants"][name$="[quantity_limit]"]').value;

            // Thêm thông tin Ảnh, Màu sắc, Dung lượng
            const image = row.querySelector('img')?.getAttribute('src');
            const color = row.querySelector('.variant-color')?.textContent.trim() || 'Không có';
            const capacity = row.querySelector('.variant-capacity')?.textContent.trim() || 'Không có';

            // Kiểm tra nếu biến thể đã tồn tại
            const exists = selectedVariants[productId].some(variant => variant.id === variantId);
            if (!exists) {
                selectedVariants[productId].push({
                    id: variantId,
                    flashPrice: flashPrice || '0',
                    quantityLimit: quantityLimit || '0',
                    image: image,
                    color: color,
                    capacity: capacity,
                });
            }
        }
    });

    // In dữ liệu ra để kiểm tra
    console.log('Selected Variants:', selectedVariants);

    // Gọi hàm hiển thị
    renderSelectedVariants(productId);

    // Đóng modal
    bootstrap.Modal.getInstance(document.getElementById('variantsModal')).hide();
}
</script>
<script>
function renderSelectedVariants(productId) {
    const container = document.getElementById('selectedVariantsContainer');
    container.innerHTML = ''; // Xóa nội dung cũ

    if (selectedVariants[productId] && selectedVariants[productId].length > 0) {
        // Tạo bảng mới
        let table = `
            <h5 class="mb-2">Biến thể đã chọn</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Màu sắc</th>
                        <th>Dung lượng</th>
                        <th>Giá Flash Sale</th>
                        <th>Số lượng Flash Sale</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
        `;

        // Duyệt qua từng biến thể trong danh sách
        selectedVariants[productId].forEach((variant, index) => {
            table += `
                <tr>
                    <td>
                        <img src="${variant.image || '#'}" alt="Ảnh" style="width: 50px; height: 50px;" />
                    </td>
                    <td>${variant.color || 'Không có'}</td>
                    <td>${variant.capacity || 'Không có'}</td>
                    <td>${variant.flashPrice || '0'} VND</td>
                    <td>${variant.quantityLimit || '0'}</td>
                    <td>
                        <button
                            class="btn btn-danger btn-sm"
                            onclick="removeVariant(${productId}, ${index})"
                        >
                            Xóa
                        </button>
                    </td>
                </tr>
            `;
        });

        table += `
                </tbody>
            </table>
        `;

        container.innerHTML = table;
    } else {
        container.innerHTML = '<p>Chưa có biến thể nào được chọn.</p>';
    }
}
</script>
<style>
#selectedVariantsContainer h5 {
    font-size: 1.2rem;
    font-weight: bold;
    color: #333;
}

#selectedVariantsContainer table {
    width: 100%;
    background-color: #fff;
    border-collapse: collapse;
    margin-top: 10px;
}

#selectedVariantsContainer table th,
#selectedVariantsContainer table td {
    text-align: center;
    padding: 10px;
    border: 1px solid #dee2e6;
}

#selectedVariantsContainer table th {
    background-color: #f8f9fa;
    font-weight: bold;
}

#selectedVariantsContainer img {
    border-radius: 5px;
    object-fit: cover;
}

.variant-item {
    background-color: #f8f9fa;
    border-radius: 5px;
}

.variant-item:not(:last-child) {
    margin-bottom: 8px;
}
</style>
<div class="mt-3" id="selectedVariantsContainer">
    <!-- Bảng hiển thị sẽ được cập nhật từ JavaScript -->
</div>
<script>
function removeVariant(productId, variantIndex) {
    // Xóa biến thể khỏi danh sách
    selectedVariants[productId].splice(variantIndex, 1);

    // Cập nhật lại bảng hiển thị
    renderSelectedVariants(productId);
}
</script>
@endsection
