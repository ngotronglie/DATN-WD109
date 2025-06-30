@extends('layouts.admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Chỉnh sửa Bài viết</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.blogs.index') }}">Bài viết</a></li>
                        <li class="breadcrumb-item active">Chỉnh sửa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Chỉnh sửa: {{ $blog->slug }}</h5>
                </div>
                <div class="card-body">
                    <form id="edit-blog-form" action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for="slug" class="form-label">Slug (Tiêu đề) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" id="slug" value="{{ old('slug', $blog->slug) }}" placeholder="Nhập slug">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Ảnh đại diện</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image">
                                    @if($blog->image)
                                        <div class="mt-2">
                                            <img src="{{ $blog->image }}" alt="{{ $blog->slug }}" width="150">
                                        </div>
                                    @endif
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
                                    <div id="snow-editor" style="height: 300px;">{!! old('content', $blog->content) !!}</div>
                                    <input type="hidden" name="content" id="content-input">
                                    @error('content')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="tag_ids" class="form-label">Tags</label>
                                    <select class="form-control" data-choices data-choices-multiple-remove="true" id="tag_ids" name="tag_ids[]" multiple>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" {{ in_array($tag->id, $blog->tags->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                {{ $tag->name_tag }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ $blog->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Kích hoạt</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">Hủy</a>
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

@section('script')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script>
document.addEventListener("DOMContentLoaded", function() {
    if (document.querySelector("#snow-editor")) {
        var quill = new Quill("#snow-editor", {
            theme: "snow"
        });

        var form = document.querySelector('#edit-blog-form');
        form.onsubmit = function() {
            var content = document.querySelector('#snow-editor .ql-editor').innerHTML;
            document.querySelector('#content-input').value = content;
        };
    }
});
</script>
@endsection 