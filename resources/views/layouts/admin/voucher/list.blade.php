@extends('layouts.admin.index')

@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý mã giảm giá</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Mã giảm giá</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title mb-0">Danh sách mã giảm giá</h4>
                    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-success">
                        <i class="ri-add-line align-bottom me-1"></i> Thêm mã mới
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mã giảm giá</th>
                                    <th>Phần trăm giảm</th>
                                    <th>Thời gian bắt đầu</th>
                                    <th>Thời gian kết thúc</th>
                                    <th>Số lượng</th>
                                    <th>Đơn tối thiểu</th>
                                    <th>Giảm tối đa</th>
                                    <th>Kích hoạt</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vouchers as $voucher)
                                <tr>
                                    <td>{{ $voucher->id }}</td>
                                    <td>{{ $voucher->code }}</td>
                                    <td>{{ $voucher->discount }}%</td>
                                    <td>{{ \Carbon\Carbon::parse($voucher->start_date)->format('d/m/Y H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($voucher->end_time)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $voucher->quantity }}</td>
                                    <td>{{ number_format($voucher->min_money, 0, ',', '.') }}₫</td>
                                    <td>{{ number_format($voucher->max_money, 0, ',', '.') }}₫</td>
                                    <td>
                                        <span class="badge {{ $voucher->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $voucher->is_active ? 'Đang dùng' : 'Tắt' }}
                                        </span>
                                    </td>
                                    <td>{{ $voucher->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-sm btn-success">
                                                <i class="ri-pencil-fill"></i> Sửa
                                            </a>
                                            <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa voucher này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="ri-delete-bin-fill"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $vouchers->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
