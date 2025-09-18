@extends('index.clientdashboard')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/contact-map.css') }}">
@auth
<meta name="user-id" content="{{ auth()->id() }}">
@endauth
@endsection

@section('content')
<!-- Shopee-style Breadcrumbs -->
<div class="shopee-breadcrumbs">
    <div class="container">
        <div class="breadcrumb-nav">
            <a href="{{ route('home') }}" class="breadcrumb-link">
                <i class="zmdi zmdi-home"></i>
                Trang chủ
            </a>
            <i class="zmdi zmdi-chevron-right breadcrumb-arrow"></i>
            <span class="breadcrumb-current">Liên hệ</span>
        </div>
    </div>
</div>

<!-- Main Contact Section -->
<section class="contact-section">
    <div class="container">
        <!-- Contact Info Cards -->
        <div class="contact-info-section mb-5">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="zmdi zmdi-pin"></i>
                        </div>
                        <div class="contact-content">
                            <h5 class="contact-title">Địa chỉ</h5>
                            <p class="contact-text">13 Trịnh Văn Bô, Nam Từ Liêm, Hà Nội</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="zmdi zmdi-phone"></i>
                        </div>
                        <div class="contact-content">
                            <h5 class="contact-title">Điện thoại</h5>
                            <p class="contact-text">
                                <a href="tel:0123456789" class="contact-link">0123456789</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="zmdi zmdi-email"></i>
                        </div>
                        <div class="contact-content">
                            <h5 class="contact-title">Email</h5>
                            <p class="contact-text">
                                <a href="mailto:info@example.com" class="contact-link">info@example.com</a><br>
                                <a href="mailto:support@example.com" class="contact-link">support@example.com</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Google Map Section -->
        <div class="map-section mb-5">
            <div class="map-container">
                <div class="map-header">
                    <h4 class="map-title">Vị trí của chúng tôi</h4>
                    <p class="map-description">Tìm đường đến cửa hàng của chúng tôi</p>
                </div>
                <div class="map-wrapper">
                    <iframe 
                        src="https://maps.google.com/maps?q=13+Trịnh+Văn+Bô,+Nam+Từ+Liêm,+Hà+Nội,+Việt+Nam&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%" 
                        height="400" 
                        style="border:0; border-radius: 8px;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

        <!-- Contact Form Section -->
        <div class="contact-form-section">
            <div class="contact-form-container">
                <div class="form-header text-center mb-4">
                    <h3 class="form-title">Gửi tin nhắn cho chúng tôi</h3>
                    <p class="form-description">Chúng tôi sẽ phản hồi bạn trong thời gian sớm nhất</p>
                </div>
                
                <form id="contact-form" method="POST" action="{{ route('contact.post') }}" class="contact-form">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label class="form-label">Họ và tên *</label>
                                <input type="text" name="con_name" placeholder="Nhập họ và tên của bạn" 
                                       value="{{ old('con_name') }}" 
                                       class="form-control @error('con_name') is-invalid @enderror">
                                @error('con_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label class="form-label">Email *</label>
                                <input type="email" name="con_email" placeholder="Nhập email của bạn" 
                                       value="{{ old('con_email') }}" 
                                       class="form-control @error('con_email') is-invalid @enderror">
                                @error('con_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label class="form-label">Tiêu đề *</label>
                                <input type="text" name="con_subject" placeholder="Nhập tiêu đề tin nhắn" 
                                       value="{{ old('con_subject') }}" 
                                       class="form-control @error('con_subject') is-invalid @enderror">
                                @error('con_subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" name="con_phone" placeholder="Nhập số điện thoại" 
                                       value="{{ old('con_phone') }}" 
                                       class="form-control @error('con_phone') is-invalid @enderror">
                                @error('con_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 mb-4">
                            <div class="form-group">
                                <label class="form-label">Nội dung tin nhắn *</label>
                                <textarea name="con_message" placeholder="Nhập nội dung tin nhắn của bạn..." 
                                          rows="5" 
                                          class="form-control @error('con_message') is-invalid @enderror">{{ old('con_message') }}</textarea>
                                @error('con_message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 text-center">
                            <button type="submit" class="btn-submit">
                                <i class="zmdi zmdi-mail-send"></i>
                                Gửi tin nhắn
                            </button>
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success mt-3">
                            <i class="zmdi zmdi-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</section>
<!-- End page content -->
@endsection

@section('script-client')
<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDacJcoyPCr-jdlP9HK93h3YKNyf710J0&callback=initMap" async defer></script>
<script src="{{ asset('js/contact-map.js') }}"></script>
<script src="{{ asset('js/contact-validate.js') }}"></script>

<style>
/* Shopee-style Breadcrumbs */
.shopee-breadcrumbs {
    background: #fff;
    padding: 16px 0;
    border-bottom: 1px solid #f0f0f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.breadcrumb-link {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #ee4d2d;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s;
    font-weight: 500;
}

.breadcrumb-link:hover {
    background: #fff5f5;
    color: #d73502;
}

.breadcrumb-arrow {
    color: #ccc;
    font-size: 16px;
    margin: 0 4px;
}

.breadcrumb-current {
    color: #333;
    font-weight: 600;
    padding: 4px 8px;
    background: #f8f9fa;
    border-radius: 4px;
}

/* Main Contact Section */
.contact-section {
    background: #f8f9fa;
    padding: 20px 0;
    min-height: 80vh;
}

/* Contact Info Cards */
.contact-info-section {
    margin-bottom: 40px;
}

.contact-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    padding: 30px 20px;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
    border: 1px solid #f0f0f0;
}

.contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #ee4d2d;
}

.contact-icon {
    width: 60px;
    height: 60px;
    background: #fff5f5;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    transition: all 0.3s ease;
}

.contact-card:hover .contact-icon {
    background: #ee4d2d;
    color: #fff;
}

.contact-icon i {
    font-size: 24px;
    color: #ee4d2d;
    transition: color 0.3s ease;
}

.contact-card:hover .contact-icon i {
    color: #fff;
}

.contact-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.contact-text {
    color: #666;
    line-height: 1.6;
    margin: 0;
}

.contact-link {
    color: #ee4d2d;
    text-decoration: none;
    transition: color 0.2s;
}

.contact-link:hover {
    color: #d73502;
}

/* Map Section */
.map-section {
    margin-bottom: 40px;
}

.map-container {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 30px;
}

.map-header {
    text-align: center;
    margin-bottom: 25px;
}

.map-title {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.map-description {
    color: #666;
    margin: 0;
}

.map-wrapper {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Contact Form */
.contact-form-section {
    margin-bottom: 40px;
}

.contact-form-container {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 40px;
}

.form-header {
    margin-bottom: 30px;
}

.form-title {
    font-size: 28px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.form-description {
    color: #666;
    font-size: 16px;
    margin: 0;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.2s;
    background: #fff;
}

.form-control:focus {
    outline: none;
    border-color: #ee4d2d;
    box-shadow: 0 0 0 3px rgba(238, 77, 45, 0.1);
}

.form-control::placeholder {
    color: #999;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

.btn-submit {
    background: #ee4d2d;
    color: #fff;
    border: none;
    padding: 12px 30px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-submit:hover {
    background: #d73502;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(238, 77, 45, 0.3);
}

.btn-submit:active {
    transform: translateY(0);
}

/* Form Validation */
.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
}

.is-invalid {
    border-color: #dc3545;
}

.alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-success i {
    color: #28a745;
}

/* Responsive */
@media (max-width: 768px) {
    .contact-section {
        padding: 15px 0;
    }
    
    .contact-card {
        padding: 20px 15px;
    }
    
    .contact-icon {
        width: 50px;
        height: 50px;
        margin-bottom: 15px;
    }
    
    .contact-icon i {
        font-size: 20px;
    }
    
    .contact-title {
        font-size: 16px;
    }
    
    .map-container {
        padding: 20px;
    }
    
    .map-title {
        font-size: 20px;
    }
    
    .contact-form-container {
        padding: 25px 20px;
    }
    
    .form-title {
        font-size: 24px;
    }
    
    .btn-submit {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection
