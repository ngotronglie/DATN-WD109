@extends('index.clientdashboard')

@section('content')
    @include('components.client.banner')

<!-- CATEGORY SECTION START -->
<div class="category-section py-4">
    <div class="container">
        <div class="row justify-content-center align-items-center flex-nowrap overflow-auto" style="gap: 0.5rem;">
            @foreach($categories as $category)
                <div class="col-6 col-md-3 d-flex justify-content-center p-0" style="max-width: 120px;">
                    <div class="category-item text-center">
                        <a href="#" style="display:inline-block;width:90px;height:70px;overflow:hidden;">
                            <img src="{{ asset($category->Image) }}" alt="{{ $category->Name }}" style="width:90px;height:70px;object-fit:cover;border-radius:10px;">
                        </a>
                        <div class="fw-bold" style="font-size: 0.95rem;">{{ $category->Name }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- CATEGORY SECTION END -->

    <!-- START PAGE CONTENT -->
    <section id="page-content" class="page-wrapper section">

        @include('components.client.product')

        <!-- SERVICE SECTION START -->
        <div class="service-section py-4">
            <div class="container">
                <div class="row flex-nowrap overflow-auto justify-content-center align-items-center text-center" style="gap: 0.1rem;">
                    <div class="col-6 col-md-3 mb-3 mb-md-0 p-0" style="max-width: 200px;">
                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-2">
                            <div style="background:#f5f5f5;border-radius:50%;width:80px;height:80px;display:flex;align-items:center;justify-content:center;">
                                <img src="{{ asset('images/logoicon/Screenshot 2025-07-11 210847.png') }}" alt="Free Shipping" style="width:38px;height:38px;">
                            </div>
                            <div>
                                <div class="fw-bold">Free Shipping</div>
                                <div class="text-muted small">Đơn hàng trên 500K</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3 mb-md-0 p-0" style="max-width: 200px;">
                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-2">
                            <div style="background:#f5f5f5;border-radius:50%;width:80px;height:80px;display:flex;align-items:center;justify-content:center;">
                                <img src="{{ asset('images/logoicon/logo24h.png') }}" alt="Support 24/7" style="width:38px;height:38px;">
                            </div>
                            <div>
                                <div class="fw-bold">Support 24/7</div>
                                <div class="text-muted small">Hỗ trợ mọi lúc</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3 mb-md-0 p-0" style="max-width: 200px;">
                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-2">
                            <div style="background:#f5f5f5;border-radius:50%;width:80px;height:80px;display:flex;align-items:center;justify-content:center;">
                                <img src="{{ asset('images/logoicon/Screenshot 2025-07-11 210222.png') }}" alt="Money Return" style="width:38px;height:38px;">
                            </div>
                            <div>
                                <div class="fw-bold">Money Return</div>
                                <div class="text-muted small">Hoàn tiền 7 ngày</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3 mb-md-0 p-0" style="max-width: 200px;">
                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-2">
                            <div style="background:#f5f5f5;border-radius:50%;width:80px;height:80px;display:flex;align-items:center;justify-content:center;">
                                <img src="{{ asset('images/logoicon/Screenshot 2025-07-11 210141.png') }}" alt="Order Discount" style="width:38px;height:38px;">
                            </div>
                            <div>
                                <div class="fw-bold">Order Discount</div>
                                <div class="text-muted small">Ưu đãi mỗi ngày</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- SERVICE SECTION END -->

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
