@extends('index.admindashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Danh sách Roles</h4>
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Thêm Role
                    </a>
                </div>
                <div class="card-body">
                    @if ($roles->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted">Không có role nào.</p>
                            <p class="text-muted">Hãy thêm role mới để bắt đầu</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>Tên Role</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-xs">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="mdi mdi-account"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        {{ $role->name }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.roles.edit', $role->id) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="mdi mdi-pencil"></i> Sửa
                                                </a>
                                                <form action="{{ route('admin.roles.destroy', $role->id) }}" 
                                                      method="POST" 
                                                      class="d-inline" 
                                                      onsubmit="return confirm('Bạn có chắc muốn xóa role này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="mdi mdi-delete"></i> Xóa
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection