@extends('layouts.admin.index')

@section('css')
<style>
    .card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e9ebec;
        background-color: #fff;
        margin-bottom: 1.5rem;
    }
    .card-title {
        margin-bottom: 0;
        font-size: 16px;
    }
    .card-body {
        padding: 1.5rem;
    }
    .header-actions {
        margin-top: 1rem;
        display: flex;
        justify-content: flex-end;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý màu sắc</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Màu sắc</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Danh sách màu sắc</h5>
                    <a href="{{ route('admin.colors.create') }}" class="btn btn-success create-btn">
                        <i class="ri-add-line align-bottom me-1"></i> Thêm màu mới
                    </a>
                </div>

                <div class="card-body pt-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 10px;">
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option">
                                        </div>
                                    </th>
                                    <th>ID</th>
                                    <th>Tên màu</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($colors as $color)
                                <tr>
                                    <th scope="row">
                                        <div class="form-check">
                                            <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="{{ $color->id }}">
                                        </div>
                                    </th>
                                    <td>{{ $color->id }}</td>
                                    <td>{{ $color->name }}</td>
                                    <td>{{ $color->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.colors.edit', $color) }}" class="btn btn-sm btn-info edit-item-btn">
                                                <i class="ri-pencil-fill align-bottom"></i> Sửa
                                            </a>
                                            
                                            <form action="{{ route('admin.colors.destroy', $color) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger remove-item-btn" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                    <i class="ri-delete-bin-fill align-bottom"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $colors->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    // Hiển thị thông báo thành công
    @if(session('success'))
        Swal.fire({
            html: '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon><div class="mt-4 pt-2 fs-15"><h4>Thành công !</h4><p class="text-muted mx-4 mb-0">{{ session('success') }}</p></div></div>',
            showCancelButton: false,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            buttonsStyling: false,
            showCloseButton: true,
            customClass: {
                closeButton: 'btn btn-light position-absolute',
            }
        });
    @endif

    // Hiển thị thông báo thông tin
    @if(session('info'))
        Swal.fire({
            html: '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/dnmvmpfk.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon><div class="mt-4 pt-2 fs-15"><h4>Thông tin!</h4><p class="text-muted mx-4 mb-0">{{ session('info') }}</p></div></div>',
            showCancelButton: false,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            buttonsStyling: false,
            showCloseButton: true,
            customClass: {
                closeButton: 'btn btn-light position-absolute',
            }
        });
    @endif

    // Xác nhận xóa
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa?',
                text: 'Dữ liệu đã xóa không thể khôi phục!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Có, xóa nó!',
                cancelButtonText: 'Hủy',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary w-xs me-2',
                    cancelButton: 'btn btn-danger w-xs',
                    closeButton: 'btn btn-light position-absolute',
                },
                showCloseButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    // Check all checkboxes
    document.getElementById('checkAll').addEventListener('change', function() {
        document.querySelectorAll('input[name="checkAll"]').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Auto close alerts
    window.setTimeout(function() {
        document.querySelectorAll(".alert").forEach(function(alert) {
            alert.classList.add('fade');
            alert.style.display = 'none';
        });
    }, 4000);
</script>
@endsection 