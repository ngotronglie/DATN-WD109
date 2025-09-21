@extends('layouts.admin.index')

@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Cập nhật dung lượng</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.capacities.index') }}">Dung lượng</a>
                        </li>
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
                    <h5 class="card-title mb-0">Cập nhật dung lượng</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.capacities.update', $capacity) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="name" class="form-label">Tên dung lượng <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $capacity->name) }}" placeholder="Nhập tên dung lượng">
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
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
    // Hiển thị thông báo lỗi nếu có
    @if($errors->any())
        Swal.fire({
            html: '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#f06548,secondary:#f7b84b" style="width:120px;height:120px"></lord-icon><div class="mt-4 pt-2 fs-15"><h4>Oops...!</h4><p class="text-muted mx-4 mb-0">Có một số lỗi trong quá trình nhập liệu. Vui lòng kiểm tra lại!</p></div></div>',
            showCancelButton: false,
            showConfirmButton: false,
            buttonsStyling: false,
            timer: 3000,
            timerProgressBar: true,
            showCloseButton: true,
            customClass: {
                closeButton: 'btn btn-light position-absolute',
            }
        });
    @endif
</script>
@endsection 