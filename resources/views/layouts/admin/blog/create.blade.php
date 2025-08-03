@extends('layouts.admin.index')

@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Thêm bài viết mới</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.blogs.index') }}">Bài viết</a>
                        </li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thêm bài viết mới</h5>
                </div>
                <div class="card-body">
                    <form id="create-blog-form" action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div class="form-group mb-3">
                                    <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" id="slug" value="{{ old('slug') }}" placeholder="Nhập slug cho bài viết">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Ảnh đại diện</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
                                    <div id="snow-editor" style="height: 300px;">{!! old('content') !!}</div>
                                    <input type="hidden" name="content" id="content-input" value="{{ old('content') }}">
                                    @error('content')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="tag_ids" class="form-label">Tags</label>
                                    <select class="form-control" data-choices data-choices-multiple-remove="true" id="tag_ids" name="tag_ids[]" multiple>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tag_ids', [])) ? 'selected' : '' }}>
                                                {{ $tag->name_tag }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" checked>
                                        <label class="form-check-label" for="is_active">Kích hoạt</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Thêm mới</button>
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
<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<script>
console.log('Script đã chạy!');

document.addEventListener("DOMContentLoaded", function() {
    var form = document.querySelector('#create-blog-form');
    var quill = null;
    var contentInput = document.querySelector('#content-input');

    if (document.querySelector("#snow-editor")) {
        quill = new Quill("#snow-editor", {
            theme: "snow",
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        // Nếu có dữ liệu cũ, set lại vào Quill
        if (contentInput && contentInput.value) {
            quill.root.innerHTML = contentInput.value;
        }
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            if (quill && contentInput) {
                var content = quill.root.innerHTML.trim();
                contentInput.value = content;
                console.log('Content gửi lên:', contentInput.value);
            }
        });
    }
});
</script>
@endsection

