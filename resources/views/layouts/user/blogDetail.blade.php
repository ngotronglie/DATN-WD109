@extends('index.clientdashboard')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/blog-custom.css') }}">
@endsection

@section('content')
<div class="blog-page-wrapper">
    <div class="container">
        <!-- Shopee-style Breadcrumbs -->
        <div class="shopee-breadcrumbs mb-3">
            <div class="breadcrumb-nav">
                <a href="{{ route('home') }}" class="breadcrumb-link">
                    <i class="zmdi zmdi-home"></i>
                    Trang chủ
                </a>
                <i class="zmdi zmdi-chevron-right breadcrumb-arrow"></i>
                <a href="{{ route('client.blog.index') }}" class="breadcrumb-link">Blog</a>
                <i class="zmdi zmdi-chevron-right breadcrumb-arrow"></i>
                <span class="breadcrumb-current">{{ \Illuminate\Support\Str::limit($blog->slug, 30) }}</span>
            </div>
        </div>
        <div class="row blog-main-row">
            <!-- Sidebar (moved up for mobile) -->
            <aside class="col-lg-3 order-lg-2 mb-4 mb-lg-0">
                <div class="blog-sidebar">
                    <!-- Search Widget -->
                    <div class="sidebar-widget">
                        <h6 class="widget-title">Tìm kiếm</h6>
                        <div class="widget-content">
                            <form action="{{ route('blog.detail.search') }}" method="GET" class="search-form">
                                <div class="search-input-group" style="width:100%;">
                                    <input type="text" name="keyword" placeholder="Tìm kiếm bài viết..."
                                           value="{{ $keyword ?? '' }}" class="search-input" style="width:100%;">
                                    <button type="submit" class="search-btn">
                                        <i class="zmdi zmdi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tags Widget -->
                    <div class="sidebar-widget">
                        <h6 class="widget-title">Tags</h6>
                        <div class="widget-content">
                            <div class="tags-list sidebar-tags-list" style="width:100%;">
                                @forelse($tags as $tag)
                                    <a href="{{ route('blog.detail.tag', $tag->id) }}" class="tag-link">
                                            {{ $tag->name_tag }} ({{ $tag->blogs_count }})
                                        </a>
                                @empty
                                    <span class="no-tags">Không có tags</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Recent Posts Widget -->
                    <div class="sidebar-widget">
                        <h6 class="widget-title">Bài viết gần đây</h6>
                        <div class="widget-content">
                            <div class="recent-posts">
                        @forelse($recentBlogs as $recentBlog)
                                    <div class="recent-post-item">
                                        <div class="recent-post-image">
                                    <a href="{{ route('blog.detail.show', $recentBlog->slug) }}">
                                                <img src="{{ $recentBlog->image ? asset($recentBlog->image) : asset('frontend/img/blog/1.jpg') }}"
                                                     alt="{{ $recentBlog->slug }}">
                                    </a>
                                </div>
                                        <div class="recent-post-info">
                                            <h6 class="recent-post-title">
                                                <a href="{{ route('blog.detail.show', $recentBlog->slug) }}">
                                                    {{ \Illuminate\Support\Str::limit($recentBlog->slug, 50) }}
                                                </a>
                                    </h6>
                                            <div class="recent-post-date">
                                                {{ $recentBlog->created_at->format('d/m/Y') }}
                                            </div>
                                        </div>
                                </div>
                                @empty
                                    <div class="no-posts">Không có bài viết gần đây.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
            <!-- Main Content -->
            <main class="col-lg-9 order-lg-1">
                <div class="blog-detail-container">
                    <!-- Blog Header -->
                    <div class="blog-header mb-4">
                        <h1 class="blog-title">{{ $blog->slug }}</h1>
                        <div class="blog-meta">
                            <div class="meta-item">
                                <i class="zmdi zmdi-account"></i>
                                <span>{{ $blog->user->name ?? 'Admin' }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="zmdi zmdi-calendar"></i>
                                <span>{{ $blog->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="zmdi zmdi-eye"></i>
                                <span>{{ $blog->view }} lượt xem</span>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Image -->
                    <div class="blog-image-container mb-4">
                        <img src="{{ $blog->image ? asset($blog->image) : asset('frontend/img/blog/10.jpg') }}"
                             alt="{{ $blog->slug }}"
                             class="blog-image">
                    </div>

                    <!-- Admin Actions -->
                        @auth
                            @if(Auth::user()->is_admin)
                            <div class="admin-actions mb-4">
                                <a href="{{ route('blog.detail.edit', $blog->slug) }}" class="btn-edit">
                                        <i class="zmdi zmdi-edit"></i> Chỉnh sửa
                                    </a>
                                    <form action="{{ route('blog.detail.destroy', $blog->slug) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                    @csrf
                                    @method('DELETE')
                                <button type="submit" class="btn-delete">
                                        <i class="zmdi zmdi-delete"></i> Xóa
                                    </button>
                                </form>
                            </div>
                            @endif
                        @endauth

                    <!-- Blog Content -->
                    <div class="blog-content">
                            {!! $blog->content !!}
                        </div>

                    <!-- Blog Tags -->
                        @if($blog->tags->count() > 0)
                    <div class="blog-tags-section mb-4">
                        <h5 class="tags-title">Tags:</h5>
                            <div class="tags-list">
                                @foreach($blog->tags as $tag)
                                    <a href="{{ route('blog.detail.tag', $tag->id) }}" class="tag-item">{{ $tag->name_tag }}</a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    <!-- Share Section -->
                    <div class="blog-share-section mb-4">
                        <div class="share-content">
                            <h5 class="share-title">Chia sẻ:</h5>
                            <div class="social-links">
                                <a href="#" class="social-link facebook" title="Facebook">
                                    <i class="zmdi zmdi-facebook"></i>
                                </a>
                                <a href="#" class="social-link twitter" title="Twitter">
                                    <i class="zmdi zmdi-twitter"></i>
                                </a>
                                <a href="#" class="social-link google" title="Google Plus">
                                    <i class="zmdi zmdi-google-plus"></i>
                                </a>
                                <a href="#" class="social-link linkedin" title="LinkedIn">
                                    <i class="zmdi zmdi-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Author Section -->
                    <div class="author-section mb-4">
                        <div class="author-card">
                            <div class="author-avatar">
                                <img src="{{asset('frontend/img/author/1.jpg')}}" alt="{{ $blog->user->name ?? 'Admin' }}">
                            </div>
                            <div class="author-info">
                                <h6 class="author-name">{{ $blog->user->name ?? 'Admin' }}</h6>
                                <p class="author-bio">Tác giả của bài viết này.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="comments-section mb-4">
                        <h4 class="comments-title">Bình luận</h4>

                        <!-- Comments List -->
                        <div class="comments-list">
                                @foreach($blog->comments()->whereNull('parent_id')->latest()->get() as $comment)
                                <div class="comment-item">
                                    <div class="comment-header">
                                        <div class="comment-author">
                                            <strong>{{ $comment->user->name ?? 'Khách' }}</strong>
                                        </div>
                                        <div class="comment-date">
                                            {{ $comment->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <div class="comment-content">
                                        {{ $comment->content }}
                                    </div>

                                    <!-- Replies -->
                                        @foreach($comment->replies as $reply)
                                        <div class="comment-reply">
                                            <div class="comment-header">
                                                <div class="comment-author">
                                                    <strong>{{ $reply->user->name ?? 'Khách' }}</strong>
                                                </div>
                                                <div class="comment-date">
                                                    {{ $reply->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                            <div class="comment-content">
                                                {{ $reply->content }}
                                            </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>

                        <!-- Comment Form -->
                            @if(Auth::check())
                        <div class="comment-form">
                            <h5 class="form-title">Viết bình luận</h5>
                            <form action="{{ route('comments.store', $blog->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <textarea name="content" class="form-control" rows="4" placeholder="Nhập bình luận của bạn..."></textarea>
                                </div>
                                <button type="submit" class="btn-submit">
                                    <i class="zmdi zmdi-mail-send"></i>
                                    Gửi bình luận
                                </button>
                            </form>
                        </div>
                            @else
                        <div class="login-required">
                            <i class="zmdi zmdi-account-circle"></i>
                            <p>Bạn cần <a href="{{ route('auth.login') }}">đăng nhập</a> để bình luận.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection

@section('script-client')
<style>
/* Shopee-style Breadcrumbs */
.shopee-breadcrumbs {
    background: #fff;
    padding: 16px 0;
    border-bottom: 1px solid #f0f0f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.breadcrumb-link {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #ee4d2d;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s;
    font-weight: 500;
}

.breadcrumb-link:hover {
    background: #fff5f5;
    color: #d73502;
}

.breadcrumb-arrow {
    color: #ccc;
    font-size: 16px;
    margin: 0 4px;
}

.breadcrumb-current {
    color: #333;
    font-weight: 600;
    padding: 4px 8px;
    background: #f8f9fa;
    border-radius: 4px;
}

/* Layout wrapper for better spacing */
.blog-page-wrapper {
    background: #f8f9fa;
    min-height: 80vh;
    padding-top: 10px;
    padding-bottom: 30px;
}

/* Main Blog Detail Section */
.blog-detail-section {
    background: #f8f9fa;
    padding: 20px 0;
    min-height: 80vh;
}

.blog-detail-container {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 30px;
    margin-bottom: 20px;
}

/* Blog Header */
.blog-header {
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 20px;
    margin-bottom: 20px;
}

.blog-title {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin-bottom: 15px;
    line-height: 1.3;
}

.blog-meta {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #666;
    font-size: 14px;
}

.meta-item i {
    color: #ee4d2d;
    font-size: 16px;
}

/* Blog Image */
.blog-image-container {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.blog-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.blog-image:hover {
    transform: scale(1.02);
}

/* Admin Actions */
.admin-actions {
    display: flex;
    gap: 10px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.btn-edit, .btn-delete {
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}

.btn-edit {
    background: #007bff;
    color: #fff;
}

.btn-edit:hover {
    background: #0056b3;
    color: #fff;
}

.btn-delete {
    background: #dc3545;
    color: #fff;
}

.btn-delete:hover {
    background: #c82333;
    color: #fff;
}

/* Blog Content */
.blog-content {
    font-size: 16px;
    line-height: 1.8;
    color: #333;
    margin-bottom: 30px;
}

.blog-content h1, .blog-content h2, .blog-content h3, .blog-content h4, .blog-content h5, .blog-content h6 {
    color: #333;
    margin-top: 25px;
    margin-bottom: 15px;
    font-weight: 600;
}

.blog-content p {
    margin-bottom: 15px;
}

.blog-content img {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
    margin: 15px 0;
}

/* Blog Tags */
.blog-tags-section {
    border-top: 1px solid #f0f0f0;
    padding-top: 20px;
}

.tags-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
}

.tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tag-item {
    background: #f0f0f0;
    color: #666;
    padding: 6px 12px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s;
}

.tag-item:hover {
    background: #ee4d2d;
    color: #fff;
}

/* Share Section */
.blog-share-section {
    border-top: 1px solid #f0f0f0;
    padding-top: 20px;
}

.share-content {
    display: flex;
    align-items: center;
    gap: 15px;
}

.share-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.social-links {
    display: flex;
    gap: 10px;
}

.social-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: #fff;
    transition: all 0.3s ease;
}

.social-link.facebook { background: #3b5998; }
.social-link.twitter { background: #1da1f2; }
.social-link.google { background: #dd4b39; }
.social-link.linkedin { background: #0077b5; }

.social-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* Author Section */
.author-section {
    border-top: 1px solid #f0f0f0;
    padding-top: 20px;
}

.author-card {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.author-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
}

.author-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.author-name {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.author-bio {
    color: #666;
    margin: 0;
    font-size: 14px;
}

/* Comments Section */
.comments-section {
    border-top: 1px solid #f0f0f0;
    padding-top: 20px;
}

.comments-title {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
}

.comments-list {
    margin-bottom: 30px;
}

.comment-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.comment-author {
    font-weight: 600;
    color: #333;
}

.comment-date {
    color: #666;
    font-size: 12px;
}

.comment-content {
    color: #555;
    line-height: 1.6;
}

.comment-reply {
    background: #fff;
    border-left: 3px solid #ee4d2d;
    margin-top: 10px;
    padding: 10px 15px;
}

/* Comment Form */
.comment-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}

.form-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
}

.form-group {
    margin-bottom: 15px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.2s;
    resize: vertical;
}

.form-control:focus {
    outline: none;
    border-color: #ee4d2d;
    box-shadow: 0 0 0 3px rgba(238, 77, 45, 0.1);
}

.btn-submit {
    background: #ee4d2d;
    color: #fff;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-submit:hover {
    background: #d73502;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(238, 77, 45, 0.3);
}

.login-required {
    text-align: center;
    padding: 30px;
    background: #f8f9fa;
    border-radius: 8px;
    color: #666;
}

.login-required i {
    font-size: 48px;
    color: #ccc;
    margin-bottom: 15px;
}

.login-required a {
    color: #ee4d2d;
    text-decoration: none;
}

/* Responsive: Sidebar above content on mobile */
@media (max-width: 991.98px) {
    .blog-main-row {
        flex-direction: column-reverse;
    }
    .blog-sidebar {
        margin-bottom: 20px;
    }
    .blog-detail-container {
        margin-bottom: 0;
    }
}

/* Sidebar visual separation */
.blog-sidebar {
    position: sticky;
    top: 20px;
    z-index: 2;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.08);
    padding: 16px 12px;
}

/* Sidebar widget spacing and background */
.sidebar-widget {
    margin-bottom: 24px;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 16px;
    background: #fafafa;
    border-radius: 6px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
}
.sidebar-widget:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

/* Search input group fix */
.search-input-group {
    display: flex;
    align-items: center;
    gap: 8px;
}
.search-input {
    flex: 1;
    padding: 8px 12px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    font-size: 14px;
}
.search-btn {
    background: #888; /* Changed from #ee4d2d to gray */
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.2s;
}
.search-btn:hover {
    background: #555; /* Darker gray on hover */
}

/* Sidebar tags list fix */
.sidebar-tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 8px;
}
.tag-link {
    background: #f0f0f0;
    color: #666;
    padding: 6px 12px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 13px;
    transition: all 0.2s;
    white-space: nowrap;
}
.tag-link:hover {
    background: #ee4d2d;
    color: #fff;
}


/* Responsive */
@media (max-width: 768px) {
    .blog-detail-section {
        padding: 15px 0;
    }

    .blog-detail-container {
        padding: 20px;
    }

    .blog-title {
        font-size: 24px;
    }

    .blog-meta {
        flex-direction: column;
        gap: 10px;
    }

    .blog-image {
        height: 250px;
    }

    .share-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .author-card {
        flex-direction: column;
        text-align: center;
    }

    .comment-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }

    .blog-sidebar {
        position: static;
        margin-top: 20px;
    }
}
.search-input-group,
.sidebar-tags-list {
    width: 100%;
}
.sidebar-tags-list {
    justify-content: flex-start;
}
</style>
@endsection
