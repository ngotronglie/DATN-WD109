@extends('layouts.admin.index')

@section('title', 'Thêm mới Flash Sale')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="mt-3">Thêm mới Flash Sale</h1>

            <form action="{{ route('admin.flash_sales.store') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="title">Tiêu đề:</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Nhập tiêu đề Flash Sale" value="{{ old('title') }}">
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

{{--                <div class="form-group mb-3">--}}
{{--                    <label for="description">Mô tả:</label>--}}
{{--                    <textarea name="description" id="description" class="form-control" rows="4" placeholder="Nhập mô tả Flash Sale">{{ old('description') }}</textarea>--}}
{{--                    @error('description')--}}
{{--                        <small class="text-danger">{{ $message }}</small>--}}
{{--                    @enderror--}}
{{--                </div>--}}

                <div class="form-group mb-3">
                    <label for="start_time">Thời gian bắt đầu:</label>
                    <input type="datetime-local" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}">
                    @error('start_time')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="end_time">Thời gian kết thúc:</label>
                    <input type="datetime-local" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}">
                    @error('end_time')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="status">Trạng thái:</label>
                    <select name="status" id="status" class="form-control">
                        <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Đang lên lịch</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                    </select>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Lưu</button>
                <a href="{{ route('admin.flash_sales.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
@endsection
