<h3>Đặt lại mật khẩu</h3>

<form action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <input type="email" name="email" placeholder="Email" required>
    @error('email') <small>{{ $message }}</small> @enderror

    <input type="password" name="password" placeholder="Mật khẩu mới" required>
    @error('password') <small>{{ $message }}</small> @enderror

    <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" required>

    <button type="submit">Đặt lại mật khẩu</button>
</form>
