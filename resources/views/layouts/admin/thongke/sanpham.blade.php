{{-- resources/views/layouts/admin/thongke/sanpham.blade.php --}}
@extends('layouts.admin.index')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:cart-large-2-bold-duotone" class="fs-32 text-success avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Tổng sản phẩm</p>
                            <h3 class="text-dark mb-0">{{ $totalProducts ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:star-bold-duotone" class="fs-32 text-warning avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Sản phẩm bán chạy nhất</p>
                            <h3 class="text-dark mb-0">{{ $bestSellerName ?? '-' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:box-minimalistic-broken" class="fs-32 text-danger avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Sản phẩm sắp hết hàng</p>
                            <h3 class="text-dark mb-0">{{ $lowStockCount ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:box-minimalistic-broken" class="fs-32 text-secondary avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Sản phẩm hết hàng</p>
                            <h3 class="text-dark mb-0">{{ $outOfStockCount ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:box-minimalistic-broken" class="fs-32 text-info avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Sản phẩm chưa được mua lần nào</p>
                            <h3 class="text-dark mb-0">{{ $neverSoldCount ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:discount-bold-duotone" class="fs-32 text-primary avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Sản phẩm đang giảm giá</p>
                            <h3 class="text-dark mb-0">{{ $discountCount ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 