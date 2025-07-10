@extends('index.clientdashboard')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/blog-custom.css') }}">
@endsection

@section('content')
    <!-- BREADCRUMBS SETCTION START -->
    <div class="breadcrumbs-section plr-200 mb-80 section">
        <div class="breadcrumbs overlay-bg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumbs-inner">
                            <h1 class="breadcrumbs-title">Bài viết</h1>
                            <ul class="breadcrumb-list">
                                <li><a href="/">Trang chủ</a></li>
                                <li>Bài viết</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMBS SETCTION END -->

    <!-- Start page content -->
    <div id="page-content" class="page-wrapper section">
        <!-- BLOG SECTION START -->
        <div class="blog-section mb-50">
            <div class="container">
                <div class="row">
                    <!-- blog-option start -->
                    <div class="col-lg-12">
                        <div class="blog-option box-shadow mb-30  clearfix">
                            <!-- categories -->
                            <div class="dropdown f-left">
                                <button class="option-btn">
                                    Tác giả
                                    <i class="zmdi zmdi-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu dropdown-width mt-30">
                                    <h6 class="widget-title border-left mb-20">Tác giả</h6>
                                    <div id="author-treeview" class="product-cat">
                                        <ul>
                                            <li>
                                                <a href="{{ route('blog') }}" class="author-filter-link" style="font-weight:bold;">Tất cả tác giả</a>
                                            </li>
                                            @if(isset($authors) && count($authors))
                                                @foreach($authors as $author)
                                                    <li><a href="{{ route('blog.author', $author->id) }}" class="author-filter-link">{{ $author->name }}</a></li>
                                                @endforeach
                                            @else
                                                <li>Không có tác giả</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- recent-product -->
                            <div class="dropdown f-left">
                                <button class="option-btn">
                                    Bài viết mới
                                    <i class="zmdi zmdi-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu dropdown-width mt-30">
                                    <aside class="widget widget-product box-shadow">
                                        <h6 class="widget-title border-left mb-20">Bài viết mới</h6>
                                        @if(isset($recentBlogs) && count($recentBlogs))
                                            @foreach($recentBlogs as $recent)
                                                <div class="product-item">
                                                    <div class="product-img">
                                                        <a href="#">
                                                            <img src="{{ $recent->image ? asset($recent->image) : asset('frontend/img/blog/1.jpg') }}" alt="{{ $recent->slug }}"/>
                                                        </a>
                                                    </div>
                                                    <div class="product-info">
                                                        <h6 class="product-title multi-line mt-10">
                                                            <a href="#">{{ $recent->slug }}</a>
                                                        </h6>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="product-item">
                                                <div class="product-info">Chưa có bài viết mới.</div>
                                            </div>
                                        @endif
                                    </aside>
                                </div>
                            </div>
                            <!-- Tags -->
                            <div class="dropdown f-left">
                                <button class="option-btn">
                                    Thẻ
                                    <i class="zmdi zmdi-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu dropdown-width mt-30">
                                    <aside class="widget widget-tags box-shadow">
                                        <h6 class="widget-title border-left mb-20">Thẻ</h6>
                                        <ul class="widget-tags-list">
                                            <li><a href="#">Điện thoại</a></li>
                                            <li><a href="#">Android</a></li>
                                            <li><a href="#">iOS</a></li>
                                            <li><a href="#">Phụ kiện</a></li>
                                            <li><a href="#">Windows Phone</a></li>
                                            <li><a href="#">Máy tính bảng</a></li>
                                            <li><a href="#">Khuyến mãi</a></li>
                                        </ul>
                                    </aside>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- blog-option end -->
                </div>
                <div class="row">
                    @if(isset($blogs) && count($blogs))
                        @foreach($blogs as $blog)
                        <!-- blog-item start -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="blog-item card h-100 shadow-sm border-0">
                                <img src="{{ $blog->image ? asset($blog->image) : asset('frontend/img/blog/1.jpg') }}"
                                     alt="{{ $blog->slug }}"
                                     class="card-img-top rounded-top"
                                     style="object-fit:cover; height:220px;">
                                <div class="blog-desc card-body d-flex flex-column">
                                    <h5 class="blog-title card-title mb-2 text-primary" style="font-size:1.2rem;">
                                        <a href="#" class="text-decoration-none text-primary">{{ $blog->slug }}</a>
                                    </h5>
                                    <p class="card-text text-muted mb-3" style="min-height:60px;">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($blog->content), 100) }}
                                    </p>
                                    <div class="mt-auto">
                                        <a href="#" class="btn btn-outline-primary btn-sm px-3">Xem thêm</a>
                                    </div>
                                </div>
                                <ul class="blog-meta list-inline card-footer bg-white border-0 py-2 mb-0 text-center small">
                                    <li class="list-inline-item me-3">
                                        <i class="zmdi zmdi-favorite text-danger"></i> 89 Lượt thích
                                    </li>
                                    <li class="list-inline-item me-3">
                                        <i class="zmdi zmdi-comments text-info"></i> 59 Bình luận
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="zmdi zmdi-share text-success"></i> 29 Chia sẻ
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- blog-item end -->
                        @endforeach
                    @else
                        <div class="col-12 text-center">Chưa có bài viết nào.</div>
                    @endif
                </div>
            </div>
        </div>
        <!-- BLOG SECTION END -->
    </div>
    <!-- End page content -->
@endsection

@section('script-client')
<script>
    // Lưu vị trí cuộn trước khi chuyển trang
    document.querySelectorAll('.author-filter-link').forEach(function(link) {
        link.addEventListener('click', function() {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        });
    });

    // Khi trang load xong, cuộn về vị trí cũ nếu có
    window.addEventListener('DOMContentLoaded', function() {
        const scrollY = sessionStorage.getItem('scrollPosition');
        if (scrollY !== null) {
            window.scrollTo(0, parseInt(scrollY));
            sessionStorage.removeItem('scrollPosition');
        }
    });
</script>
@endsection