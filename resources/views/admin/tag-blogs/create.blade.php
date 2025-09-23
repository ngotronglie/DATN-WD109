@extends('layouts.admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thêm Tag Blog Mới</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tag-blogs.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name_tag" class="form-label">Tên Tag</label>
                            <input type="text" class="form-control @error('name_tag') is-invalid @enderror" 
                                id="name_tag" name="name_tag" value="{{ old('name_tag') }}">
                            @error('name_tag')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                id="content" name="content" rows="3">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Tạo Tag</button>
                            <a href="{{ route('admin.tag-blogs.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        let slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9-]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        document.getElementById('slug').value = slug;
    });
</script>
@endpush 