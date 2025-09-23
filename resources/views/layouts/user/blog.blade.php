@extends('index.clientdashboard')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/blog-custom.css') }}">
@endsection

@section('content')

        <!-- Shopee-style Breadcrumbs -->
        <div class="shopee-breadcrumbs">
            <div class="container">
                <div class="breadcrumb-nav">
                    <a href="{{ route('home') }}" class="breadcrumb-link">
                        <i class="zmdi zmdi-home"></i>
                        Trang chủ
                    </a>
                    <i class="zmdi zmdi-chevron-right breadcrumb-arrow"></i>
                    <span class="breadcrumb-current">Blog</span>
                </div>
            </div>
        </div>

        <!-- Main Blog Section -->
        <section class="blog-section">
            <div class="container">
                @auth
                    @if(Auth::user()->is_admin)
                        <div class="row mb-4">
                            <div class="col-12">
                                <a href="{{ route('blog.detail.create') }}" class="btn btn-primary">
                                    <i class="zmdi zmdi-plus"></i> Tạo bài viết mới
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth
                
                <div class="row">
                    <div class="col-lg-9 order-lg-2 order-1">
                        <div class="blog-content">
                            <!-- Blog Options -->
                            <div class="blog-options mb-4">
                                <div class="row align-items-center justify-content-end">
                                    <div class="col-md-4">
                                        <div class="blog-controls">
                                            <form method="GET" action="{{ route('client.blog.index') }}" id="sortForm" class="sort-form">
                                                <div class="sort-wrapper">
                                                    <label for="sort-select" class="sort-label">
                                                        <i class="zmdi zmdi-sort"></i>
                                                        Sắp xếp:
                                                    </label>
                                                    <select name="sort" id="sort-select" onchange="document.getElementById('sortForm').submit()" class="form-select sort-select">
                                                        <option value="">Mặc định</option>
                                                        <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Tên A-Z</option>
                                                        <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Tên Z-A</option>
                                                    </select>
                                                </div>
                                                @foreach(request()->except('sort', 'page') as $key => $value)
                                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                                @endforeach
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Blog Posts Grid -->
                            <div class="blog-posts-container">
                                <div class="row" id="blog-posts-grid">
                                    @forelse($blogs as $blog)
                                        <div class="col-lg-4 col-md-6 mb-4">
                                            <div class="blog-card">
                                                <div class="blog-image-container">
                                                    <a href="{{ route('blog.detail.show', $blog->slug) }}">
                                                        @if($blog->image)
                                                            <img src="{{ $blog->image }}" alt="{{ $blog->slug }}" class="blog-image">
                                                        @else
                                                            <img src="{{ asset('images/no-image.png') }}" alt="{{ $blog->slug }}" class="blog-image">
                                                        @endif
                                                    </a>
                                                    
                                                </div>
                                                
                                                <div class="blog-content">
                                                    <h5 class="blog-title">
                                                        <a href="{{ route('blog.detail.show', $blog->slug) }}">{{ $blog->slug }}</a>
                                                    </h5>
                                                    
                                                    <p class="blog-excerpt">
                                                        {{ \Illuminate\Support\Str::limit(strip_tags($blog->content), 120) }}
                                                    </p>
                                                    
                                                    <div class="blog-footer">
                                                        <div class="blog-author">
                                                            <i class="zmdi zmdi-account"></i>
                                                            {{ $blog->user->name ?? 'N/A' }}
                                                        </div>
                                                        <div class="blog-actions">
                                                            <a href="{{ route('blog.detail.show', $blog->slug) }}" class="read-more-btn">
                                                                Đọc thêm <i class="zmdi zmdi-arrow-right"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="no-blogs text-center py-5">
                                                <i class="zmdi zmdi-file-text-o fa-5x text-muted mb-4"></i>
                                                <h3 class="text-muted mb-3">Chưa có bài viết nào</h3>
                                                <p class="text-muted">Hãy quay lại sau để đọc những bài viết mới nhất!</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="pagination-container mt-4">
                                {{ $blogs->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 order-lg-1 order-2">
                        <!-- Blog Sidebar -->
                        <div class="blog-sidebar">
                            <!-- Categories Filter -->
                            <div class="filter-widget">
                                <h6 class="filter-title">Danh mục</h6>
                                <div class="filter-content">
                                    <ul class="category-list">
                                        <li class="category-item">
                                            <a href="{{ route('client.blog.index') }}" class="category-link {{ !request('category') ? 'active' : '' }}">
                                                Tất cả
                                            </a>
                                        </li>
                                        @foreach($categories ?? [] as $category)
                                            <li class="category-item">
                                                <a href="{{ route('client.blog.index', ['category' => $category->slug]) }}" 
                                                   class="category-link {{ request('category') == $category->slug ? 'active' : '' }}">
                                                    {{ $category->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <!-- Recent Posts -->
                            <div class="filter-widget">
                                <h6 class="filter-title">Bài viết gần đây</h6>
                                <div class="filter-content">
                                    <div class="recent-posts">
                                        @foreach($recentBlogs ?? [] as $recentBlog)
                                            <div class="recent-post-item">
                                                <div class="recent-post-image">
                                                    <a href="{{ route('blog.detail.show', $recentBlog->slug) }}">
                                                        @if($recentBlog->image)
                                                            <img src="{{ $recentBlog->image }}" alt="{{ $recentBlog->slug }}">
                                                        @else
                                                            <img src="{{ asset('images/no-image.png') }}" alt="{{ $recentBlog->slug }}">
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="recent-post-info">
                                                    <h6 class="recent-post-title">
                                                        <a href="{{ route('blog.detail.show', $recentBlog->slug) }}">{{ $recentBlog->slug }}</a>
                                                    </h6>
                                                    <div class="recent-post-date">
                                                        {{ $recentBlog->created_at->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End page content -->
@endsection

@section('script-client')
<script>
</script>

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

/* Main Blog Section */
.blog-section {
    background: #f8f9fa;
    padding: 20px 0;
    min-height: 80vh;
}

/* Blog Options */
.blog-options {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

/* Blog Controls */
.blog-controls {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    height: 100%;
}

.sort-form {
    width: 100%;
}

.sort-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 8px 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}

.sort-wrapper:hover {
    border-color: #ee4d2d;
    box-shadow: 0 2px 8px rgba(238, 77, 45, 0.15);
}

.sort-label {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #666;
    font-size: 14px;
    font-weight: 500;
    margin: 0;
    white-space: nowrap;
}

.sort-label i {
    color: #ee4d2d;
    font-size: 16px;
}

.sort-select {
    border: none;
    background: transparent;
    padding: 4px 8px;
    font-size: 14px;
    font-weight: 500;
    color: #333;
    cursor: pointer;
    outline: none;
    min-width: 140px;
}

.sort-select:focus {
    box-shadow: none;
    border: none;
    outline: none;
}

.sort-select option {
    padding: 8px 12px;
    background: #fff;
    color: #333;
}

/* Blog Cards */
.blog-posts-container {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 20px;
}

.blog-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    overflow: hidden;
    height: 100%;
    border: 1px solid #f0f0f0;
}

.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #ee4d2d;
}

.blog-image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.blog-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.blog-card:hover .blog-image {
    transform: scale(1.05);
}


.blog-content {
    padding: 20px;
}

.blog-title {
    font-size: 16px;
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: 12px;
    height: 2.2em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.blog-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s;
}

.blog-title a:hover {
    color: #ee4d2d;
}

.blog-excerpt {
    font-size: 14px;
    color: #666;
    line-height: 1.6;
    margin-bottom: 15px;
    height: 4.2em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

.blog-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

.blog-author {
    font-size: 12px;
    color: #999;
    display: flex;
    align-items: center;
    gap: 5px;
}

.read-more-btn {
    color: #ee4d2d;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s;
}

.read-more-btn:hover {
    color: #d73502;
    transform: translateX(3px);
}

/* Sidebar */
.blog-sidebar {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 20px;
}

.filter-widget {
    margin-bottom: 25px;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 20px;
}

.filter-widget:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.filter-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #ee4d2d;
    display: inline-block;
}

.category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-item {
    margin-bottom: 8px;
}

.category-link {
    display: block;
    padding: 8px 12px;
    color: #666;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.2s;
    font-size: 14px;
}

.category-link:hover,
.category-link.active {
    background: #fff5f5;
    color: #ee4d2d;
}

.recent-posts {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.recent-post-item {
    display: flex;
    gap: 10px;
    padding: 8px;
    border-radius: 6px;
    transition: background 0.2s;
}

.recent-post-item:hover {
    background: #f8f9fa;
}

.recent-post-image {
    width: 50px;
    height: 50px;
    border-radius: 4px;
    overflow: hidden;
    flex-shrink: 0;
}

.recent-post-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.recent-post-info {
    flex: 1;
    min-width: 0;
}

.recent-post-title {
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 4px;
    line-height: 1.3;
}

.recent-post-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s;
}

.recent-post-title a:hover {
    color: #ee4d2d;
}

.recent-post-date {
    font-size: 11px;
    color: #999;
}

.tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tag-link {
    background: #f8f9fa;
    color: #666;
    padding: 4px 8px;
    border-radius: 12px;
    text-decoration: none;
    font-size: 12px;
    transition: all 0.2s;
    border: 1px solid #e9ecef;
}

.tag-link:hover {
    background: #ee4d2d;
    color: #fff;
    border-color: #ee4d2d;
}

/* Pagination */
.pagination-container {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    text-align: center;
}


/* No Blogs State */
.no-blogs {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    padding: 40px 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .blog-options {
        padding: 15px;
    }
    
    .blog-controls {
        text-align: center;
    }
    
    .blog-image-container {
        height: 180px;
    }
    
    .blog-sidebar {
        margin-top: 20px;
        padding: 15px;
    }
}
</style>
@endsection