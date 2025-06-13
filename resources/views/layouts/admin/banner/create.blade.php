@extends('index.admindashboard')

@section('content')
<div class="page-content ms-0 px-0">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Thêm Banner Mới</h5>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu Đề</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô Tả</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="img" class="form-label">Hình Ảnh</label>
                                <input type="file" class="form-control" id="img" name="img" accept="image/*" required>
                                <small class="text-muted">Định dạng: JPEG, PNG, JPG, GIF. Kích thước tối đa: 2MB</small>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Trạng thái hoạt động</label>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Lưu</button>
                                <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 