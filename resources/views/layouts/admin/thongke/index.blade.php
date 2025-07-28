{{-- resources/views/layouts/admin/thongke/index.blade.php --}}
@extends('layouts.admin.index')
@section('content')
<div class="container-fluid">
    <!-- Card thống kê tổng quan -->
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:cart-large-2-bold-duotone" class="fs-32 text-success avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Tổng sản phẩm</p>
                            <h3 class="text-dark mt-1 mb-0">{{ $totalProducts ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:star-bold-duotone" class="fs-32 text-warning avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Sản phẩm bán chạy</p>
                            <h3 class="text-dark mt-1 mb-0">{{ $bestSellerName ?? '-' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:box-minimalistic-broken" class="fs-32 text-danger avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Sắp hết hàng</p>
                            <h3 class="text-dark mt-1 mb-0">{{ $lowStockCount ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-md bg-light bg-opacity-50 rounded">
                                <iconify-icon icon="solar:box-minimalistic-broken" class="fs-32 text-secondary avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Hết hàng</p>
                            <h3 class="text-dark mt-1 mb-0">{{ $outOfStockCount ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Thêm các card khác cho thống kê đơn hàng, người dùng, lượt thích... -->
    <!-- ... -->
</div>
@endsection 