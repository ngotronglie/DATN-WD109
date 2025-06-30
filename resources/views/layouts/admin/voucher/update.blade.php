@extends('layouts.admin.index')

@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Cập nhật voucher</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Voucher</a></li>
                        <li class="breadcrumb-item active">Cập nhật</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Thông tin voucher</h4>
                </div>
                <div class="card-body">
                    <form class="row g-3 needs-validation" action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="col-md-6">
                            <label for="code" class="form-label">Mã voucher <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror"
                                id="code" name="code" value="{{ old('code', $voucher->code) }}" required>
                            @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @else
                            <div class="invalid-feedback">Vui lòng nhập mã voucher.</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="discount" class="form-label">Giảm giá (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('discount') is-invalid @enderror"
                                id="discount" name="discount" value="{{ old('discount', $voucher->discount) }}" required>
                            @error('discount')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @else
                            <div class="invalid-feedback">Vui lòng nhập phần trăm giảm giá.</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Số lượng <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                id="quantity" name="quantity" value="{{ old('quantity', $voucher->quantity) }}" required>
                            @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @else
                            <div class="invalid-feedback">Vui lòng nhập số lượng.</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                id="start_date" name="start_date"
                                value="{{ old('start_date', \Carbon\Carbon::parse($voucher->start_date)->format('Y-m-d')) }}" required>
                            @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-6">
                            <label for="end_time" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_time') is-invalid @enderror"
                                id="end_time" name="end_time"
                                value="{{ old('end_time', \Carbon\Carbon::parse($voucher->end_time)->format('Y-m-d')) }}" required>
                            @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="min_money" class="form-label">Số tiền tối thiểu <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('min_money') is-invalid @enderror"
                                id="min_money" name="min_money" value="{{ old('min_money', $voucher->min_money) }}" required>
                            @error('min_money')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @else
                            <div class="invalid-feedback">Vui lòng nhập số tiền tối thiểu.</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="max_money" class="form-label">Số tiền tối đa <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('max_money') is-invalid @enderror"
                                id="max_money" name="max_money" value="{{ old('max_money', $voucher->max_money) }}" required>
                            @error('max_money')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @else
                            <div class="invalid-feedback">Vui lòng nhập số tiền tối đa.</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Kích hoạt</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="hstack gap-2 justify-content-start">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line align-bottom me-1"></i> Cập nhật
                                </button>
                                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light">
                                    <i class="ri-arrow-go-back-line align-bottom me-1"></i> Quay lại
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Bootstrap validation + custom validation
    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                // Bootstrap built-in validation
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                // Custom validation
                const discount = parseFloat(form.querySelector('#discount').value);
                const quantity = parseInt(form.querySelector('#quantity').value);
                const minMoney = parseFloat(form.querySelector('#min_money').value);
                const maxMoney = parseFloat(form.querySelector('#max_money').value);

                let errorMessages = [];

                if (isNaN(discount) || discount <= 0) {
                    errorMessages.push("Giảm giá phải lớn hơn 0.");
                }

                if (isNaN(quantity) || quantity <= 0) {
                    errorMessages.push("Số lượng phải lớn hơn 0.");
                }

                if (isNaN(minMoney) || minMoney <= 0) {
                    errorMessages.push("Số tiền tối thiểu phải lớn hơn 0.");
                }

                if (isNaN(maxMoney) || maxMoney <= 0) {
                    errorMessages.push("Số tiền tối đa phải lớn hơn 0.");
                }

                if (!isNaN(minMoney) && !isNaN(maxMoney) && maxMoney < minMoney) {
                    errorMessages.push("Số tiền tối đa phải lớn hơn hoặc bằng số tiền tối thiểu.");
                }

                if (errorMessages.length > 0) {
                    event.preventDefault();
                    event.stopPropagation();
                    alert(errorMessages.join("\n"));
                }

                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

@endsection