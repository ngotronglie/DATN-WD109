@extends('layouts.admin.index')
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Quản lý Flash Sale</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-12">
                                <a href="{{ route('admin.flash-sales.create') }}"
                                   class="btn btn-success mb-2">
                                    <i class="mdi mdi-plus-circle me-1"></i>Thêm Flash Sale
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Tên sản phẩm</th>
                                        <th scope="col">Giá gốc</th>
                                        <th scope="col">Giá Flash Sale</th>
                                        <th scope="col">Số lượng</th>
                                        <th scope="col">Thời gian bắt đầu</th>
                                        <th scope="col">Thời gian kết thúc</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col" style="width: 120px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($flashSales as $flashSale)
                                        <tr>
                                            <td>{{ $flashSale->id }}</td>
                                            <td>{{ $flashSale->product->name }}</td>
                                            <td>{{ number_format($flashSale->original_price) }}đ</td>
                                            <td>{{ number_format($flashSale->sale_price) }}đ</td>
                                            <td>{{ $flashSale->quantity }}</td>
                                            <td>{{ $flashSale->start_date }}</td>
                                            <td>{{ $flashSale->end_date }}</td>
                                            <td>
                                                <span class="badge @if($flashSale->is_active) bg-success @else bg-danger @endif">
                                                    {{ $flashSale->is_active ? 'Đang hoạt động' : 'Không hoạt động' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('admin.flash-sales.edit', $flashSale->id) }}"
                                                       class="btn btn-success btn-sm"
                                                       data-bs-toggle="tooltip"
                                                       data-bs-placement="top"
                                                       title="Sửa">
                                                        <span class="nav-icon"><iconify-icon icon="solar:pen-bold"></iconify-icon></span>
                                                    </a>
                                                    <form action="{{ route('admin.flash-sales.destroy', $flashSale->id) }}"
                                                          method="POST"
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-danger btn-sm"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="Xóa"
                                                                onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                            <span class="nav-icon"><iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon></span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 d-flex justify-content-center">
                                {{ $flashSales->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
@endsection
