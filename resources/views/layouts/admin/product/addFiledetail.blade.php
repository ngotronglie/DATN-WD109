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
                    <form action="{{ route('admin.products.updateImages', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            @foreach($product->variants as $variant)
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Biến thể: {{ $variant->name }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Ảnh hiện tại:</label>
                                            <div class="row">
                                                @foreach($variant->images as $image)
                                                <div class="col-4 mb-2">
                                                    <div class="position-relative">
                                                        <img src="{{ asset('storage/' . $image->image) }}" class="img-fluid" alt="Variant Image">
                                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" 
                                                                onclick="deleteImage({{ $variant->id }}, {{ $image->id }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Thêm ảnh mới:</label>
                                            <input type="file" name="images[{{ $variant->id }}][]" class="form-control" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="text-end mt-3">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteImage(variantId, imageId) {
    if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
        fetch(`/admin/products/variants/${variantId}/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra khi xóa ảnh');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa ảnh');
        });
    }
}
</script>
@endpush

@endsection
