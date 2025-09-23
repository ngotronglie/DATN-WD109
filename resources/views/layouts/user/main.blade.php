@extends('index.clientdashboard')

@section('content')
    @include('components.client.banner')

    @include('components.client.category-slider', ['categories' => $categories])

    {{-- Flash Sale Section --}}
    @if(isset($flashSales) && $flashSales->isNotEmpty())
        @include('components.client.flash-sale', ['flashSales' => $flashSales, 'limit' => 8])
    @endif

    <!-- START PAGE CONTENT -->
    <section id="page-content" class="page-wrapper section">

      <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @if(isset($discountedProducts) && $discountedProducts->count() > 0)
                        @include('components.client.product', ['products' => $discountedProducts])
                    @elseif(isset($products) && $products->count() > 0)
                        @include('components.client.product', ['products' => $products])
                    @endif
                </div>
            </div>

      </div>


        <!-- BLOG SECTION START -->
        <div class="blog-section section-bg-tb pt-80 pb-55">
            <div class="container">
                <div class="section-title text-center mb-5">
                    <h2 class="mb-3">BÀI VIẾT MỚI NHẤT</h2>
                    <div class="title-divider">
                        <span class="divider-line"></span>
                        <i class="zmdi zmdi-star"></i>
                        <span class="divider-line"></span>
                    </div>
                </div>
                <div class="row">
                    @if(isset($latestBlogs) && $latestBlogs->count() > 0)
                        @foreach($latestBlogs as $blog)
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="blog-item">
                                <div class="blog-img">
                                    <a href="{{ route('blog.detail.show', $blog->slug) }}">
                                        @if($blog->image)
                                            <img src="{{ $blog->image }}" alt="{{ $blog->slug }}" />
                                        @else
                                            <img src="{{ asset('images/no-image.png') }}" alt="{{ $blog->slug }}" />
                                        @endif
                                    </a>
                                </div>
                                <div class="blog-info">
                                    <h5 class="blog-title">
                                        <a href="{{ route('blog.detail.show', $blog->slug) }}">{{ $blog->slug }}</a>
                                    </h5>
                                    <p class="blog-excerpt">
                                        {{ Str::limit(strip_tags($blog->content), 100) }}
                                    </p>
                                    <div class="blog-meta">
                                        <span class="blog-date">
                                            <i class="zmdi zmdi-calendar"></i> {{ $blog->created_at->format('d/m/Y') }}
                                        </span>
                                        <span class="blog-author">
                                            <i class="zmdi zmdi-account"></i> {{ $blog->user->name ?? 'N/A' }}
                                        </span>
                                        <a href="{{ route('blog.detail.show', $blog->slug) }}" class="read-more">
                                            Xem thêm <i class="zmdi zmdi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        @for($i = 1; $i <= 4; $i++)
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="blog-item">
                                <div class="blog-img">
                                    <a href="#">
                                        <img src="{{ asset('frontend/img/blog/' . $i . '.jpg') }}" alt="Bài viết mẫu" />
                                    </a>
                                </div>
                                <div class="blog-info">
                                    <h5 class="blog-title">
                                        <a href="#">Tiêu đề bài viết mẫu {{ $i }}</a>
                                    </h5>
                                    <p class="blog-excerpt">
                                        Đây là nội dung mẫu của bài viết. Nội dung thực tế sẽ được hiển thị tại đây.
                                    </p>
                                    <div class="blog-meta">
                                        <span class="blog-date">
                                            <i class="zmdi zmdi-calendar"></i> {{ now()->format('d/m/Y') }}
                                        </span>
                                        <a href="#" class="read-more">
                                            Xem thêm <i class="zmdi zmdi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endfor
                    @endif
                </div>
            </div>
        </div>
                        <!-- blog-item end -->
                    </div>
                </div>
            </div>
        </div>
        <!-- BLOG SECTION END -->

        <!-- NEWSLETTER SECTION START -->
        <div class="newsletter-section section-bg-tb pt-60 pb-80">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <div class="newsletter">
                            <div class="newsletter-info text-center">
                                <h2 class="newsletter-title">Nhận được một bản tin</h2>
                                <p>Make sure that you never miss our interesting news <br class="hidden-xs">by
                                    joining our newsletter program.</p>
                            </div>
                            <div class="subcribe clearfix">
                                <form action="#">
                                    <input type="text" name="email" placeholder="Enter your email here..." />
                                    <button class="submit-btn-2 btn-hover-2" type="submit">subcribe</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- NEWSLETTER SECTION END -->
    </section>
    <!-- END PAGE CONTENT -->
@endsection

