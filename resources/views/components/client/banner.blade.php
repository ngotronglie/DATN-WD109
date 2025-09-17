

<div id="carouselExample" class="carousel slide carousel-fade rounded shadow overflow-hidden" data-bs-ride="carousel">
    <!-- Indicators -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="0" class="active" aria-current="true"
            aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">
        @if (isset($banners) && count($banners) > 0)
            @foreach ($banners as $index => $banner)
                <div class="carousel-item @if ($index == 0) active @endif">
                    <img src="{{ asset('images/banners/' . $banner->img) }}" class="d-block w-100"
                        alt="{{ $banner->title }}">
                    @if ($banner->title || $banner->description)
                    @endif
                </div>
            @endforeach
        @else
            <div class="carousel-item active">
                <img src="https://via.placeholder.com/1200x375?text=No+Banner+Available" class="d-block w-100"
                    alt="No banner">
            </div>
        @endif
    </div>

    <!-- Controls -->
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
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.carousel-inner .carousel-item img {
    width: auto;
    height: auto; /* khung chuẩn */
    object-fit: contain; /* hiển thị toàn bộ ảnh */
    object-position: center;
    background-color: #fff; /* nền trắng cho phần trống */
    border-radius: 16px;
}


.carousel-caption {
    background: rgba(0,0,0,0.5);
    border-radius: 10px;
    padding: 10px 15px;
}

.carousel-caption h5 {
    font-size: 1.5rem;
    font-weight: bold;
    color: #fff;
}

.carousel-caption p {
    font-size: 1rem;
    color: #f1f1f1;
}

@media (max-width: 768px) {
    .carousel-inner .carousel-item img {
        height: 220px; /* Giảm chiều cao trên mobile */
    }

    .carousel-caption h5 {
        font-size: 1.1rem;
    }

    .carousel-caption p {
        font-size: 0.9rem;
    }
}

</style>    
