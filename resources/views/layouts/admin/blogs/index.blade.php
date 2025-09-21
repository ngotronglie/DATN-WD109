@extends('layouts.admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Danh sách Bài viết</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Bài viết</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">Thêm mới</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ảnh</th>
                                <th>Slug (Tiêu đề)</th>
                                <th>Tác giả</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($blogs as $blog)
                                <tr>
                                    <td>{{ $blog->id }}</td>
                                    <td>
                                        <img src="{{ $blog->image }}" alt="{{ $blog->slug }}" width="80">
                                    </td>
                                    <td>{{ $blog->slug }}</td>
                                    <td>{{ $blog->user->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($blog->is_active)
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-danger">Không hoạt động</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                                        <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Không có bài viết nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $blogs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
console.log('Script đã chạy!');
</script>
@endsection

@yield('script') 