@extends('index.admindashboard')

@section('content')
<div class="page-content ms-0 px-0">
    <div class="container-fluid  " >
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Chỉnh sửa Banner</h5>
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
                        </div>

                        <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu đề</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $banner->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" required>{{ old('description', $banner->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="img" class="form-label">Hình ảnh</label>
                                @if($banner->img)
                                    <div class="mb-2">
                                        <img src="{{ asset('images/banners/' . $banner->img) }}" 
                                             alt="{{ $banner->title }}" style="max-width: 200px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('img') is-invalid @enderror" 
                                       id="img" name="img">
                                <small class="form-text text-muted">Để trống nếu muốn giữ ảnh hiện tại</small>
                                @error('img')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" 
                                           name="is_active" value="1" 
                                           {{ $banner->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Kích hoạt</label>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Cập nhật Banner</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 