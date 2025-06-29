@extends('index.clientdashboard')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/contact-map.css') }}">
@auth
<meta name="user-id" content="{{ auth()->id() }}">
@endauth
@endsection

@section('content')
<!-- BREADCRUMBS SECTION START -->
<div class="breadcrumbs-section plr-200 mb-80 section">
    <div class="breadcrumbs overlay-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumbs-inner">
                        <h1 class="breadcrumbs-title">Liên hệ</h1>
                        <ul class="breadcrumb-list">
                            <li><a href="/">Trang chủ</a></li>
                            <li>Liên hệ</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- BREADCRUMBS SECTION END -->

<!-- Start page content -->
<section id="page-content" class="page-wrapper section">
    <!-- ADDRESS SECTION START -->
    <div class="address-section mb-80">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="contact-address box-shadow">
                        <i class="zmdi zmdi-pin"></i>
                        <h6>Địa chỉ</h6>
                        <h6>13 Trịnh Văn Bô, Nam Từ Liêm, Hà Nội</h6>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-address box-shadow">
                        <i class="zmdi zmdi-phone"></i>
                        <h6><a href="tel:0123456789">0123456789</a></h6>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-address box-shadow">
                        <i class="zmdi zmdi-email"></i>
                        <h6>info@example.com</h6>
                        <h6>support@example.com</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ADDRESS SECTION END -->

    <!-- GOOGLE MAP SECTION START -->
    <div class="google-map-section mb-80">
        <div class="container-fluid">
            <div class="google-map plr-185">
                <div id="googleMap" style="width:100%;height:400px;">
                    <iframe 
                        src="https://maps.google.com/maps?q=13+Trịnh+Văn+Bô,+Nam+Từ+Liêm,+Hà+Nội,+Việt+Nam&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%" 
                        height="400" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- GOOGLE MAP SECTION END -->

    <!-- MESSAGE BOX SECTION START -->
    <div class="message-box-section mt--50 mb-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="message-box box-shadow white-bg">
                        <form id="contact-form" method="POST" action="{{ route('contact.post') }}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="blog-section-title border-left mb-30">Gửi tin nhắn cho chúng tôi</h4>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" name="con_name" placeholder="Họ và tên của bạn" value="{{ old('con_name') }}" class="form-control @error('con_name') is-invalid @enderror">
                                        @error('con_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" name="con_email" placeholder="Email của bạn" value="{{ old('con_email') }}" class="form-control @error('con_email') is-invalid @enderror">
                                        @error('con_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" name="con_subject" placeholder="Tiêu đề" value="{{ old('con_subject') }}" class="form-control @error('con_subject') is-invalid @enderror">
                                        @error('con_subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" name="con_phone" placeholder="Số điện thoại" value="{{ old('con_phone') }}" class="form-control @error('con_phone') is-invalid @enderror">
                                        @error('con_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <textarea class="custom-textarea form-control @error('con_message') is-invalid @enderror" name="con_message" placeholder="Nội dung tin nhắn">{{ old('con_message') }}</textarea>
                                        @error('con_message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button class="submit-btn-1 mt-30 btn-hover-1" type="submit">Gửi tin nhắn</button>
                                </div>
                            </div>
                            <div class="form-message mt-3"></div>
                            @if(session('success'))
                                <div class="alert alert-success mt-2">{{ session('success') }}</div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MESSAGE BOX SECTION END -->
</section>
<!-- End page content -->
@endsection

@section('script-client')
<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDacJcoyPCr-jdlP9HK93h3YKNyf710J0&callback=initMap" async defer></script>
<script src="{{ asset('js/contact-map.js') }}"></script>
<script src="{{ asset('js/contact-validate.js') }}"></script>
<style>
    .form-group {
        margin-bottom: 20px;
    }
    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 14px;
        margin-top: 5px;
    }
    .is-invalid {
        border-color: #dc3545;
    }
</style>
@endsection
