@extends('index.admindashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quản lý bình luận sản phẩm</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Sản phẩm</th>
                                    <th>Người bình luận</th>
                                    <th>Nội dung</th>
                                    <th>Đánh giá</th>
                                    <th>Trả lời</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comments as $comment)
                                    <tr>
                                        <td>{{ $comment->id }}</td>
                                        <td>
                                            <a href="{{ route('product.detail', $comment->product->slug) }}" target="_blank">
                                                {{ $comment->product->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($comment->user)
                                                    <img src="{{ $comment->user->avatar ?? asset('frontend/img/author/1.jpg') }}" 
                                                         alt="{{ $comment->user->name }}" 
                                                         class="rounded-circle me-2" 
                                                         style="width: 30px; height: 30px; object-fit: cover;">
                                                    <span>{{ $comment->user->name }}</span>
                                                @else
                                                    <span class="text-muted">Khách</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $comment->content }}">
                                                {{ $comment->content }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($comment->rating)
                                                <div class="rating-stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="zmdi zmdi-star {{ $i <= $comment->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                    <span class="ms-1">{{ $comment->rating }}/5</span>
                                                </div>
                                            @else
                                                <span class="text-muted">Không đánh giá</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $comment->replies->count() }}</span>
                                        </td>
                                        <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.product-comments.show', $comment->id) }}" 
                                                   class="btn btn-sm btn-info" title="Xem chi tiết">
                                                    <i class="zmdi zmdi-eye"></i>
                                                </a>
                                                <form action="{{ route('admin.product-comments.destroy', $comment->id) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Xóa bình luận này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="zmdi zmdi-comment-outline text-muted" style="font-size: 48px;"></i>
                                            <p class="text-muted mt-2">Chưa có bình luận nào.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    @if($comments->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $comments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-stars {
    display: inline-block;
}

.rating-stars i {
    font-size: 14px;
}

.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.btn-group .btn {
    margin-right: 2px;
}

.table-responsive {
    overflow-x: auto;
}
</style>
@endsection 