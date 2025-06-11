@extends('layouts.admin.index')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Cập nhật Tag Blog</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tag-blogs.update', $tagBlog->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name_tag" class="form-label">Tên Tag</label>
                            <input type="text" class="form-control @error('name_tag') is-invalid @enderror" 
                                id="name_tag" name="name_tag" value="{{ old('name_tag', $tagBlog->name_tag) }}">
                            @error('name_tag')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                id="content" name="content" rows="3">{{ old('content', $tagBlog->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('admin.tag-blogs.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 