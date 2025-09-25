@extends('index.admindashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h3 class="card-title mb-0">Danh sách người dùng</h3>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Thêm mới
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Ảnh đại diện</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Địa chỉ</th>
                                        <th>Ngày sinh</th>
                                        <th>Vai trò</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>
                                                @if($user->avatar)
                                                    <img src="{{ asset($user->avatar) }}" alt="Avatar" class="rounded-circle" width="40" height="40">
                                                @else
                                                    <img src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar" class="rounded-circle" width="40" height="40">
                                                @endif
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone_number ?? 'N/A' }}</td>
                                            <td>{{ $user->address ?? 'N/A' }}</td>
                                            <td>{{ $user->date_of_birth ? date('d/m/Y', strtotime($user->date_of_birth)) : 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'info' }}">
                                                    {{ $user->role == 'admin' ? 'Admin' : 'User' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                                    {{ $user->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <!-- <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a> -->
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')" title="Xóa">
                                                            <i class="fas fa-trash">Khoá</i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .table th, .table td {
                vertical-align: middle;
            }
            .badge {
                font-size: 0.85em;
            }
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
                line-height: 1.5;
                border-radius: 0.2rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 32px;
                height: 32px;
            }
            .btn-sm i {
                font-size: 0.875rem;
                margin: 0;
            }
            .gap-2 {
                gap: 0.5rem;
            }
            .d-flex {
                display: flex;
            }
            .justify-content-center {
                justify-content: center;
            }
            .btn-info {
                background-color: #0dcaf0;
                border-color: #0dcaf0;
                color: #fff;
            }
            .btn-info:hover {
                background-color: #31d2f2;
                border-color: #25cff2;
                color: #fff;
            }
            .btn-danger {
                background-color: #dc3545;
                border-color: #dc3545;
                color: #fff;
            }
            .btn-danger:hover {
                background-color: #bb2d3b;
                border-color: #b02a37;
                color: #fff;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Tự động ẩn thông báo sau 3 giây
            $(document).ready(function() {
                setTimeout(function() {
                    $('.alert').alert('close');
                }, 3000);
            });
        </script>
    @endpush
@endsection
