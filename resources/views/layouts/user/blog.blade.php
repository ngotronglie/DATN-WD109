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
                                <h1 class="breadcrumbs-title">Blog</h1>
                                <ul class="breadcrumb-list">
                                    <li><a href="index.html">Home</a></li>
                                    <li>Blog</li>
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
                        <!-- blog-option start -->
                        <div class="col-lg-12">
                            <div class="blog-option box-shadow mb-30  clearfix">
                                <!-- categories -->
                                <div class="dropdown f-left">
                                    <button class="option-btn">
                                        Categories
                                        <i class="zmdi zmdi-chevron-down"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-width mt-30">
                                        <aside class="widget widget-categories box-shadow">
                                            <h6 class="widget-title border-left mb-20">Categories</h6>
                                            <div id="cat-treeview" class="product-cat">
                                                <ul>
                                                    <li class="closed"><a href="#">Brand One</a>
                                                        <ul>
                                                            <li><a href="#">Mobile</a></li>
                                                            <li><a href="#">Tab</a></li>
                                                            <li><a href="#">Watch</a></li>
                                                            <li><a href="#">Head Phone</a></li>
                                                            <li><a href="#">Memory</a></li>
                                                        </ul>
                                                    </li>
                                                    <li class="open"><a href="#">Brand Two</a>
                                                        <ul>
                                                            <li><a href="#">Mobile</a></li>
                                                            <li><a href="#">Tab</a></li>
                                                            <li><a href="#">Watch</a></li>
                                                            <li><a href="#">Head Phone</a></li>
                                                            <li><a href="#">Memory</a></li>
                                                        </ul>
                                                    </li>
                                                    <li class="closed"><a href="#">Accessories</a>
                                                        <ul>
                                                            <li><a href="#">Footwear</a></li>
                                                            <li><a href="#">Sunglasses</a></li>
                                                            <li><a href="#">Watches</a></li>
                                                            <li><a href="#">Utilities</a></li>
                                                        </ul>
                                                    </li>
                                                    <li class="closed"><a href="#">Top Brands</a>
                                                        <ul>
                                                            <li><a href="#">Mobile</a></li>
                                                            <li><a href="#">Tab</a></li>
                                                            <li><a href="#">Watch</a></li>
                                                            <li><a href="#">Head Phone</a></li>
                                                            <li><a href="#">Memory</a></li>
                                                        </ul>
                                                    </li>
                                                    <li class="closed"><a href="#">Jewelry</a>
                                                        <ul>
                                                            <li><a href="#">Footwear</a></li>
                                                            <li><a href="#">Sunglasses</a></li>
                                                            <li><a href="#">Watches</a></li>
                                                            <li><a href="#">Utilities</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </aside>
                                    </div>
                                </div>
                                <!-- recent-product -->
                                <div class="dropdown f-left">
                                    <button class="option-btn">
                                        Recent Post
                                        <i class="zmdi zmdi-chevron-down"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-width mt-30">
                                        <aside class="widget widget-product box-shadow">
                                            <h6 class="widget-title border-left mb-20">recent products</h6>
                                            <!-- product-item start -->
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <a href="single-product.html">
                                                        <img src="{{asset('frontend/img/cart/4.jpg')}}" alt=""/>
                                                    </a>
                                                </div>
                                                <div class="product-info">
                                                    <h6 class="product-title multi-line mt-10">
                                                        <a href="single-product.html">Dummy Blog Name</a>
                                                    </h6>
                                                </div>
                                            </div>
                                            <!-- product-item end -->
                                            <!-- product-item start -->
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <a href="single-product.html">
                                                        <img src="{{asset('frontend/img/cart/5.jpg')}}" alt=""/>
                                                    </a>
                                                </div>
                                                <div class="product-info">
                                                    <h6 class="product-title multi-line mt-10">
                                                        <a href="single-product.html">Dummy Blog Name</a>
                                                    </h6>
                                                </div>
                                            </div>
                                            <!-- product-item end -->
                                            <!-- product-item start -->
                                            <div class="product-item">
                                                <div class="product-img">
                                                    <a href="single-product.html">
                                                        <img src="{{asset('frontend/img/cart/6.jpg')}}" alt=""/>
                                                    </a>
                                                </div>
                                                <div class="product-info">
                                                    <h6 class="product-title multi-line mt-10">
                                                        <a href="single-product.html">Dummy Blog Name</a>
                                                    </h6>
                                                </div>
                                            </div>
                                            <!-- product-item end -->
                                        </aside>
                                    </div>
                                </div>
                                <!-- Tags -->
                                <div class="dropdown f-left">
                                    <button class="option-btn">
                                        Tags
                                        <i class="zmdi zmdi-chevron-down"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-width mt-30">
                                        <aside class="widget widget-tags box-shadow">
                                            <h6 class="widget-title border-left mb-20">Tags</h6>
                                            <ul class="widget-tags-list">
                                                <li><a href="#">Bleckgerry ios</a></li>
                                                <li><a href="#">Symban</a></li>
                                                <li><a href="#">IOS</a></li>
                                                <li><a href="#">Bleckgerry</a></li>
                                                <li><a href="#">Windows Phone</a></li>
                                                <li><a href="#">Windows Phone</a></li>
                                                <li><a href="#">Androids</a></li>
                                            </ul>
                                        </aside>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- blog-option end -->
                    </div>
                    <div class="row">
                        @forelse($blogs as $blog)
                        <!-- blog-item start -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                @if($blog->image)
                                    <img src="{{ $blog->image }}" class="card-img-top" alt="{{ $blog->slug }}">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $blog->slug }}</h5>
                                    <p class="card-text">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($blog->content), 100) }}
                                    </p>
                                    <a href="{{ route('blog.detail.show', $blog->slug) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                                </div>
                                <div class="card-footer text-muted small">
                                    Tác giả: {{ $blog->user->name ?? 'N/A' }} | {{ $blog->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                        <!-- blog-item end -->
                        @empty
                        <div class="col-12">Chưa có bài viết nào.</div>
                        @endforelse
                    </div>
                    
                    <!-- Pagination -->
                    <div class="row">
                        <div class="col-12">
                            <div class="pagination-wrapper text-center">
                                {{ $blogs->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- BLOG SECTION END -->

        </div>
        <!-- End page content -->
@endsection