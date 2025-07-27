@extends('layouts.admin.index')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Danh sách Flash Sales</h1>
            <a href="{{ route('admin.flash-sales.create') }}" class="btn btn-primary">+ Thêm mới</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>Tên</th>
                    <th>Thời gian</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($flashSales as $flashSale)
                    <tr>
                        <td>{{ $flashSale->id }}</td>
                        <td>{{ $flashSale->name }}</td>
                        <td>
                            Từ: {{ date('d/m/Y H:i', strtotime($flashSale->start_time)) }} <br>
                            Đến: {{ date('d/m/Y H:i', strtotime($flashSale->end_time)) }}
                        </td>
                        <td>
                            @if ($flashSale->is_active)
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-danger">Tạm ngưng</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.flash-sales.edit', $flashSale->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('admin.flash-sales.destroy', $flashSale->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Không có Flash Sales nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $flashSales->links() }} {{-- Phân trang --}}
    </div>
@endsection
