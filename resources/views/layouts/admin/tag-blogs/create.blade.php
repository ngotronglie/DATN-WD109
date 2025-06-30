@extends('layouts.admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Thêm Thẻ Blog Mới</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tag-blogs.index') }}">Thẻ Blog</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin Thẻ Blog</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tag-blogs.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name_tag" class="form-label">Tên Thẻ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name_tag') is-invalid @enderror" id="name_tag" name="name_tag" value="{{ old('name_tag') }}" placeholder="Nhập tên thẻ">
                            @error('name_tag')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="4" placeholder="Nhập nội dung mô tả">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Thêm mới</button>
                            <a href="{{ route('admin.tag-blogs.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 