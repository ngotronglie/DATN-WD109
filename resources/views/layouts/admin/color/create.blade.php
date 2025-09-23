@extends('layouts.admin.index')

@section('css')
<style>
    .form-label {
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Thêm màu mới</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.colors.index') }}">Màu sắc</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Thông tin màu sắc</h4>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="live-preview">
                        <form class="row g-3 needs-validation" action="{{ route('admin.colors.store') }}" method="POST" novalidate>
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name" class="form-label">Tên màu <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           required placeholder="Nhập tên màu (ví dụ: Đỏ, Xanh, Vàng)"
                                           pattern="^[a-zA-ZÀ-ỹ\s]+$"
                                           minlength="2" maxlength="50">
                                    <div class="invalid-feedback" id="name-error">
                                        @error('name')
                                            {{ $message }}
                                        @else
                                            Vui lòng nhập tên màu hợp lệ (chỉ chữ cái, 2-50 ký tự).
                                        @enderror
                                    </div>
                                    <div class="valid-feedback">
                                        Tên màu hợp lệ!
                                    </div>
                                    <small class="text-muted">Chỉ được nhập chữ cái và khoảng trắng, từ 2-50 ký tự.</small>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line align-bottom me-1"></i> Lưu
                                    </button>
                                    <a href="{{ route('admin.colors.index') }}" class="btn btn-light">
                                        <i class="ri-arrow-go-back-line align-bottom me-1"></i> Quay lại
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="d-none code-view">
                        <pre class="language-markup" style="height: 375px;">
                            <code>&lt;form class="row g-3"&gt;
                                ...
                            &lt;/form&gt;</code>
                        </pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>
@endsection 