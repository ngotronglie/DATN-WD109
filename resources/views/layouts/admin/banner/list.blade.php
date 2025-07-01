@extends('index.admindashboard')

@section('content')
<div class="page-content ms-0 px-0">
    <!-- Start Container Fluid -->
    <div class="container-fluid  " >
        <div class="row">
            <div class="col-xl-12">
                <div class="card ">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Quản lý Banner</h5>
                            <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">Thêm mới Banner</a>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-borderless table-centered">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Hình ảnh</th>
                                        <th scope="col">Tiêu đề</th>
                                        <th scope="col">Mô tả</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Ngày cập nhật</th>
                                        <th scope="col">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($banners as $banner)
                                    <tr>
                                        <td>{{ $banner->id }}</td>
                                        <td>
                                            @if($banner->img)
                                                <img src="{{ asset('images/banners/' . $banner->img) }}" alt="{{ $banner->title }}" style="max-width: 100px;">
                                            @endif
                                        </td>
                                        <td>{{ $banner->title }}</td>
                                        <td>{{ Str::limit($banner->description, 50) }}</td>
                                        <td>
                                             @if($banner->is_active)
                                                  <span class="badge bg-success">Kích hoạt</span>
                                             @else
                                                  <span class="badge bg-secondary">Không kích hoạt</span>
                                             @endif
                                             </td>
                                        <td>{{ $banner->created_at ? $banner->created_at->format('d/m/Y H:i') : '' }}</td>
                                        <td>{{ $banner->updated_at ? $banner->updated_at->format('d/m/Y H:i') : '' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.banners.edit', $banner) }}" 
                                                   class="btn btn-primary btn-sm">Sửa</a>
                                                <form action="{{ route('admin.banners.destroy', $banner) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này không?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateStatus(id, status) {
    // Hiển thị loading state
    const checkbox = event.target;
    const originalState = checkbox.checked;
    checkbox.disabled = true;

    fetch(`/admin/banners/${id}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ is_active: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hiển thị thông báo thành công
            toastr.success('Cập nhật trạng thái thành công');
        } else {
            // Nếu có lỗi, revert checkbox về trạng thái cũ
            checkbox.checked = !originalState;
            toastr.error(data.message || 'Cập nhật trạng thái thất bại');
        }
    })
    .catch(error => {
        // Nếu có lỗi, revert checkbox về trạng thái cũ
        checkbox.checked = !originalState;
        console.error('Error:', error);
        toastr.error('Đã xảy ra lỗi khi cập nhật trạng thái');
    })
    .finally(() => {
        // Enable lại checkbox
        checkbox.disabled = false;
    });
}
</script>
@endpush

@endsection
