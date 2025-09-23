@extends('layouts.admin.index')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Quản lý bình luận sản phẩm</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr class="text-center align-middle">
                        <th style="width:70px">ID</th>
                        <th>Sản phẩm</th>
                        <th>Người bình luận</th>
                        <th>Nội dung</th>
                        <th style="width:160px">Ngày</th>
                        <th style="width:120px">Trạng thái</th>
                        <th style="width:220px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                        <tr class="align-middle">
                            <td class="text-center">{{ $comment->id }}</td>
                            <td>
                                @if($comment->product)
                                    <a href="{{ route('product.detail', $comment->product->slug ?? '') }}" target="_blank">
                                        {{ $comment->product->name ?? ('SP#' . $comment->product_id) }}
                                    </a>
                                @else
                                    <span class="text-muted">(đã xóa)</span>
                                @endif
                            </td>
                            <td>{{ $comment->user->name ?? 'Khách' }}</td>
                            <td>{{ $comment->content }}</td>
                            <td class="text-center">{{ $comment->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                @if($comment->is_hidden)
                                    <span class="badge bg-secondary">Đang ẩn</span>
                                @else
                                    <span class="badge bg-success">Đang hiện</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.product-comments.toggle', $comment) }}" method="POST" style="display:inline" onsubmit="return confirm('Xác nhận {{ $comment->is_hidden ? 'hiện' : 'ẩn' }} bình luận này?')">
                                    @csrf
                                    <button class="btn btn-sm btn-{{ $comment->is_hidden ? 'primary' : 'warning' }}">{{ $comment->is_hidden ? 'Hiện' : 'Ẩn' }}</button>
                                </form>
                                <form action="{{ route('admin.product-comments.destroy', $comment) }}" method="POST" style="display:inline" onsubmit="return confirm('Xóa bình luận này?')">
                                    @csrf
                                    @method('DELETE')
                                    
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Chưa có bình luận nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($comments->hasPages())
            <div class="card-footer">
                {{ $comments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
