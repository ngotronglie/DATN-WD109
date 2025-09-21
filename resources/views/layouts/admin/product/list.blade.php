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
                <div class="card-header d-flex justify-content-between item ">
                    <h3 class="card-title">Danh sách sản phẩm</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Thêm sản phẩm mới
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Tên sản phẩm</th>
                                    <th class="text-center">Danh mục</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Lượt xem</th>
                                    <th class="text-center">Ngày tạo</th>
                                    <th class="text-center">Ngày cập nhật</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="text-center">{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category_name }}</td>
                                    <td class="text-center">
                                        @if($product->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $product->view_count }}</td>
                                    <td class="text-center">{{ $product->created_at ? \Carbon\Carbon::parse($product->created_at)->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="text-center">{{ $product->updated_at ? \Carbon\Carbon::parse($product->updated_at)->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.products.edit', $product->slug) }}"
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>

                                            <form action="{{ route('admin.products.destroy', $product->slug) }}"
                                                  method="POST"
                                                  style="display:inline;"
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-group {
    display: flex;
    gap: 5px;
}
</style>
@endsection
