@extends('index.clientdashboard')

@section('content')
<div class="container py-5">
    <h4 class="mb-4">Điền thông tin hoàn hàng</h4>

    <!-- Địa chỉ shop -->
    <div class="alert alert-info rounded-4 shadow-sm">
        <h5 class="mb-2"><i class="bi bi-geo-alt-fill"></i> Địa chỉ trả hàng của shop</h5>
        <ul class="mb-0">
            <li><strong>Tên shop:</strong> BeeHat Store</li>
            <li><strong>Người nhận:</strong> Nguyễn Huy Năng</li>
            <li><strong>Địa chỉ:</strong> Số 123 Đường trình văn bô, Phường Nam từ Liêm, Hà Nội</li>
            <li><strong>Số điện thoại:</strong> 0909 123 456</li>
        </ul>
    </div>

    <form action="{{ route('account.updateInfo', $refund->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Các trường form bạn đã có -->
        <div class="mb-3">
            <label for="bank_name" class="form-label">Tên ngân hàng</label>
            <select name="bank_name" class="form-select" required>
                <option value="">-- Chọn ngân hàng --</option>
                @php
                $banks = ['Vietcombank', 'VietinBank', 'BIDV', 'Agribank', 'TPBank', 'ACB', 'MB Bank', 'Sacombank', 'Techcombank', 'VPBank', 'SHB', 'HDBank', 'Eximbank', 'OCB', 'SCB', 'ABBANK', 'VIB', 'SeABank', 'Bac A Bank', 'LienVietPostBank', 'Nam A Bank', 'PG Bank', 'Saigonbank', 'BaoViet Bank', 'NCB', 'Kienlongbank', 'OceanBank', 'PVcomBank'];
                @endphp
                @foreach ($banks as $bank)
                    <option value="{{ $bank }}" {{ old('bank_name', $refund->bank_name) == $bank ? 'selected' : '' }}>
                        {{ $bank }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="bank_number" class="form-label">Số tài khoản</label>
            <input type="text" name="bank_number" class="form-control" value="{{ old('bank_number', $refund->bank_number) }}" required>
        </div>
        <div class="mb-3">
            <label for="account_name" class="form-label">Tên tài khoản</label>
            <input type="text" name="account_name" class="form-control" value="{{ old('account_name', $refund->account_name) }}" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Ảnh sản phẩm hoàn trả</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="sent_back" name="sent_back" value="1" {{ old('sent_back') ? 'checked' : '' }}>
            <label class="form-check-label" for="sent_back">Tôi xác nhận đã gửi hàng hoàn trả</label>
        </div>

        <button type="submit" class="btn btn-success">Gửi thông tin</button>
    </form>
</div>
@endsection
