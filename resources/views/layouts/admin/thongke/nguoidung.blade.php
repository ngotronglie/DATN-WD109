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
    @if(config('app.debug'))
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Debug Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Raw Data:</h6>
                            <ul>
                            <li>Tổng số tài khoản (totalUsers): {{ $totalUsers ?? 'NULL' }}</li>
                            <li>Đăng ký mới hôm nay (newUsersToday): {{ $newUsersToday ?? 'NULL' }}</li>
                            <li>Đăng ký mới tháng này (newUsersMonth): {{ $newUsersMonth ?? 'NULL' }}</li>
                            <li>Khách hàng mua nhiều nhất (topBuyerName): {{ $topBuyerName ?? 'NULL' }}</li>
                            <li>Số tài khoản Admin (adminCount): {{ $adminCount ?? 'NULL' }}</li>
                            <li>Số tài khoản Khách hàng (customerCount): {{ $customerCount ?? 'NULL' }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Database Check:</h6>
                            <ul>
                            <li>Tổng số tài khoản trong DB: {{ \App\Models\User::count() }}</li>
                            <li>Số tài khoản có vai trò Admin: {{ \App\Models\User::whereHas('role', function($q){ $q->where('name', 'admin'); })->count() }}</li>
                            <li>Số tài khoản có vai trò User: {{ \App\Models\User::whereHas('role', function($q){ $q->where('name', 'user'); })->count() }}</li>
                            <li>Số tài khoản không có role: {{ \App\Models\User::whereNull('role_id')->count() }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection