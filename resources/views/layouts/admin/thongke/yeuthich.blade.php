{{-- resources/views/layouts/admin/thongke/yeuthich.blade.php --}}
@extends('layouts.admin.index')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:heart-bold-duotone" class="fs-32 text-danger avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Sản phẩm được thích nhiều nhất</p>
                            <h3 class="text-dark mb-0">{{ $mostLikedProductName ?? '-' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <iconify-icon icon="solar:star-bold-duotone" class="fs-32 text-warning avatar-title me-3"></iconify-icon>
                        <div>
                            <p class="text-muted mb-0">Sản phẩm được đánh giá cao nhất</p>
                            <h3 class="text-dark mb-0">{{ $topRatedProductName ?? '-' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Thống kê lượt thích & đánh giá theo sản phẩm</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="bg-light bg-opacity-50">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Lượt thích</th>
                                    <th>Đánh giá trung bình</th>
                                    <th>Số lượt đánh giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productStats ?? [] as $stat)
                                <tr>
                                    <td>{{ $stat['name'] }}</td>
                                    <td>{{ $stat['likes'] }}</td>
                                    <td>{{ $stat['avg_rating'] }}</td>
                                    <td>{{ $stat['review_count'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 