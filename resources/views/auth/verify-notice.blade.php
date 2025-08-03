@extends('index.clientdashboard')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 text-center p-4">
                <div class="card-body">
                    <h2 class="text-success mb-3">🎉 Đăng ký thành công!</h2>

                    <p class="fs-5">
                        Một liên kết xác minh đã được gửi tới email:
                        <strong>
                            <a href="https://mail.google.com/mail/u/{{ $user->email }}" target="_blank" class="text-decoration-none text-primary">
                                {{ $user->email }}
                            </a>
                        </strong>
                    </p>

                    <p class="mt-4">
                        <a href="{{ route('auth.login') }}" class="btn btn-primary rounded-pill px-4 py-2">
                            👉 Quay lại trang đăng nhập
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection