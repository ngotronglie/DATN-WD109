@extends('layouts.admin.index')

@section('title', 'Flash Sales')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="mt-3">Danh sách Flash Sale</h1>
            <a href="{{ route('admin.flash_sales.create') }}" class="btn btn-primary mb-3">Thêm Flash Sale</a>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Mô tả</th>
                        <th>Thời gian bắt đầu</th>
                        <th>Thời gian kết thúc</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($flashSales as $flashSale)
                        <tr>
                            <td>{{ $flashSale->id }}</td>
                            <td>{{ $flashSale->title }}</td>
                            <td>{{ $flashSale->description ?? 'Không có mô tả' }}</td>
                            <td>{{ $flashSale->start_time }}</td>
                            <td>{{ $flashSale->end_time }}</td>
                            <td>
                                @if ($flashSale->status === 'scheduled')
                                    <span class="badge bg-warning">Đang lên lịch</span>
                                @elseif ($flashSale->status === 'active')
                                    <span class="badge bg-success">Đang hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Không hoạt động</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.flash_sales.edit', $flashSale->id) }}" class="btn btn-warning btn-sm">Chỉnh sửa</a>
                                <form action="{{ route('admin.flash_sales.destroy', $flashSale->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa không?')">Xóa</button>
                                </form>
                                <a href="{{ route('admin.flash_sales.show', $flashSale->id) }}" class="btn btn-info btn-sm">Xem chi tiết</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
