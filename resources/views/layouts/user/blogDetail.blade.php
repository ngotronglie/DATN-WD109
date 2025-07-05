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
                                <h1 class="breadcrumbs-title">Single Blog</h1>
                                <ul class="breadcrumb-list">
                                    <li><a href="index.html">Home</a></li>
                                    <li>Single Blog</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- BREADCRUMBS SETCTION END -->

        <!-- Start page content -->
        <section id="page-content" class="page-wrapper section">

            <!-- BLOG SECTION START -->
            <div class="blog-section mb-50">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="blog-details-area">
                                <!-- blog-details-photo -->
                                <div class="blog-details-photo bg-img-1 p-20 mb-30">
                                    <img src="{{ $blog->image ? asset($blog->image) : asset('frontend/img/blog/10.jpg') }}" alt="{{ $blog->slug }}">
                                    <div class="today-date bg-img-1">
                                        <span class="meta-date">{{ $blog->created_at->format('d') }}</span>
                                        <span class="meta-month">{{ $blog->created_at->format('M') }}</span>
                                    </div>
                                </div>
                                <!-- blog-like-share -->
                                <ul class="blog-like-share mb-20">
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-eye"></i>{{ $blog->view }} Views</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-account"></i>{{ $blog->user->name ?? 'Admin' }}</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-calendar"></i>{{ $blog->created_at->format('d/m/Y') }}</a>
                                    </li>
                                </ul>
                                <!-- blog-details-title -->
                                <h3 class="blog-details-title mb-30">{{ $blog->slug }}</h3>
                                
                                @auth
                                    @if(Auth::user()->is_admin)
                                        <div class="admin-actions mb-30">
                                            <a href="{{ route('blog.detail.edit', $blog->slug) }}" class="btn btn-primary btn-sm">
                                                <i class="zmdi zmdi-edit"></i> Chỉnh sửa
                                            </a>
                                            <form action="{{ route('blog.detail.destroy', $blog->slug) }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="zmdi zmdi-delete"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                                <!-- blog-description -->
                                <div class="blog-description mb-60">
                                    {!! $blog->content !!}
                                </div>
                                
                                <!-- blog tags -->
                                @if($blog->tags->count() > 0)
                                <div class="blog-tags mb-30">
                                    <h4>Tags:</h4>
                                    <div class="tags-list">
                                        @foreach($blog->tags as $tag)
                                            <a href="{{ route('blog.detail.tag', $tag->id) }}" class="tag-item">{{ $tag->name_tag }}</a>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                <!-- blog-share-tags -->
                                <div class="blog-share-tags box-shadow clearfix mb-60">
                                    <div class="blog-share f-left">
                                        <p class="share-tags-title f-left">share</p>
                                        <ul class="footer-social f-left">
                                            <li>
                                                <a class="facebook" href="#" title="Facebook"><i class="zmdi zmdi-facebook"></i></a>
                                            </li>
                                            <li>
                                                <a class="google-plus" href="#" title="Google Plus"><i class="zmdi zmdi-google-plus"></i></a>
                                            </li>
                                            <li>
                                                <a class="twitter" href="#" title="Twitter"><i class="zmdi zmdi-twitter"></i></a>
                                            </li>
                                            <li>
                                                <a class="rss" href="#" title="RSS"><i class="zmdi zmdi-rss"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="blog-tags f-right">
                                        <p class="share-tags-title f-left">Tags</p>
                                        <ul class="blog-tags-list f-left">
                                            @forelse($blog->tags as $tag)
                                                <li><a href="{{ route('blog.detail.tag', $tag->id) }}">{{ $tag->name_tag }}</a></li>
                                            @empty
                                                <li><span>Không có tags</span></li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                                <!-- author-post -->
                                <div class="media author-post box-shadow mb-60">
                                    <div class="media-left pr-20">
                                        <a href="#">
                                            <img class="media-object" src="{{asset('frontend/img/author/1.jpg')}}" alt="{{ $blog->user->name ?? 'Admin' }}">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="media-heading">
                                            <a href="#">{{ $blog->user->name ?? 'Admin' }}</a>
                                        </h6>
                                        <p class="mb-0">Tác giả của bài viết này.</p>
                                    </div>
                                </div>
                                <!-- comments on t this post -->
                                <div class="post-comments mb-60">
                                    <h4 class="blog-section-title border-left mb-30">comments on this product</h4>
                                    <!-- single-comments -->
                                    <div class="media mt-30">
                                        <div class="media-left pr-30">
                                            <a href="#"><img class="media-object" src="{{asset('frontend/img/author/2.jpg')}}" alt="#"></a>
                                        </div>
                                        <div class="media-body">
                                            <div class="clearfix">
                                                <div class="name-commenter f-left">
                                                    <h6 class="media-heading"><a href="#">Gerald Barnes</a></h6>
                                                    <p class="mb-10">27 Jun, 2019 at 2:30pm</p>
                                                </div>
                                                <ul class="reply-delate f-right">
                                                    <li><a href="#">Reply</a></li>
                                                    <li>/</li>
                                                    <li><a href="#">Delate</a></li>
                                                </ul>
                                            </div>
                                            <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer accumsan egestas elese ifend. Phasellus a felis atestese bibendum feugiat ut eget eni Praesent  messages in con sectetur posuere dolor non.</p>
                                        </div>
                                    </div>
                                    <!-- single-comments -->
                                    <div class="media mt-30">
                                        <div class="media-left pr-30">
                                            <a href="#"><img class="media-object" src="{{asset('frontend/img/author/3.jpg')}}" alt="#"></a>
                                        </div>
                                        <div class="media-body">
                                            <div class="clearfix">
                                                <div class="name-commenter f-left">
                                                    <h6 class="media-heading"><a href="#">Gerald Barnes</a></h6>
                                                    <p class="mb-10">27 Jun, 2019 at 2:30pm</p>
                                                </div>
                                                <ul class="reply-delate f-right">
                                                    <li><a href="#">Reply</a></li>
                                                    <li>/</li>
                                                    <li><a href="#">Delate</a></li>
                                                </ul>
                                            </div>
                                            <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer accumsan egestas elese ifend. Phasellus a felis atestese bibendum feugiat ut eget eni Praesent  messages in con sectetur posuere dolor non.</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- leave your comment -->
                                <div class="leave-comment">
                                    <h4 class="blog-section-title border-left mb-30">leave your comment</h4>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <input type="text" name="name" placeholder="Subas Chandra Das">
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" name="email" placeholder="Email address here...">
                                        </div>
                                        <div class="col-lg-12">
                                            <input type="text" name="subject" placeholder="Subject here...">
                                        </div>
                                        <div class="col-lg-12">
                                            <textarea class="custom-textarea" placeholder="Your comment here..."></textarea>
                                        </div>
                                    </div>
                                    <button class="submit-btn-1 black-bg mt-30 btn-hover-2" type="submit">submit comment</button>
                                </div>
                                <!--  -->
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <!-- widget-search -->
                            <aside class="widget-search mb-30">
                                <form action="{{ route('blog.detail.search') }}" method="GET">
                                    <input type="text" name="keyword" placeholder="Tìm kiếm bài viết..." 
                                           value="{{ $keyword ?? '' }}">
                                    <button type="submit"><i class="zmdi zmdi-search"></i></button>
                                </form>
                            </aside>
                            <!-- widget-categories -->
                            <aside class="widget widget-categories box-shadow mb-30">
                                <h6 class="widget-title border-left mb-20">Tags</h6>
                                <div id="cat-treeview" class="product-cat">
                                    <ul>
                                        @forelse($tags as $tag)
                                            <li>
                                                <a href="{{ route('blog.detail.tag', $tag->id) }}">
                                                    {{ $tag->name_tag }} ({{ $tag->blogs_count }})
                                                </a>
                                            </li>
                                        @empty
                                            <li><span>Không có tags</span></li>
                                        @endforelse
                                    </ul>
                                </div>
                            </aside>
                            <!-- widget-product -->
                            <aside class="widget widget-product box-shadow mb-30">
                                <h6 class="widget-title border-left mb-20">recent blogs</h6>
                                @forelse($recentBlogs as $recentBlog)
                                <!-- product-item start -->
                                <div class="product-item">
                                    <div class="product-img">
                                        <a href="{{ route('blog.detail.show', $recentBlog->slug) }}">
                                            <img src="{{ $recentBlog->image ? asset($recentBlog->image) : asset('frontend/img/blog/1.jpg') }}" alt="{{ $recentBlog->slug }}"/>
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <h6 class="product-title">
                                            <a href="{{ route('blog.detail.show', $recentBlog->slug) }}">{{ $recentBlog->slug }}</a>
                                        </h6>
                                        <h3 class="pro-price">$ 869.00</h3>
                                    </div>
                                </div>
                                <!-- product-item end -->
                                <!-- product-item start -->
                                <div class="product-item">
                                    <div class="product-img">
                                        <a href="single-product.html">
                                            <img src="{{asset('frontend/img/product/8.jpg')}}" alt=""/>
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <h6 class="product-title">
                                            <a href="single-product.html">Product Name</a>
                                        </h6>
                                        <h3 class="pro-price">$ 869.00</h3>
                                    </div>
                                </div>
                                <!-- product-item end -->
                                <!-- product-item start -->
                                <div class="product-item">
                                    <div class="product-img">
                                        <a href="single-product.html">
                                            <img src="{{asset('frontend/img/product/12.jpg')}}" alt=""/>
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <h6 class="product-title">
                                            <a href="single-product.html">Product Name</a>
                                        </h6>
                                        <h3 class="pro-price">$ 869.00</h3>
                                    </div>
                                </div>
                                <!-- product-item end -->
                            </aside>
                            <!-- operating-system -->
                            <aside class="widget operating-system box-shadow">
                                <h6 class="widget-title border-left mb-20">operating system</h6>
                                <form action="">
                                    <label><input type="checkbox" name="operating-1" value="phone-1">Windows Phone</label><br>
                                    <label><input type="checkbox" name="operating-1" value="phone-1">Bleckgerry ios</label><br>
                                    <label><input type="checkbox" name="operating-1" value="phone-1">Android</label><br>
                                    <label><input type="checkbox" name="operating-1" value="phone-1">ios</label><br>
                                    <label><input type="checkbox" name="operating-1" value="phone-1">Windows Phone</label><br>
                                    <label><input type="checkbox" name="operating-1" value="phone-1">Symban</label><br>
                                    <label class="mb-0"><input type="checkbox" name="operating-1" value="phone-1">Bleckgerry os</label><br>
                                </form>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
            <!-- BLOG SECTION END -->

        </section>
        <!-- End page content -->

@endsection