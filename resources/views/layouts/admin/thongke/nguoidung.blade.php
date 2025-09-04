{{-- resources/views/layouts/admin/thongke/nguoidung.blade.php --}}
@extends('layouts.admin.index')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-32 text-success avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Tổng số tài khoản</p>
                            <h3 class="text-dark mb-0">{{ $totalUsers ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:calendar-bold-duotone" class="fs-32 text-info avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Đăng ký mới hôm nay</p>
                            <h3 class="text-dark mb-0">{{ $newUsersToday ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:calendar-bold-duotone" class="fs-32 text-warning avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Tài khoản người dùng hiện có</p>
                            <h3 class="text-dark mb-0">{{ \App\Models\User::whereHas('role', function($q){ $q->where('name', 'user'); })->count()}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:calendar-bold-duotone" class="fs-32 text-warning avatar-title me-3"></iconify-icon>
                        <div>Admin hiện có</p>
                            <h3 class="text-dark mb-0">{{ \App\Models\User::whereHas('role', function($q){ $q->where('name', 'admin'); })->count()}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:star-bold-duotone" class="fs-32 text-primary avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Khách hàng mua nhiều nhất</p>
                            <h3 class="text-dark mb-0">{{ $topBuyerName ?? '-' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    
    <!-- Debug Section (tạm thời) -->
   
</div>
@endsection