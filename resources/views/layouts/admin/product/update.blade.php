@extends('index.admindashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Cập nhật sản phẩm</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" id="productForm">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
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

                                <div class="form-group">
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
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h4>Biến thể sản phẩm</h4>
                                <div id="variants-container">
                                    @foreach($product->variants as $index => $variant)
                                    <div class="variant-item border p-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Màu sắc <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="variants[{{ $index }}][color_id]" required>
                                                        <option value="">Chọn màu</option>
                                                        @foreach($colors as $color)
                                                            <option value="{{ $color->id }}" 
                                                                {{ $variant->color_id == $color->id ? 'selected' : '' }}>
                                                                {{ $color->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Dung lượng <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="variants[{{ $index }}][capacity_id]" required>
                                                        <option value="">Chọn dung lượng</option>
                                                        @foreach($capacities as $capacity)
                                                            <option value="{{ $capacity->id }}" 
                                                                {{ $variant->capacity_id == $capacity->id ? 'selected' : '' }}>
                                                                {{ $capacity->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Giá <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" 
                                                           name="variants[{{ $index }}][price]" 
                                                           value="{{ $variant->price }}" required min="0">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Giá khuyến mãi</label>
                                                    <input type="number" class="form-control" 
                                                           name="variants[{{ $index }}][price_sale]" 
                                                           value="{{ $variant->price_sale }}" min="0">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label>Số lượng <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" 
                                                           name="variants[{{ $index }}][quantity]" 
                                                           value="{{ $variant->quantity }}" required min="0">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-block remove-variant">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-info" id="add-variant">
                                    <i class="fas fa-plus"></i> Thêm biến thể
                                </button>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        let variantCount = {{ count($product->variants) }};

        $('#add-variant').click(function() {
            const template = `
                <div class="variant-item border p-3 mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Màu sắc <span class="text-danger">*</span></label>
                                <select class="form-control" name="variants[${variantCount}][color_id]" required>
                                    <option value="">Chọn màu</option>
                                    @foreach($colors as $color)
                                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Dung lượng <span class="text-danger">*</span></label>
                                <select class="form-control" name="variants[${variantCount}][capacity_id]" required>
                                    <option value="">Chọn dung lượng</option>
                                    @foreach($capacities as $capacity)
                                        <option value="{{ $capacity->id }}">{{ $capacity->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Giá <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" 
                                       name="variants[${variantCount}][price]" required min="0">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Giá khuyến mãi</label>
                                <input type="number" class="form-control" 
                                       name="variants[${variantCount}][price_sale]" min="0">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Số lượng <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" 
                                       name="variants[${variantCount}][quantity]" required min="0">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-block remove-variant">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('#variants-container').append(template);
            variantCount++;
        });

        $(document).on('click', '.remove-variant', function() {
            $(this).closest('.variant-item').remove();
        });
    });
</script>
@endpush
@endsection