<style>
    /* ====== BLOG STYLES ====== */
    .blog-section {
        background-color: #f9f9f9;
    }
    
    .blog-item {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 30px;
        border: 1px solid #eee;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .blog-item:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }
    
    .blog-img {
        position: relative;
        overflow: hidden;
        padding-top: 60%;
    }
    
    .blog-img img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .blog-item:hover .blog-img img {
        transform: scale(1.05);
    }
    
    .blog-info {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .blog-title {
        margin: 0 0 12px 0;
        line-height: 1.4;
        min-height: 42px;
        max-height: 54px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .blog-title a {
        color: #333;
        font-size: 1.1rem;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .blog-title a:hover {
        color: #ff6b00;
    }
    
    .blog-excerpt {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 15px;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .blog-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid #eee;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .blog-date,
    .blog-author {
        color: #888;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
    }
    
    .blog-date i,
    .blog-author i {
        margin-right: 5px;
        font-size: 1rem;
    }
    
    .read-more {
        color: #ff6b00;
        font-weight: 500;
        font-size: 0.9rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }
    
    .read-more i {
        margin-left: 5px;
        transition: transform 0.3s ease;
    }
    
    .read-more:hover {
        color: #e65100;
    }
    
    .read-more:hover i {
        transform: translateX(3px);
    }
    
    /* Responsive styles */
    @media (max-width: 991px) {
        .blog-title a {
            font-size: 1rem;
        }
        
        .blog-excerpt {
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 767px) {
        .blog-item {
            margin-bottom: 20px;
        }
    }
    
    /* ====== GLOBAL ====== */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f9f9f9;
    color: #333;
    line-height: 1.6;
    margin: 0;
    padding: 0;
}

.section {
    padding: 60px 0;
}

/* ====== CATEGORY SLIDER ====== */
.category-slider-section {
    background: #fff;
    padding: 30px 0;
    overflow: hidden; /* tránh bị tràn */
}

.active-category-slider {
    display: flex;
    justify-content: center; /* căn giữa nội dung */
    align-items: center;
    gap: 20px; /* khoảng cách đều nhau */
    flex-wrap: nowrap; /* giữ các item trên 1 hàng */
    overflow-x: auto; /* cho phép kéo ngang khi nhiều item */
    scroll-behavior: smooth;
    padding: 10px 0;
}

.active-category-slider::-webkit-scrollbar {
    display: none; /* Ẩn thanh scroll để gọn gàng */
}

.category-slide-item {
    flex: 0 0 150px;
    background: #fff;
    border-radius: 12px;
    padding: 15px 10px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    cursor: pointer;
}

.category-slide-item img {
    width: 100%;
    max-height: 80px;
    object-fit: contain;
    margin-bottom: 10px;
    transition: transform 0.3s ease;
}

.category-slide-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
}

.category-slide-item:hover img {
    transform: scale(1.08);
}

.category-slide-item .category-name {
    font-size: 14px;
    font-weight: 600;
    color: #222;
    white-space: nowrap; /* giữ text trên 1 dòng */
}

/* ====== BLOG SECTION ====== */
.blog-section-2 {
    background: #fff;
    padding: 60px 0;
}

.blog-item-2 {
    background: #fafafa;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 30px;
    transition: box-shadow 0.3s ease;
}

.blog-item-2:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.blog-image img {
    width: 100%;
    height: auto;
    border-radius: 12px 12px 0 0;
    object-fit: cover;
}

.blog-desc {
    padding: 20px;
}

.blog-title-2 a {
    font-size: 18px;
    font-weight: 700;
    color: #222;
    text-decoration: none;
}

.blog-title-2 a:hover {
    color: #007bff;
}

/* ====== NEWSLETTER ====== */
.newsletter-section {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: #fff;
    border-radius: 16px;
    padding: 20px;
}

.newsletter-info .newsletter-title {
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 10px;
}

.subcribe form {
    display: flex;
    margin-top: 20px;
}

.subcribe input[type="text"] {
    flex: 1;
    padding: 12px 16px;
    border: none;
    border-radius: 8px 0 0 8px;
    font-size: 15px;
}

.subcribe .submit-btn-2 {
    padding: 12px 20px;
    border: none;
    background: #ffdd57;
    color: #222;
    border-radius: 0 8px 8px 0;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.subcribe .submit-btn-2:hover {
    background: #ffc107;
}

</style>

@section('script-client')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var carousel = document.getElementById('carouselExample');
        if (!carousel) return;
        var items = carousel.querySelectorAll('.carousel-item');
        var currentIndex = 0;
        var totalItems = items.length;

        setInterval(function () {
            // Remove 'active' from current
            items[currentIndex].classList.remove('active');
            // Move to next
            currentIndex = (currentIndex + 1) % totalItems;
            // Add 'active' to new
            items[currentIndex].classList.add('active');
        }, 2000); // 2 seconds
    });
</script>
@endsection