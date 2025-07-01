@extends('index.admindashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h3 class="card-title mb-0">Danh sách Categories</h3>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Thêm mới
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên</th>
                                    <th>Danh mục cha</th>
                                    <th>Trạng thái</th>
                                    <th>Hình ảnh</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $index = ($categories->currentPage() - 1) * $categories->perPage() + 1;
                                @endphp
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $index++ }}</td>
                                        <td>{{ $category->Name }}</td>
                                        <td>
                                            @if ($category->parent)
                                                <span class="badge bg-info">{{ $category->parent->Name }}</span>
                                            @else
                                                <span class="badge bg-secondary">Danh mục gốc</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($category->Is_active)
                                                <span class="badge bg-success">Hiển thị</span>
                                            @else
                                                <span class="badge bg-danger">Ẩn</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($category->Image)
                                                <img src="{{ asset($category->Image) }}" alt="{{ $category->Name }}"
                                                    style="max-width: 50px; height: auto;">
                                            @else
                                                Không có ảnh
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.categories.edit', $category->ID) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category->ID) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-center">
                            <nav aria-label="Page navigation">
                                {{ $categories->links('pagination::bootstrap-4') }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .pagination {
                margin-bottom: 0;
            }

            .pagination .page-item .page-link {
                color: #6c757d;
                border-color: #dee2e6;
                padding: 0.5rem 0.75rem;
                margin: 0 2px;
                border-radius: 4px;
            }

            .pagination .page-item.active .page-link {
                background-color: #0d6efd;
                border-color: #0d6efd;
                color: #fff;
            }

            .pagination .page-item .page-link:hover {
                background-color: #e9ecef;
                color: #0d6efd;
            }

            .pagination .page-item.disabled .page-link {
                color: #6c757d;
                pointer-events: none;
                background-color: #fff;
                border-color: #dee2e6;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Tự động ẩn thông báo sau 3 giây
            $(document).ready(function() {
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 3000);
            });
        </script>
    @endpush
@endsection
