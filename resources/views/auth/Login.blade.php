@extends('index.clientdashboard')

@section('content')


    <!-- Main Content -->
    <main class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 bg-white p-5 shadow rounded">
                    <h3 class="mb-4 text-center fw-bold">🔐 Đăng nhập</h3>

                    @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if($errors->has('TokenMismatchException') || session('error') == 'Page Expired')
                    <div class="alert alert-warning">
                        <strong>Phiên đăng nhập đã hết hạn.</strong> Vui lòng thử lại.
                    </div>
                    @endif

                    <form action="{{ route('login.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect" value="{{ request('redirect', '') }}">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control rounded-pill" id="email" name="email" placeholder="Email" required>
                            <label for="email">Email</label>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control rounded-pill" id="password" name="password" placeholder="Mật khẩu" required>
                            <label for="password">Mật khẩu</label>
                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary rounded-pill">🚀 Đăng nhập</button>
                        </div>

                        <div class="text-center mb-3">
                            <p class="mb-1">Bạn chưa có tài khoản?
                                <a href="{{ route('auth.register') }}" class="text-decoration-none">Đăng ký</a>
                            </p>

                            @if (Route::has('password.request'))
                            <a class="text-decoration-none small" href="{{ route('password.request') }}">❓ Quên mật khẩu?</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
    // Refresh CSRF token khi trang load để tránh lỗi Page Expired
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy token mới từ meta tag
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            // Cập nhật tất cả input CSRF token
            document.querySelectorAll('input[name="_token"]').forEach(input => {
                input.value = token.getAttribute('content');
            });
        }
        
        // Refresh token mỗi 30 phút để tránh hết hạn
        setInterval(function() {
            fetch('/csrf-token', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    // Cập nhật meta tag
                    const metaToken = document.querySelector('meta[name="csrf-token"]');
                    if (metaToken) {
                        metaToken.setAttribute('content', data.token);
                    }
                    
                    // Cập nhật form token
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = data.token;
                    });
                }
            })
            .catch(error => {
                console.log('CSRF token refresh failed:', error);
            });
        }, 30 * 60 * 1000); // 30 phút
    });
    </script>

@endsection
