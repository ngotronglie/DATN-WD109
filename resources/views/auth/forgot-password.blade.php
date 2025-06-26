<h3>Quên mật khẩu</h3>

@if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif

<form action="{{ route('password.email') }}" method="POST">
    @csrf
    <input type="email" name="email" placeholder="Nhập email của bạn" required>
    @error('email') <small style="color: red">{{ $message }}</small> @enderror
    <button type="submit">Gửi link đặt lại mật khẩu</button>
</form>
