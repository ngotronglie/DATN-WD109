@extends('index.clientdashboard')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/contact-map.css') }}">
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
                        <h6>Địa chỉ </h6>
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
                <div id="googleMap" style="width:100%;height:400px;"></div>
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
                        <form id="contact-form">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="blog-section-title border-left mb-30">Gửi tin nhắn cho chúng tôi</h4>
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" name="con_name" placeholder="Họ và tên của bạn">
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" name="con_email" placeholder="Email của bạn">
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" name="con_subject" placeholder="Tiêu đề">
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" name="con_phone" placeholder="Số điện thoại">
                                </div>
                                <div class="col-lg-12">
                                    <textarea class="custom-textarea" name="con_message" placeholder="Nội dung tin nhắn"></textarea>
                                    <button class="submit-btn-1 mt-30 btn-hover-1" type="submit">Gửi tin nhắn</button>
                                </div>
                            </div>
                            <p class="form-message"></p>
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
@endsection
