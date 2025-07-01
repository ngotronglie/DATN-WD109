<style>
    #carouselExample img {
        height: 400px;
        object-fit: cover;
    }

    .carousel-indicators [data-bs-target] {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #000;
    }

    @media (max-width: 768px) {
        #carouselExample img {
            height: 250px;
        }
    }
</style>

<div id="carouselExample" class="carousel slide carousel-fade rounded shadow overflow-hidden" data-bs-ride="carousel">
    <!-- Indicators -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="https://cdn.hoanghamobile.com/i/home/Uploads/2025/05/13/thu-cu-wweb.png" class="d-block w-100" alt="Banner 1">
        </div>
        <div class="carousel-item">
            <img src="https://cdn.hoanghamobile.com/i/home/Uploads/2025/06/16/xiaomi-tivi-web.png" class="d-block w-100" alt="Banner 2">
        </div>
        <div class="carousel-item">
            <img src="https://cdn.hoanghamobile.com/i/home/Uploads/2025/05/06/a56-a36-atsh-1200x375.jpg" class="d-block w-100" alt="Banner 3">
        </div>
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
        <span class="visually-hidden">Trước</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
        <span class="visually-hidden">Tiếp</span>
    </button>
</div>
