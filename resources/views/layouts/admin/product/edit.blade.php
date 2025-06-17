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
                        <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Tên sản phẩm</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $product->product_name) }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="category_id">Danh mục</label>
                                <select class="form-control @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id" required>
                                    <option value="">Chọn danh mục</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $product->categories_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->Name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="color_id">Màu sắc</label>
                                <select class="form-control @error('color_id') is-invalid @enderror" id="color_id"
                                    name="color_id" required>
                                    <option value="">Chọn màu sắc</option>
                                    <option value="1" {{ old('color_id', $product->color_id) == 1 ? 'selected' : '' }}>
                                        Đen</option>
                                    <option value="2"
                                        {{ old('color_id', $product->color_id) == 2 ? 'selected' : '' }}>Trắng</option>
                                    <option value="3"
                                        {{ old('color_id', $product->color_id) == 3 ? 'selected' : '' }}>Vàng</option>
                                    <option value="4"
                                        {{ old('color_id', $product->color_id) == 4 ? 'selected' : '' }}>Xanh</option>
                                    <option value="5"
                                        {{ old('color_id', $product->color_id) == 5 ? 'selected' : '' }}>Đỏ</option>
                                </select>
                                @error('color_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="capacity_id">Dung lượng</label>
                                <select class="form-control @error('capacity_id') is-invalid @enderror" id="capacity_id"
                                    name="capacity_id" required>
                                    <option value="">Chọn dung lượng</option>
                                    <option value="1"
                                        {{ old('capacity_id', $product->capacity_id) == 1 ? 'selected' : '' }}>128GB
                                    </option>
                                    <option value="2"
                                        {{ old('capacity_id', $product->capacity_id) == 2 ? 'selected' : '' }}>256GB
                                    </option>
                                    <option value="3"
                                        {{ old('capacity_id', $product->capacity_id) == 3 ? 'selected' : '' }}>512GB
                                    </option>
                                    <option value="4"
                                        {{ old('capacity_id', $product->capacity_id) == 4 ? 'selected' : '' }}>1TB</option>
                                </select>
                                @error('capacity_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="price">Giá</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="price_sale">Giá khuyến mãi</label>
                                <input type="number" class="form-control @error('price_sale') is-invalid @enderror"
                                    id="price_sale" name="price_sale"
                                    value="{{ old('price_sale', $product->price_sale) }}">
                                @error('price_sale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="quantity">Số lượng</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                    id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}"
                                    required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Kích hoạt</label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Ảnh sản phẩm</label>
                                <div class="current-images" id="imageDropZone">
                                    @if ($product->images)
                                        @foreach ($product->images as $image)
                                            <div class="image-container" data-image-id="{{ $image['id'] }}">
                                                <img src="{{ asset('storage/' . $image['path']) }}" alt="Product Image"
                                                    class="img-thumbnail"
                                                    style="width: 150px; height: 150px; object-fit: cover;">
                                                <div class="image-actions">
                                                    <button type="button" class="btn btn-danger btn-sm delete-image">
                                                        Xóa
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">Không có ảnh</p>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-info btn-sm" id="addImagesBtn">
                                        Thêm ảnh
                                    </button>
                                    <input type="file" id="images" name="images[]" multiple accept="image/*"
                                        style="display: none;">
                                    @error('images.*')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .current-images {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
            min-height: 180px;
            padding: 20px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .current-images.drag-over {
            border-color: #007bff;
            background-color: rgba(0, 123, 255, 0.1);
            transform: scale(1.01);
        }

        .image-container {
            position: relative;
            display: inline-block;
            margin: 5px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .image-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: none;
            z-index: 1;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .image-container:hover .image-actions {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .image-container img {
            transition: all 0.3s ease;
            border: 1px solid #ddd;
        }

        .image-container:hover img {
            transform: scale(1.05);
            border-color: #007bff;
        }

        .image-actions .btn {
            padding: 5px 12px;
            font-size: 13px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .image-actions .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        #addImagesBtn {
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        #addImagesBtn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>

@endsection

@section('scripts')
    <script>
        // Khởi tạo biến toàn cục để theo dõi trạng thái
        let imageState = {
            existingImages: new Set(),
            deletedImages: new Set(),
            newImages: []
        };

        document.addEventListener('DOMContentLoaded', function() {
            // Khởi tạo danh sách ảnh hiện có
            document.querySelectorAll('.image-container').forEach(container => {
                const imageId = container.dataset.imageId;
                if (imageId) {
                    imageState.existingImages.add(imageId);
                }
            });

            // Xử lý nút thêm ảnh
            const addImagesBtn = document.getElementById('addImagesBtn');
            const imagesInput = document.getElementById('images');
            const dropZone = document.getElementById('imageDropZone');

            if (addImagesBtn && imagesInput) {
                addImagesBtn.onclick = function() {
                    imagesInput.click();
                };

                imagesInput.onchange = function(e) {
                    if (this.files.length > 0) {
                        handleNewImages(this.files);
                        this.value = ''; // Reset input
                    }
                };
            }

            // Xử lý xóa ảnh
            document.querySelectorAll('.delete-image').forEach(button => {
                button.onclick = function() {
                    const container = this.closest('.image-container');
                    const imageId = container.dataset.imageId;

                    if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
                        // Thêm vào danh sách ảnh cần xóa
                        if (imageId) {
                            imageState.deletedImages.add(imageId);
                        }

                        // Xóa khỏi UI
                        container.style.opacity = '0';
                        container.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            container.remove();
                            updateEmptyState();
                        }, 300);
                    }
                };
            });

            // Xử lý kéo thả
            if (dropZone) {
                dropZone.ondragover = function(e) {
                    e.preventDefault();
                    this.classList.add('drag-over');
                };

                dropZone.ondragleave = function(e) {
                    e.preventDefault();
                    this.classList.remove('drag-over');
                };

                dropZone.ondrop = function(e) {
                    e.preventDefault();
                    this.classList.remove('drag-over');
                    if (e.dataTransfer.files.length > 0) {
                        handleNewImages(e.dataTransfer.files);
                    }
                };
            }
        });

        // Hàm xử lý ảnh mới
        function handleNewImages(files) {
            const dropZone = document.getElementById('imageDropZone');
            const noImagesMsg = dropZone.querySelector('.text-muted');
            if (noImagesMsg) {
                noImagesMsg.remove();
            }

            Array.from(files).forEach(file => {
                if (!file.type.startsWith('image/')) {
                    alert(`File ${file.name} không phải là ảnh hợp lệ.`);
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    alert(`File ${file.name} quá lớn. Kích thước tối đa là 5MB.`);
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = document.createElement('div');
                    container.className = 'image-container';
                    container.innerHTML = `
                <img src="${e.target.result}"
                     class="img-thumbnail"
                     style="width: 150px; height: 150px; object-fit: cover;">
                <div class="image-actions">
                    <button type="button" class="btn btn-danger btn-sm remove-preview">
                        Xóa
                    </button>
                </div>
            `;

                    // Thêm sự kiện xóa cho ảnh preview
                    const removeBtn = container.querySelector('.remove-preview');
                    removeBtn.onclick = function() {
                        container.style.opacity = '0';
                        container.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            container.remove();
                            updateEmptyState();
                        }, 300);
                    };

                    dropZone.appendChild(container);
                    imageState.newImages.push(file);
                };

                reader.readAsDataURL(file);
            });
        }

        // Hàm cập nhật trạng thái trống
        function updateEmptyState() {
            const dropZone = document.getElementById('imageDropZone');
            if (dropZone.querySelectorAll('.image-container').length === 0) {
                dropZone.innerHTML = '<p class="text-muted">Không có ảnh</p>';
            }
        }

        // Hàm chuẩn bị dữ liệu trước khi submit form
        document.querySelector('form').onsubmit = function(e) {
            // Thêm các input hidden cho ảnh cần xóa
            imageState.deletedImages.forEach(imageId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_images[]';
                input.value = imageId;
                this.appendChild(input);
            });
        };
    </script>
@endsection
