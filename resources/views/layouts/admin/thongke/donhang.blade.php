{{-- resources/views/layouts/admin/thongke/donhang.blade.php --}}
@extends('layouts.admin.index')
@section('content')
<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-32 text-warning avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Chưa xử lý</p>
                            <h3 class="text-dark mb-0">{{ $pendingOrders ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-32 text-success avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Đã xử lý</p>
                            <h3 class="text-dark mb-0">{{ $processedOrders ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:cart-large-2-bold-duotone" class="fs-32 text-primary avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Tổng sản phẩm đã bán</p>
                            <h3 class="text-dark mb-0">{{ $totalProductsSold ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 