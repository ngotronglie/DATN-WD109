<div id="carouselExample" class="carousel slide">
    <div class="carousel-inner">
        @if(isset($banners) && count($banners) > 0)
            @foreach($banners as $index => $banner)
                <div class="carousel-item @if($index == 0) active @endif">
                    <img src="{{ asset('images/banners/' . $banner->img) }}" class="d-block w-100" alt="{{ $banner->title }}">
                    @if($banner->title || $banner->description)
                        <div class="carousel-caption d-none d-md-block">
                            <h5>{{ $banner->title }}</h5>
                            <p>{{ $banner->description }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="carousel-item active">
                <img src="https://via.placeholder.com/1200x375?text=No+Banner+Available" class="d-block w-100" alt="No banner">
            </div>
        @endif
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Trước</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Tiếp Theo</span>
    </button>
</div>

<style>
#carouselExample {
    position: relative;
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
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 180px;
    height: 340px;
}
.carousel-inner .carousel-item.active {
    opacity: 1;
    position: relative;
    z-index: 2;
    display: flex;
}
.carousel-inner .carousel-item img {
    width: 100vw;
    max-width: 100vw;
    min-width: 100vw;
    max-height: 340px;
    height: 100%;
    object-fit: cover;
    object-position: center;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    background: #f8f8f8;
}
.carousel-caption {
    position: absolute;
    left: 50%;
    bottom: 24px;
    top: auto;
    transform: translateX(-50%);
    background: rgba(30,30,30,0.55);
    border-radius: 14px;
    padding: 12px 18px 10px 18px;
    color: #fff;
    min-width: 180px;
    max-width: 340px;
    width: 100%;
    box-shadow: 0 2px 12px rgba(0,0,0,0.13);
    text-align: center;
    border: none;
    text-shadow: 0 2px 8px rgba(0,0,0,0.18);
    font-weight: 500;
    filter: none;
}
.carousel-caption h5 {
    font-size: 1.45rem;
    font-weight: 900;
    margin-bottom: 0.3rem;
    letter-spacing: 0.5px;
    text-transform: none;
    color: #fff;
    text-align: center;
    text-shadow: 0 2px 8px rgba(0,0,0,0.28), 0 0 2px #fff, 0 0 8px #2228;
}
.carousel-caption p {
    font-size: 0.98rem;
    margin-bottom: 0.2rem;
    font-weight: 400;
    color: #eaf6ff;
    text-align: center;
    text-shadow: none;
    margin-top: 0.1rem;
    line-height: 1.3;
}
.carousel-control-prev-icon,
.carousel-control-next-icon {
    filter: drop-shadow(0 2px 6px rgba(0,0,0,0.25));
}
@media (max-width: 768px) {
    .carousel-inner .carousel-item {
        height: 180px;
        min-height: 100px;
    }
    .carousel-inner .carousel-item img {
        max-height: 180px;
        border-radius: 10px;
        width: 100vw;
        min-width: 100vw;
    }
    .carousel-caption {
        padding: 5px 6px 4px 6px;
        font-size: 0.95rem;
        max-width: 90vw;
        min-width: 0;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        border-radius: 10px;
    }
    .carousel-caption h5 {
        font-size: 1.08rem;
    }
    .carousel-caption p {
        font-size: 0.88rem;
    }
}
</style>
