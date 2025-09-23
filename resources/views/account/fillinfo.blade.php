@extends('index.clientdashboard')

@section('content')
<div class="container py-5">
    <h4 class="mb-4">Điền thông tin hoàn hàng</h4>

    <div class="alert alert-info rounded-4 shadow-sm">
        Đây là yêu cầu hoàn tiền do cửa hàng khởi tạo (hết hàng hoặc lý do khác). Vui lòng nhập thông tin tài khoản để nhận hoàn tiền.
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

        <!-- Không yêu cầu gửi hàng/ảnh trong hoàn tiền do admin -->

        <button type="submit" class="btn btn-success">Gửi thông tin</button>
    </form>
</div>
@endsection
