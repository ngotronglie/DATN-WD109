@extends('index.clientdashboard')

@section('title', '403 - Truy cập bị từ chối')

@section('css')
<style>
    .error-section {
        padding: 100px 0;
        min-height: 60vh;
        display: flex;
        align-items: center;
    }
    .error-container {
        text-align: center;
        max-width: 600px;
        margin: 0 auto;
    }
    .error-code {
        font-size: 120px;
        font-weight: 700;
        color: #dc3545;
        line-height: 1;
        margin-bottom: 20px;
    }
    .error-message {
        font-size: 24px;
        color: #333;
        margin-bottom: 30px;
    }
    .error-description {
        color: #666;
        margin-bottom: 30px;
    }
    .back-home {
        display: inline-block;
        padding: 12px 30px;
        background-color: #dc3545;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s;
    }
    .back-home:hover {
        background-color: #c82333;
        color: #fff;
    }
    .error-image {
        max-width: 400px;
        margin: 0 auto 30px;
    }
</style>
@endsection

@section('content')
<section class="error-section">
    <div class="container">
        <div class="error-container">
            <img src="{{ asset('user/images/403.png') }}" alt="403 Error" class="error-image">
            <h1 class="error-code">403</h1>
            <h2 class="error-message">Truy cập bị từ chối!</h2>
            <p class="error-description">
                Xin lỗi, bạn không có quyền truy cập vào trang này.
                <br>Vui lòng quay về trang chủ hoặc liên hệ quản trị viên nếu bạn cần hỗ trợ.
            </p>
            <a href="{{ route('home') }}" class="back-home">
                <i class="fas fa-home me-2"></i>Quay về trang chủ
            </a>
        </div>
    </div>
</section>
@endsection 