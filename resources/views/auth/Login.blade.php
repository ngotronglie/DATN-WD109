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

                    <form action="{{ route('login.store') }}" method="POST">
                        @csrf
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

@endsection
