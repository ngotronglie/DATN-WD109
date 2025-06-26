<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 bg-white p-4 shadow rounded text-center">
            <h2>🎉 Đăng ký thành công!</h2>

            <p>
                Một liên kết xác minh đã được gửi tới
                <strong>
                    <a href="https://mail.google.com/mail/u/{{ $user->email }}" target="_blank" class="text-primary">
                        {{ $user->email }}
                    </a>
                </strong>.
            </p>

            <p class="mt-4">
                <a href="{{ route('auth.login') }}" class="btn btn-primary">👉 Quay lại trang đăng nhập</a>
            </p>
        </div>
    </div>
</div>