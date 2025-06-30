@extends('index.clientdashboard')

@section('title', '404 - Không tìm thấy trang')

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
        color: #ff6b6b;
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
        background-color: #ff6b6b;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s;
    }
    .back-home:hover {
        background-color: #ff5252;
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
            <img src="{{ asset('user/images/404.png') }}" alt="404 Error" class="error-image">
            <h1 class="error-code">404</h1>
            <h2 class="error-message">Oops! Trang không tồn tại</h2>
            <p class="error-description">
                Xin lỗi, trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.
                <br>Vui lòng kiểm tra lại đường dẫn hoặc quay về trang chủ.
            </p>
            <a href="{{ route('home') }}" class="back-home">
                <i class="fas fa-home me-2"></i>Quay về trang chủ
            </a>
        </div>
    </div>
</section>
@endsection 