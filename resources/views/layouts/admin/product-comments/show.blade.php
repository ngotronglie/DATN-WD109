@extends('index.admindashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Chi tiết bình luận</h3>
                        <a href="{{ route('admin.product-comments.index') }}" class="btn btn-secondary">
                            <i class="zmdi zmdi-arrow-back"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Thông tin bình luận chính -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="comment-detail">
                                <div class="media">
                                    <div class="media-left me-3">
                                        <img class="media-object rounded-circle" 
                                             src="{{ $comment->user->avatar ?? asset('frontend/img/author/1.jpg') }}" 
                                             alt="{{ $comment->user->name }}" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h5 class="media-heading mb-1">{{ $comment->user->name ?? 'Khách' }}</h5>
                                                @if($comment->rating)
                                                    <div class="rating-stars mb-2">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="zmdi zmdi-star {{ $i <= $comment->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                        @endfor
                                                        <span class="ms-2">{{ $comment->rating }}/5</span>
                                                    </div>
                                                @endif
                                                <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i:s') }}</small>
                                            </div>
                                            <div>
                                                <a href="{{ route('product.detail', $comment->product->slug) }}" 
                                                   target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="zmdi zmdi-eye"></i> Xem sản phẩm
                                                </a>
                                            </div>
                                        </div>
                                        <div class="comment-content p-3 bg-light rounded">
                                            <p class="mb-0">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin sản phẩm -->
                            <div class="product-info mt-4">
                                <h5>Thông tin sản phẩm</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <img src="{{ $comment->product->variants->first()->image ?? asset('frontend/img/product/1.jpg') }}" 
                                             alt="{{ $comment->product->name }}" 
                                             class="img-fluid rounded">
                                    </div>
                                    <div class="col-md-9">
                                        <h6>{{ $comment->product->name }}</h6>
                                        <p class="text-muted mb-1">{{ $comment->product->description }}</p>
                                        <small class="text-muted">Danh mục: {{ $comment->product->category->Name ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Form trả lời -->
                            <div class="reply-form">
                                <h5>Trả lời bình luận</h5>
                                <form action="{{ route('admin.product-comments.reply', $comment->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="content">Nội dung trả lời:</label>
                                        <textarea name="content" class="form-control" rows="4" 
                                                  placeholder="Viết trả lời của bạn..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="zmdi zmdi-send"></i> Gửi trả lời
                                    </button>
                                </form>
                            </div>

                            <!-- Thống kê -->
                            <div class="stats mt-4">
                                <h5>Thống kê</h5>
                                <div class="list-group">
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span>Tổng trả lời:</span>
                                        <span class="badge bg-primary">{{ $comment->replies->count() }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span>Đánh giá:</span>
                                        <span class="badge bg-warning">{{ $comment->rating ?? 'N/A' }}/5</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <span>Ngày tạo:</span>
                                        <span>{{ $comment->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách trả lời -->
                    @if($comment->replies->count() > 0)
                        <div class="replies-section mt-4">
                            <h5>Trả lời ({{ $comment->replies->count() }})</h5>
                            @foreach($comment->replies as $reply)
                                <div class="reply-item border-start border-primary ps-3 ms-3 mt-3">
                                    <div class="media">
                                        <div class="media-left me-3">
                                            <img class="media-object rounded-circle" 
                                                 src="{{ $reply->user->avatar ?? asset('frontend/img/author/1.jpg') }}" 
                                                 alt="{{ $reply->user->name }}" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="media-heading mb-1">{{ $reply->user->name ?? 'Khách' }}</h6>
                                                    <small class="text-muted">{{ $reply->created_at->format('d/m/Y H:i:s') }}</small>
                                                </div>
                                                <form action="{{ route('admin.product-comments.destroy', $reply->id) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Xóa trả lời này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="reply-content p-2 bg-light rounded">
                                                <p class="mb-0">{{ $reply->content }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center mt-4">
                            <i class="zmdi zmdi-comment-outline text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-2">Chưa có trả lời nào cho bình luận này.</p>
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
    font-size: 16px;
}

.reply-item {
    position: relative;
}

.reply-item::before {
    content: '';
    position: absolute;
    left: -15px;
    top: 20px;
    width: 10px;
    height: 10px;
    background: #007bff;
    border-radius: 50%;
}

.comment-content, .reply-content {
    border: 1px solid #e9ecef;
}

.stats .list-group-item {
    border: none;
    padding: 0.5rem 0;
}
</style>
@endsection 