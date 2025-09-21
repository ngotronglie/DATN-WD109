@extends('layouts.admin.index')

@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Thêm mã giảm giá</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Voucher</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Thông tin voucher</h4>
                </div>

                <div class="card-body">
                    <form class="row g-3" action="{{ route('admin.vouchers.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="col-md-6">
                            <label for="code" class="form-label">Mã voucher <span class="text-danger">*</span></label>
                            <input type="text" id="code" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="discount" class="form-label">Giảm giá (%) <span class="text-danger">*</span></label>
                            <input type="number" id="discount" name="discount" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount') }}" required min="1" max="100">
                            @error('discount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Số lượng <span class="text-danger">*</span></label>
                            <input type="number" id="quantity" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" required min="1">
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" id="start_date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="end_time" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                            <input type="date" id="end_time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                            @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="min_money" class="form-label">giá trị đơn hàng tối thiểu <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="min_money" name="min_money" class="form-control @error('min_money') is-invalid @enderror" value="{{ old('min_money') }}" required>
                            @error('min_money')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="max_money" class="form-label">giá trị đơn hàng tối đa <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="max_money" name="max_money" class="form-control @error('max_money') is-invalid @enderror" value="{{ old('max_money') }}" required>
                            @error('max_money')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Kích hoạt</label>
                            </div>
                            @error('is_active')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i> Lưu
                            </button>
                            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light">
                                <i class="ri-arrow-go-back-line me-1"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.querySelector('form').addEventListener('submit', function (e) {
        const discount = parseFloat(document.getElementById('discount').value);
        const quantity = parseInt(document.getElementById('quantity').value);
        const minMoney = parseFloat(document.getElementById('min_money').value);
        const maxMoney = parseFloat(document.getElementById('max_money').value);

        let hasError = false;
        let errorMessages = [];

        if (isNaN(discount) || discount <= 0) {
            errorMessages.push("Giảm giá phải lớn hơn 0.");
            hasError = true;
        }

        if (isNaN(quantity) || quantity <= 0) {
            errorMessages.push("Số lượng phải lớn hơn 0.");
            hasError = true;
        }

        if (isNaN(minMoney) || minMoney <= 0) {
            errorMessages.push("Số tiền tối thiểu phải lớn hơn 0.");
            hasError = true;
        }

        if (isNaN(maxMoney) || maxMoney <= 0) {
            errorMessages.push("Số tiền tối đa phải lớn hơn 0.");
            hasError = true;
        }

        if (!isNaN(minMoney) && !isNaN(maxMoney) && maxMoney < minMoney) {
            errorMessages.push("Số tiền tối đa phải lớn hơn hoặc bằng số tiền tối thiểu.");
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
            alert(errorMessages.join("\n"));
        }
    });
</script>
@endpush


@endsection
