$(document).ready(function() {
    $('#contact-form').on('submit', function(e) {
        e.preventDefault(); // Ngăn form submit mặc định
        
        // Reset tất cả lỗi trước đó
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        
        let isValid = true;
        const formData = new FormData(this);
        
        // Validate từng trường
        const validations = {
            'con_name': {
                required: true,
                message: 'Vui lòng nhập họ tên.'
            },
            'con_email': {
                required: true,
                email: true,
                message: 'Vui lòng nhập email hợp lệ.'
            },
            'con_subject': {
                required: true,
                message: 'Vui lòng nhập tiêu đề.'
            },
            'con_phone': {
                required: true,
                phone: true,
                message: 'Vui lòng nhập số điện thoại hợp lệ (9-12 số).'
            },
            'con_message': {
                required: true,
                minLength: 10,
                message: 'Vui lòng nhập nội dung (ít nhất 10 ký tự).'
            }
        };
        
        // Kiểm tra từng trường
        for (let field in validations) {
            const value = formData.get(field);
            const validation = validations[field];
            
            if (validation.required && (!value || value.trim() === '')) {
                showError(field, validation.message);
                isValid = false;
                continue;
            }
            
            if (validation.email && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    showError(field, 'Email không hợp lệ.');
                    isValid = false;
                    continue;
                }
            }
            
            if (validation.phone && value) {
                const phoneRegex = /^\d{9,12}$/;
                if (!phoneRegex.test(value.replace(/\s/g, ''))) {
                    showError(field, 'Số điện thoại phải từ 9-12 số.');
                    isValid = false;
                    continue;
                }
            }
            
            if (validation.minLength && value && value.length < validation.minLength) {
                showError(field, `Nội dung phải ít nhất ${validation.minLength} ký tự.`);
                isValid = false;
                continue;
            }
        }
        
        // Nếu tất cả đều hợp lệ thì submit form
        if (isValid) {
            // Hiển thị loading
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.text();
            submitBtn.text('Đang gửi...').prop('disabled', true);
            
            // Tạo FormData để gửi
            const formData = new FormData(this);
            
            // Thêm user_id nếu user đã đăng nhập
            const userId = $('meta[name="user-id"]').attr('content');
            if (userId) {
                formData.append('user_id', userId);
            }
            
            // Gửi form bằng AJAX
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Hiển thị thông báo thành công
                    showSuccess('Gửi tin nhắn thành công!');
                    $('#contact-form')[0].reset();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Validation errors từ server
                        const errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            showError(field, errors[field][0]);
                        }
                    } else {
                        showError('general', 'Có lỗi xảy ra, vui lòng thử lại.');
                    }
                },
                complete: function() {
                    // Khôi phục button
                    submitBtn.text(originalText).prop('disabled', false);
                }
            });
        }
    });
    
    // Hàm hiển thị lỗi
    function showError(field, message) {
        const input = $(`[name="${field}"]`);
        input.addClass('is-invalid');
        
        // Tìm div cha chứa input (col-lg-6 hoặc col-lg-12)
        const parentDiv = input.closest('.col-lg-6, .col-lg-12');
        
        // Tạo element hiển thị lỗi
        const errorElement = $(`<div class="error-message text-danger" style="font-size: 12px; margin-top: 2px; color: #dc3545;">${message}</div>`);
        
        // Thêm lỗi vào sau input trong cùng div cha
        input.after(errorElement);
    }
    
    // Hàm hiển thị thành công
    function showSuccess(message) {
        // Xóa thông báo cũ
        $('.alert-success').remove();
        
        // Tạo thông báo mới
        const successElement = $(`<div class="alert alert-success mt-2" style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; border: 1px solid #c3e6cb;">${message}</div>`);
        
        // Thêm vào sau form
        $('#contact-form').append(successElement);
        
        // Tự động ẩn sau 3 giây
        setTimeout(function() {
            successElement.fadeOut();
        }, 3000);
    }
    
    // Xóa lỗi khi user bắt đầu nhập
    $('input, textarea').on('input', function() {
        const fieldName = $(this).attr('name');
        $(this).removeClass('is-invalid');
        $(this).siblings('.error-message').remove();
    });
}); 