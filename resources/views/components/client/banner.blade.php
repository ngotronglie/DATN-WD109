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
