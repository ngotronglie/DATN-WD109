@if(isset($banners) && count($banners) > 0)
<div id="carouselExample" class="carousel slide">
    <div class="carousel-inner">
        @foreach($banners as $key => $banner)
            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                <img src="{{ asset('images/banners/' . $banner->img) }}" class="d-block w-100 banner-img" alt="{{ $banner->title }}">
                @if($banner->title || $banner->description)
                <div class="carousel-caption custom-caption d-none d-md-block">
                    <h5 class="banner-title">{{ $banner->title }}</h5>
                    <p class="banner-desc">{{ $banner->description }}</p>
                </div>
                @endif
            </div>
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
@else
<div id="carouselExample" class="carousel slide">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="https://cdn.hoanghamobile.com/i/home/Uploads/2025/05/13/thu-cu-wweb.png" class="d-block w-100"
                alt="...">
        </div>
        <div class="carousel-item">
            <img src="https://cdn.hoanghamobile.com/i/home/Uploads/2025/06/16/xiaomi-tivi-web.png" class="d-block w-100"
                alt="...">
        </div>
        <div class="carousel-item">
            <img src="https://cdn.hoanghamobile.com/i/home/Uploads/2025/05/06/a56-a36-atsh-1200x375.jpg"
                class="d-block w-100" alt="...">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
@endif

<style>
.banner-img {
    max-height: 350px;
    width: 100%;
    object-fit: cover;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    background: #f8f9fa;
}
.carousel-inner {
    border-radius: 16px;
    overflow: hidden;
}
.carousel-inner .carousel-item {
    opacity: 0;
    transition: opacity 0.7s ease-in-out;
    position: absolute;
    width: 100%;
    left: 0;
    top: 0;
    z-index: 1;
}
.carousel-inner .carousel-item.active {
    opacity: 1;
    position: relative;
    z-index: 2;
}
#carouselExample {
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
}
.custom-caption {
    background: rgba(0,0,0,0.45);
    border-radius: 12px;
    padding: 18px 28px 14px 28px;
    left: 50%;
    bottom: 32px;
    transform: translateX(-50%);
    max-width: 80%;
    text-align: center;
}
.banner-title {
    color: #fff;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 8px rgba(0,0,0,0.25);
}
.banner-desc {
    color: #f1f1f1;
    font-size: 1.1rem;
    margin-bottom: 0;
    text-shadow: 0 1px 4px rgba(0,0,0,0.18);
}
@media (max-width: 768px) {
    .custom-caption {
        padding: 10px 10px 8px 10px;
        max-width: 98%;
        bottom: 12px;
    }
    .banner-title {
        font-size: 1.2rem;
    }
    .banner-desc {
        font-size: 0.95rem;
    }
}
</style>
