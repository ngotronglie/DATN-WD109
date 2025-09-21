<!-- resources/views/layouts/admin/role/create.blade.php -->

@extends('index.admindashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Thêm Role mới</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.roles.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên Role</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Nhập tên role"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-end">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary me-2">
                                <i class="mdi mdi-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Lưu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
