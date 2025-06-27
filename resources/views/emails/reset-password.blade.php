<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 24px; background-color: #ffffff; border: 1px solid #eaeaea; border-radius: 8px;">
    <h2 style="color: #007bff;">🔐 Yêu cầu đặt lại mật khẩu</h2>

    <p>Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>

    <p>Vui lòng nhấn vào nút bên dưới để tiến hành đặt lại mật khẩu:</p>

    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ route('password.reset', $token) }}"
           style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;">
            👉 Đặt lại mật khẩu
        </a>
    </p>

    <p>Hoặc bạn cũng có thể truy cập liên kết sau:</p>
    <p>
        <a href="{{ route('password.reset', $token) }}" style="color: #007bff;">
            {{ route('password.reset', $token) }}
        </a>
    </p>

    <hr style="margin: 30px 0;">

    <p style="color: #888888; font-size: 14px;">
        Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.
    </p>

    <p style="color: #888888; font-size: 14px;">Cảm ơn bạn,<br>Đội ngũ hỗ trợ</p>
</div>
