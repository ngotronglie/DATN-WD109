@extends('index.clientdashboard')

@section('content')
<main>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">🔒 Đặt lại mật khẩu</h3>

                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email"
                                       class="form-control rounded-pill px-4 py-3 border-2 shadow-sm"
                                       placeholder="Nhập email của bạn" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Mật khẩu mới -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu mới</label>
                                <input type="password" id="password" name="password"
                                       class="form-control rounded-pill px-4 py-3 border-2 shadow-sm"
                                       placeholder="Nhập mật khẩu mới" required>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Xác nhận mật khẩu -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control rounded-pill px-4 py-3 border-2 shadow-sm"
                                       placeholder="Xác nhận lại mật khẩu" required>
                            </div>

                            <!-- Nút submit -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary rounded-pill ">
                                    ✅ Đặt lại mật khẩu
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
