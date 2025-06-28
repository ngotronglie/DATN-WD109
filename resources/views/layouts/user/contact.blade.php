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
<<<<<<< HEAD
                        <h6>Địa chỉ </h6>
=======
                        <h6>Địa chỉ</h6>
>>>>>>> feature/contact
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
<<<<<<< HEAD
                        <form id="contact-form" method="POST" action="{{ route('contact.post') }}">
=======
                        <form id="contact-form" method="POST" action="{{ route('contact.submit') }}">
>>>>>>> feature/contact
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="blog-section-title border-left mb-30">Gửi tin nhắn cho chúng tôi</h4>
                                </div>
                                <div class="col-lg-6">
<<<<<<< HEAD
                                    <input type="text" name="con_name" placeholder="Họ và tên của bạn" value="{{ old('con_name') }}">
                                    @error('con_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" name="con_email" placeholder="Email của bạn" value="{{ old('con_email') }}">
                                    @error('con_email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" name="con_subject" placeholder="Tiêu đề" value="{{ old('con_subject') }}">
                                    @error('con_subject')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" name="con_phone" placeholder="Số điện thoại" value="{{ old('con_phone') }}">
                                    @error('con_phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-12">
                                    <textarea class="custom-textarea" name="con_message" placeholder="Nội dung tin nhắn">{{ old('con_message') }}</textarea>
                                    @error('con_message')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <button class="submit-btn-1 mt-30 btn-hover-1" type="submit">Gửi tin nhắn</button>
                                </div>
                            </div>
                            <p class="form-message"></p>
                            @if(session('success'))
                                <div class="alert alert-success mt-2">{{ session('success') }}</div>
                            @endif
=======
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
>>>>>>> feature/contact
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
<<<<<<< HEAD
<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDacJcoyPCr-jdlP9HK93h3YKNyf710J0&callback=initMap" async defer></script>
<script src="{{ asset('js/contact-map.js') }}"></script>
<script src="{{ asset('js/contact-validate.js') }}"></script>
=======
<style>
    .form-group {
        margin-bottom: 20px;
        position: relative;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    }
    
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    .is-invalid:focus {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.25) !important;
    }
    
    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
        font-weight: 500;
    }
    
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    
    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    
    .alert ul {
        margin: 0;
        padding-left: 20px;
    }
    
    /* Đảm bảo textarea có style phù hợp */
    .custom-textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }
</style>

<script>
    $(document).ready(function() {
        $('#contact-form').on('submit', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var submitBtn = form.find('button[type="submit"]');
            var messageDiv = form.find('.form-message');
            
            // Clear previous validation errors
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.invalid-feedback').remove();
            messageDiv.html('');
            
            // Disable submit button
            submitBtn.prop('disabled', true).text('Đang gửi...');
            
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        messageDiv.html('<div class="alert alert-success">' + response.message + '</div>');
                        form[0].reset();
                    } else {
                        messageDiv.html('<div class="alert alert-danger">' + response.message + '</div>');
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Hiển thị lỗi validation ngay dưới từng trường
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, messages) {
                            var fieldElement = form.find('[name="' + field + '"]');
                            var formGroup = fieldElement.closest('.form-group');
                            
                            // Thêm class lỗi cho input
                            fieldElement.addClass('is-invalid');
                            
                            // Thêm thông báo lỗi dưới input
                            var errorHtml = '<div class="invalid-feedback">' + messages[0] + '</div>';
                            formGroup.append(errorHtml);
                        });
                    } else {
                        var message = 'Có lỗi xảy ra. Vui lòng thử lại sau.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        messageDiv.html('<div class="alert alert-danger">' + message + '</div>');
                    }
                },
                complete: function() {
                    // Re-enable submit button
                    submitBtn.prop('disabled', false).text('Gửi tin nhắn');
                }
            });
        });
        
        // Xóa lỗi khi user bắt đầu nhập lại
        $('#contact-form input, #contact-form textarea').on('input', function() {
            var field = $(this);
            var formGroup = field.closest('.form-group');
            
            if (field.hasClass('is-invalid')) {
                field.removeClass('is-invalid');
                formGroup.find('.invalid-feedback').remove();
            }
        });
    });
</script>
>>>>>>> feature/contact
@endsection
