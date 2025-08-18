@extends('layouts.admin.index')

@section('title', 'Danh sách sản phẩm Flash Sale')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h1 class="mt-3">Quản lý Flash Sale Products</h1>
            <a href="{{ route('admin.flash_sale_products.create') }}" class="btn btn-primary mb-3">Thêm Mới</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="col-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Flash Sale</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($flashSaleProducts as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->flashSale->title ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.flash_sale_products.edit', $product->id) }}" class="btn btn-warning btn-sm">Chỉnh sửa</a>
                                <form action="{{ route('admin.flash_sale_products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if ($flashSaleProducts->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center">Chưa có sản phẩm nào trong Flash Sale</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
