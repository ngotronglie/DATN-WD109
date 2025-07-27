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

            {{-- Dropdown Chọn Sản Phẩm --}}
            <div class="mb-3">
                <label for="product_id" class="form-label">Chọn sản phẩm</label>
                <select
                    name="product_id"
                    id="product_id"
                    class="form-control @error('product_id') is-invalid @enderror"
                    onchange="loadVariants(this)" {{-- Gọi hàm mở modal --}}
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
                @error('product_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
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
                            <td>${variant.color?.name || 'Không có'}</td>
                            <td>${variant.capacity?.name || 'Không có'}</td>

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
    function saveVariants() {
        // Lấy danh sách các biến thể được chọn từ modal
        const rows = document.querySelectorAll('#variantsList tr');
        let selectedVariants = [];

        rows.forEach(row => {
            // Tìm các trường dữ liệu tương ứng trong từng dòng
            const checkbox = row.querySelector('input[type="checkbox"]');
            if (checkbox && checkbox.checked) { // Chỉ lưu nếu checkbox được chọn
                const variantId = checkbox.getAttribute('name').match(/\[(\d+)\]/)[1];
                const flashPriceInput = row.querySelector('input[name^="variants"]');
                const quantityLimitInput = row.querySelector('input[type="number"][name$="[quantity_limit]"]');

                selectedVariants.push({
                    variant_id: variantId,
                    flash_price: flashPriceInput?.value || null,
                    quantity_limit: quantityLimitInput?.value || null
                });
            }
        });

        // Kiểm tra nếu chưa chọn biến thể nào
        if (selectedVariants.length === 0) {
            alert('Vui lòng chọn ít nhất một biến thể!');
            return;
        }

        console.log('Selected Variants:', selectedVariants);

        // OPTIONAL: Gửi dữ liệu đến form hoặc xử lý lưu tại đây
        // Ví dụ: Lưu vào một hidden input để gửi cùng form
    }
</script>
@endsection
