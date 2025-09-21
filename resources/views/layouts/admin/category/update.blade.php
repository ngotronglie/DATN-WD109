@extends('index.admindashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Cập nhật danh mục</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.categories.update', $category->ID) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="Name">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('Name') is-invalid @enderror"
                                id="Name" name="Name" value="{{ old('Name', $category->Name) }}" required>
                            @error('Name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <label for="Parent_id">Danh mục cha</label>
                            <select class="form-control @error('Parent_id') is-invalid @enderror"
                                id="Parent_id" name="Parent_id">
                                <option value="">Chọn danh mục cha</option>
                                @foreach($categories as $category)
                                    @if($category->ID != $category->ID)
                                        <option value="{{ $category->ID }}"
                                            {{ old('Parent_id', $category->Parent_id) == $category->ID ? 'selected' : '' }}>
                                            {{ $category->Name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('Parent_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Nếu không chọn, danh mục này sẽ là danh mục gốc</small>
                        </div>

                        <div class="form-group mt-3">
                            <label for="Is_active">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-control @error('Is_active') is-invalid @enderror"
                                id="Is_active" name="Is_active" required>
                                <option value="">Chọn trạng thái</option>
                                <option value="1" {{ old('Is_active', $category->Is_active) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('Is_active', $category->Is_active) == 0 ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                            @error('Is_active')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <label for="Image">Hình ảnh <span class="text-danger">*</span></label>
                            @if($category->Image)
                                <div class="mb-2">
                                    <img src="{{ asset($category->Image) }}" alt="Current Image" style="max-width: 200px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('Image') is-invalid @enderror"
                                id="Image" name="Image" accept="image/*">
                            @error('Image')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
