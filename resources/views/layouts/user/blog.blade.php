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
                                    Danh mục
                                    <i class="zmdi zmdi-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu dropdown-width mt-30">
                                    <aside class="widget widget-categories box-shadow">
                                        <h6 class="widget-title border-left mb-20">Danh mục</h6>
                                        <div id="cat-treeview" class="product-cat">
                                            <ul>
                                                <li class="closed"><a href="#">Thương hiệu Một</a>
                                                    <ul>
                                                        <li><a href="#">Điện thoại</a></li>
                                                        <li><a href="#">Máy tính bảng</a></li>
                                                        <li><a href="#">Đồng hồ</a></li>
                                                        <li><a href="#">Tai nghe</a></li>
                                                        <li><a href="#">Bộ nhớ</a></li>
                                                    </ul>
                                                </li>
                                                <li class="open"><a href="#">Thương hiệu Hai</a>
                                                    <ul>
                                                        <li><a href="#">Điện thoại</a></li>
                                                        <li><a href="#">Máy tính bảng</a></li>
                                                        <li><a href="#">Đồng hồ</a></li>
                                                        <li><a href="#">Tai nghe</a></li>
                                                        <li><a href="#">Bộ nhớ</a></li>
                                                    </ul>
                                                </li>
                                                <li class="closed"><a href="#">Phụ kiện</a>
                                                    <ul>
                                                        <li><a href="#">Giày dép</a></li>
                                                        <li><a href="#">Kính mát</a></li>
                                                        <li><a href="#">Đồng hồ</a></li>
                                                        <li><a href="#">Tiện ích</a></li>
                                                    </ul>
                                                </li>
                                                <li class="closed"><a href="#">Top thương hiệu</a>
                                                    <ul>
                                                        <li><a href="#">Điện thoại</a></li>
                                                        <li><a href="#">Máy tính bảng</a></li>
                                                        <li><a href="#">Đồng hồ</a></li>
                                                        <li><a href="#">Tai nghe</a></li>
                                                        <li><a href="#">Bộ nhớ</a></li>
                                                    </ul>
                                                </li>
                                                <li class="closed"><a href="#">Trang sức</a>
                                                    <ul>
                                                        <li><a href="#">Giày dép</a></li>
                                                        <li><a href="#">Kính mát</a></li>
                                                        <li><a href="#">Đồng hồ</a></li>
                                                        <li><a href="#">Tiện ích</a></li>
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
                                    Bài viết mới
                                    <i class="zmdi zmdi-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu dropdown-width mt-30">
                                    <aside class="widget widget-product box-shadow">
                                        <h6 class="widget-title border-left mb-20">Bài viết mới</h6>
                                        <!-- product-item start -->
                                        <div class="product-item">
                                            <div class="product-img">
                                                <a href="#">
                                                    <img src="{{ asset('frontend/img/cart/4.jpg') }}" alt=""/>
                                                </a>
                                            </div>
                                            <div class="product-info">
                                                <h6 class="product-title multi-line mt-10">
                                                    <a href="#">Tên bài viết mẫu</a>
                                                </h6>
                                            </div>
                                        </div>
                                        <!-- product-item end -->
                                        <!-- product-item start -->
                                        <div class="product-item">
                                            <div class="product-img">
                                                <a href="#">
                                                    <img src="{{ asset('frontend/img/cart/5.jpg') }}" alt=""/>
                                                </a>
                                            </div>
                                            <div class="product-info">
                                                <h6 class="product-title multi-line mt-10">
                                                    <a href="#">Tên bài viết mẫu</a>
                                                </h6>
                                            </div>
                                        </div>
                                        <!-- product-item end -->
                                        <!-- product-item start -->
                                        <div class="product-item">
                                            <div class="product-img">
                                                <a href="#">
                                                    <img src="{{ asset('frontend/img/cart/6.jpg') }}" alt=""/>
                                                </a>
                                            </div>
                                            <div class="product-info">
                                                <h6 class="product-title multi-line mt-10">
                                                    <a href="#">Tên bài viết mẫu</a>
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
                    <!-- blog-item start -->
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item">
                            <img src="{{ asset('frontend/img/blog/1.jpg') }}" alt="">
                            <div class="blog-desc">
                                <h5 class="blog-title"><a href="#">Tên bài viết mẫu</a></h5>
                                <p>Có rất nhiều biến thể của Lorem Ipsum, nhưng phần lớn đã bị thay đổi dưới nhiều hình thức, bởi sự thêm vào của các yếu tố hài hước, hoặc bởi các từ ngẫu nhiên không có ý nghĩa.</p>
                                <div class="read-more">
                                    <a href="#">Xem thêm</a>
                                </div>
                                <ul class="blog-meta">
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-favorite"></i>89 Lượt thích</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-comments"></i>59 Bình luận</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-share"></i>29 Chia sẻ</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- blog-item end -->
                    <!-- blog-item start -->
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item">
                            <img src="{{ asset('frontend/img/blog/2.jpg') }}" alt="">
                            <div class="blog-desc">
                                <h5 class="blog-title"><a href="#">Tên bài viết mẫu</a></h5>
                                <p>Có rất nhiều biến thể của Lorem Ipsum, nhưng phần lớn đã bị thay đổi dưới nhiều hình thức, bởi sự thêm vào của các yếu tố hài hước, hoặc bởi các từ ngẫu nhiên không có ý nghĩa.</p>
                                <div class="read-more">
                                    <a href="#">Xem thêm</a>
                                </div>
                                <ul class="blog-meta">
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-favorite"></i>89 Lượt thích</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-comments"></i>59 Bình luận</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-share"></i>29 Chia sẻ</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- blog-item end -->
                    <!-- blog-item start -->
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item">
                            <img src="{{ asset('frontend/img/blog/3.jpg') }}" alt="">
                            <div class="blog-desc">
                                <h5 class="blog-title"><a href="#">Tên bài viết mẫu</a></h5>
                                <p>Có rất nhiều biến thể của Lorem Ipsum, nhưng phần lớn đã bị thay đổi dưới nhiều hình thức, bởi sự thêm vào của các yếu tố hài hước, hoặc bởi các từ ngẫu nhiên không có ý nghĩa.</p>
                                <div class="read-more">
                                    <a href="#">Xem thêm</a>
                                </div>
                                <ul class="blog-meta">
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-favorite"></i>89 Lượt thích</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-comments"></i>59 Bình luận</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-share"></i>29 Chia sẻ</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- blog-item end -->
                    <!-- blog-item start -->
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item">
                            <img src="{{ asset('frontend/img/blog/4.jpg') }}" alt="">
                            <div class="blog-desc">
                                <h5 class="blog-title"><a href="#">Tên bài viết mẫu</a></h5>
                                <p>Có rất nhiều biến thể của Lorem Ipsum, nhưng phần lớn đã bị thay đổi dưới nhiều hình thức, bởi sự thêm vào của các yếu tố hài hước, hoặc bởi các từ ngẫu nhiên không có ý nghĩa.</p>
                                <div class="read-more">
                                    <a href="#">Xem thêm</a>
                                </div>
                                <ul class="blog-meta">
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-favorite"></i>89 Lượt thích</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-comments"></i>59 Bình luận</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-share"></i>29 Chia sẻ</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- blog-item end -->
                    <!-- blog-item start -->
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item">
                            <img src="{{ asset('frontend/img/blog/5.jpg') }}" alt="">
                            <div class="blog-desc">
                                <h5 class="blog-title"><a href="#">Tên bài viết mẫu</a></h5>
                                <p>Có rất nhiều biến thể của Lorem Ipsum, nhưng phần lớn đã bị thay đổi dưới nhiều hình thức, bởi sự thêm vào của các yếu tố hài hước, hoặc bởi các từ ngẫu nhiên không có ý nghĩa.</p>
                                <div class="read-more">
                                    <a href="#">Xem thêm</a>
                                </div>
                                <ul class="blog-meta">
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-favorite"></i>89 Lượt thích</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-comments"></i>59 Bình luận</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-share"></i>29 Chia sẻ</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- blog-item end -->
                    <!-- blog-item start -->
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item">
                            <img src="{{ asset('frontend/img/blog/6.jpg') }}" alt="">
                            <div class="blog-desc">
                                <h5 class="blog-title"><a href="#">Tên bài viết mẫu</a></h5>
                                <p>Có rất nhiều biến thể của Lorem Ipsum, nhưng phần lớn đã bị thay đổi dưới nhiều hình thức, bởi sự thêm vào của các yếu tố hài hước, hoặc bởi các từ ngẫu nhiên không có ý nghĩa.</p>
                                <div class="read-more">
                                    <a href="#">Xem thêm</a>
                                </div>
                                <ul class="blog-meta">
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-favorite"></i>89 Lượt thích</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-comments"></i>59 Bình luận</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-share"></i>29 Chia sẻ</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- blog-item end -->
                    <!-- blog-item start -->
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item">
                            <img src="{{ asset('frontend/img/blog/1.jpg') }}" alt="">
                            <div class="blog-desc">
                                <h5 class="blog-title"><a href="#">Tên bài viết mẫu</a></h5>
                                <p>Có rất nhiều biến thể của Lorem Ipsum, nhưng phần lớn đã bị thay đổi dưới nhiều hình thức, bởi sự thêm vào của các yếu tố hài hước, hoặc bởi các từ ngẫu nhiên không có ý nghĩa.</p>
                                <div class="read-more">
                                    <a href="#">Xem thêm</a>
                                </div>
                                <ul class="blog-meta">
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-favorite"></i>89 Lượt thích</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-comments"></i>59 Bình luận</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-share"></i>29 Chia sẻ</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- blog-item end -->
                    <!-- blog-item start -->
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item">
                            <img src="{{ asset('frontend/img/blog/7.jpg') }}" alt="">
                            <div class="blog-desc">
                                <h5 class="blog-title"><a href="#">Tên bài viết mẫu</a></h5>
                                <p>Có rất nhiều biến thể của Lorem Ipsum, nhưng phần lớn đã bị thay đổi dưới nhiều hình thức, bởi sự thêm vào của các yếu tố hài hước, hoặc bởi các từ ngẫu nhiên không có ý nghĩa.</p>
                                <div class="read-more">
                                    <a href="#">Xem thêm</a>
                                </div>
                                <ul class="blog-meta">
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-favorite"></i>89 Lượt thích</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-comments"></i>59 Bình luận</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-share"></i>29 Chia sẻ</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- blog-item end -->
                    <!-- blog-item start -->
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item">
                            <img src="{{ asset('frontend/img/blog/8.jpg') }}" alt="">
                            <div class="blog-desc">
                                <h5 class="blog-title"><a href="#">Tên bài viết mẫu</a></h5>
                                <p>Có rất nhiều biến thể của Lorem Ipsum, nhưng phần lớn đã bị thay đổi dưới nhiều hình thức, bởi sự thêm vào của các yếu tố hài hước, hoặc bởi các từ ngẫu nhiên không có ý nghĩa.</p>
                                <div class="read-more">
                                    <a href="#">Xem thêm</a>
                                </div>
                                <ul class="blog-meta">
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-favorite"></i>89 Lượt thích</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-comments"></i>59 Bình luận</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="zmdi zmdi-share"></i>29 Chia sẻ</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- blog-item end -->
                </div>
            </div>
        </div>
        <!-- BLOG SECTION END -->
    </div>
    <!-- End page content -->
@endsection