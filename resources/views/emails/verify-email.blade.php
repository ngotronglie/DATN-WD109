<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 10px;
            background-color: #007bff;
            color: #fff !important;
            text-decoration: none;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <p>Chào <strong>{{ $user->name }}</strong>,</p>

    <p>Chúng tôi đã nhận được yêu cầu đăng ký tài khoản từ bạn. Vui lòng nhấp vào nút bên dưới để xác minh địa chỉ email:</p>

    <p>
        <a href="{{ route('verify.email', ['email' => $user->email, 'token' => $token]) }}" class="button">
            ✅ Xác minh email
        </a>
    </p>

    <p>Nếu bạn không thực hiện yêu cầu này, bạn có thể bỏ qua email này.</p>

    <p><strong>Lưu ý:</strong> Liên kết này sẽ hết hạn sau 60 phút kể từ thời điểm gửi.</p>

    <p>Trân trọng,<br>Đội ngũ hỗ trợ WD_109</p>
</body>
</html>
