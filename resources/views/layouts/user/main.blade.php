
@extends('index.clientdashboard')

@section('content')
    @include('components.client.banner')

    @include('components.client.category-slider', ['categories' => $categories])

    <!-- START PAGE CONTENT -->
    <section id="page-content" class="page-wrapper section">

        <!-- SẢN PHẨM ĐANG GIẢM GIÁ -->
        <div class="container ">
        <h2 class="mb-3 font-bold">Sản phẩm phổ biến</h2>
            @include('components.client.product', ['products' => $discountedProducts])
     

        <!-- SẢN PHẨM PHỔ BIẾN (KHÔNG GIẢM GIÁ) -->
        
            <h2 class="mb-3 font-bold">Sản phẩm phổ biến</h2>
            @include('components.client.product', ['products' => $popularProducts])
        </div>



        <!-- BLOG SECTION START -->
        <div class="blog-section-2 pt-60 pb-30">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title text-start mb-40">
                            <h2 class="uppercase">Bài viết mới nhất</h2>
                            <h6>There are many variations of passages of brands available,</h6>
                        </div>
                    </div>
                </div>
                <div class="blog">
                    <div class="row active-blog-2">
                        <!-- blog-item start -->
                        <div class="col-lg-12">
                            <div class="blog-item-2">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="blog-image">
                                            <a href="single-blog.html"><img src="{{asset('frontend/img/blog/4.jpg')}}" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="blog-desc">
                                            <h5 class="blog-title-2"><a href="#">dummy Blog name</a></h5>
                                            <p>There are many variations of passages of in psum available, but the
                                                majority have sufe ered on in some form...</p>
                                            <div class="read-more">
                                                <a href="#">Read more</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- blog-item end -->
                        <!-- blog-item start -->
                        <div class="col-lg-12">
                            <div class="blog-item-2">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="blog-image">
                                            <a href="single-blog.html"><img src="{{asset('frontend/img/blog/5.jpg')}}" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="blog-desc">
                                            <h5 class="blog-title-2"><a href="#">dummy Blog name</a></h5>
                                            <p>There are many variations of passages of in psum available, but the
                                                majority have sufe ered on in some form...</p>
                                            <div class="read-more">
                                                <a href="#">Read more</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- blog-item end -->
                        <!-- blog-item start -->
                        <div class="col-lg-12">
                            <div class="blog-item-2">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="blog-image">
                                            <a href="single-blog.html"><img src="{{asset('frontend/img/blog/4.jpg')}}"" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="blog-desc">
                                            <h5 class="blog-title-2"><a href="#">dummy Blog name</a></h5>
                                            <p>There are many variations of passages of in psum available, but the
                                                majority have sufe ered on in some form...</p>
                                            <div class="read-more">
                                                <a href="#">Read more</a>
                                            </div>
                                        </div>
                                    </div>
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
    /* ====== GLOBAL ====== */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f9f9f9;
    color: #333;
    line-height: 1.6;
}

.section {
    padding: 60px 0;
}

/* ====== CATEGORY SLIDER ====== */
.category-slider-section {
    background: #fff;
    padding: 30px 0;
}

.active-category-slider {
    display: flex;
    justify-content: center; /* căn giữa */
    gap: 12px; /* khoảng cách đều nhau */
    flex-wrap: wrap; /* nếu nhiều sẽ xuống dòng */
}

.category-slide-item {
    flex: 0 0 auto;
    width: 150px;
    background: #fff;
    border-radius: 12px;
    padding: 15px 10px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.category-slide-item img {
    max-height: 60px;
    object-fit: contain;
    margin-bottom: 10px;
    transition: transform 0.3s;
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
    border-radius: 12px 0 0 12px;
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
