@extends('index.clientdashboard')

@section('content')
    @include('components.client.banner')

    @include('components.client.category-slider', ['categories' => $categories])

    <!-- START PAGE CONTENT -->
    <section id="page-content" class="page-wrapper section">

        @include('components.client.product')



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
