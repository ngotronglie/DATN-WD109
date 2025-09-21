@extends('layouts.admin.index')

@section('css')
<style>
    .error-content {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .error-img {
        max-width: 600px;
        margin: 0 auto;
    }
    .error-title {
        font-size: 6rem;
        font-weight: 700;
        color: #405189;
        margin-bottom: 1rem;
    }
    .error-subtitle {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    .error-text {
        color: #878a99;
        margin-bottom: 2rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="error-content text-center">
        <div>
            <div class="error-img mb-4">
                <img src="{{ asset('dashboard/images/error404.png') }}" class="img-fluid" alt="404 Error">
            </div>
            <div>
                <h1 class="error-title">404</h1>
                <h4 class="error-subtitle">Không tìm thấy trang!</h4>
                <p class="error-text w-75 mx-auto mb-4">Trang bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary"><i class="mdi mdi-home me-1"></i>Quay về trang chủ</a>
            </div>
        </div>
    </div>
</div>
@endsection 