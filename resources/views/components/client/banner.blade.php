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
}
</style>
