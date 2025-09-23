@extends('index.admindashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Quản lý ảnh sản phẩm</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.products.updateImages', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @foreach($product->variants as $variant)
                        <div class="variant-images mb-4 p-3 border rounded">
                            <h5 class="mb-3">Biến thể: {{ $variant->color->name }} - {{ $variant->capacity->name }}</h5>
                            
                            <div class="current-images mb-4">
                                <h6 class="mb-3">Ảnh hiện tại:</h6>
                                <div class="row" id="variant-images-{{ $variant->id }}">
                                    @foreach($variant->images as $image)
                                    <div class="col-md-3 mb-3 image-item" data-image-id="{{ $image->id }}">
                                        <div class="card h-100">
                                            <img src="{{ asset('storage/' . $image->image) }}" 
                                                 class="card-img-top" 
                                                 alt="Product Image"
                                                 style="height: 200px; object-fit: cover;">
                                            <div class="card-body text-center">
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm delete-image" 
                                                        data-variant-id="{{ $variant->id }}" 
                                                        data-image-id="{{ $image->id }}">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="new-images">
                                <h6 class="mb-3">Thêm ảnh mới:</h6>
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" 
                                               class="custom-file-input" 
                                               id="variant-images-{{ $variant->id }}"
                                               name="variants[{{ $variant->id }}][images][]" 
                                               accept="image/jpeg,image/png,image/jpg,image/gif"
                                               multiple>
                                        <label class="custom-file-label" for="variant-images-{{ $variant->id }}">
                                            Chọn ảnh...
                                        </label>
                                    </div>
                                    <input type="hidden" name="variants[{{ $variant->id }}][id]" value="{{ $variant->id }}">
                                    <small class="form-text text-muted mt-2">
                                        Chọn một hoặc nhiều ảnh. Định dạng: JPEG, PNG, JPG, GIF. Kích thước tối đa: 2MB
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật ảnh
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.custom-file-label::after {
    content: "Chọn file";
}
.variant-images {
    background-color: #f8f9fa;
}
.card-img-top {
    border-bottom: 1px solid #dee2e6;
}
.image-item {
    transition: opacity 0.3s ease;
}
</style>
@endpush

@push('scripts')
<script>
(function() {
    // Đợi DOM load xong
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initImageHandlers);
    } else {
        initImageHandlers();
    }

    function initImageHandlers() {
        // Hiển thị tên file đã chọn
        document.querySelectorAll('.custom-file-input').forEach(function(input) {
            input.addEventListener('change', function() {
                let fileName = '';
                if (this.files && this.files.length > 1) {
                    fileName = this.files.length + ' files selected';
                } else if (this.files && this.files[0]) {
                    fileName = this.files[0].name;
                }
                this.nextElementSibling.textContent = fileName;
            });
        });

        // Xử lý xóa ảnh
        document.querySelectorAll('.delete-image').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (!confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
                    return;
                }

                const variantId = this.dataset.variantId;
                const imageId = this.dataset.imageId;
                const imageContainer = this.closest('.image-item');

                if (!imageContainer) {
                    console.error('Image container not found');
                    return;
                }

                // Disable button while processing
                this.disabled = true;

                // Gửi request xóa ảnh
                const xhr = new XMLHttpRequest();
                xhr.open('DELETE', `/admin/products/variants/${variantId}/images/${imageId}`, true);
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.onload = function() {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Fade out và xóa element
                            imageContainer.style.opacity = '0';
                            setTimeout(() => {
                                if (imageContainer && imageContainer.parentNode) {
                                    imageContainer.parentNode.removeChild(imageContainer);
                                }
                            }, 300);
                            alert('Xóa ảnh thành công!');
                        } else {
                            alert('Có lỗi xảy ra khi xóa ảnh: ' + (response.message || 'Không xác định'));
                            button.disabled = false;
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        alert('Có lỗi xảy ra khi xóa ảnh. Vui lòng thử lại sau.');
                        button.disabled = false;
                    }
                };

                xhr.onerror = function() {
                    console.error('Network error occurred');
                    alert('Có lỗi xảy ra khi xóa ảnh. Vui lòng thử lại sau.');
                    button.disabled = false;
                };

                xhr.send();
            });
        });

        // Kiểm tra kích thước file trước khi upload
        document.querySelectorAll('.custom-file-input').forEach(function(input) {
            input.addEventListener('change', function() {
                const files = this.files;
                const maxSize = 2 * 1024 * 1024; // 2MB
                let hasError = false;

                for (let i = 0; i < files.length; i++) {
                    if (files[i].size > maxSize) {
                        alert('File ' + files[i].name + ' vượt quá kích thước cho phép (2MB)');
                        this.value = '';
                        this.nextElementSibling.textContent = 'Chọn ảnh...';
                        hasError = true;
                        break;
                    }
                }

                if (!hasError && files.length > 0) {
                    // Preview images
                    const previewContainer = this.closest('.variant-images').querySelector('.current-images .row');
                    if (previewContainer) {
                        for (let i = 0; i < files.length; i++) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const previewHtml = `
                                    <div class="col-md-3 mb-3 image-item">
                                        <div class="card h-100">
                                            <img src="${e.target.result}" class="card-img-top" alt="Preview" style="height: 200px; object-fit: cover;">
                                            <div class="card-body text-center">
                                                <span class="badge badge-info">Ảnh mới</span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                previewContainer.insertAdjacentHTML('beforeend', previewHtml);
                            }
                            reader.readAsDataURL(files[i]);
                        }
                    }
                }
            });
        });
    }
})();
</script>
@endpush
@endsection