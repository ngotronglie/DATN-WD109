@extends('index.clientdashboard')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 text-center p-4">
                <div class="card-body">
                    <div class="mb-3" style="font-size:60px; line-height:1;">
                        <span class="text-success">✅</span>
                    </div>
                    <h2 class="fw-bold mb-2" style="color:#16a34a;">Đăng ký thành công!</h2>
                    <p class="text-muted mb-4">Chúng tôi đã gửi một email xác minh đến địa chỉ:</p>
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill mb-3" style="background:#f1f5f9;">
                        <span class="fw-semibold">{{ $user->email }}</span>
                    </div>

                    <div class="row g-2 justify-content-center mb-3">
                    
                        <div class="col-12 mt-2">
                            <a href="{{ route('auth.login') }}" class="btn btn-primary rounded-pill px-4">
                                Đi tới đăng nhập
                            </a>
                        </div>
                    </div>

                    <div class="small text-muted">
                        - Nếu không thấy email, vui lòng kiểm tra thư mục Spam/Quảng cáo.<br>
                        - Liên kết xác minh chỉ có hiệu lực trong 60 phút.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection