<div class="category-slider-section pt-30 pb-30">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="active-category-slider">
                    @foreach($categories as $category)
<div class="category-slide-item">
    <a href="{{ url('shop?category=' . $category->ID) }}">
        <div class="category-img">
            <img src="{{ $category->Image ? asset($category->Image) : 'https://via.placeholder.com/100x60?text=No+Image' }}" alt="{{ $category->Name }}">
        </div>
        <div class="category-name">{{ $category->Name }}</div>
    </a>
</div>

                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.active-category-slider .category-slide-item {
    transition: box-shadow 0.2s;
}
.active-category-slider .category-slide-item:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.10);
}
/* ==== CATEGORY SLIDER ==== */
.category-slider-section {
    background: #f9f9f9;
}

.active-category-slider {
    display: flex;
    gap: 8px;
    justify-content: center; /* căn giữa khi ít item */
    overflow-x: auto;        /* scroll ngang khi nhiều item */
    scroll-behavior: smooth;
    padding-bottom: 10px;
}
.active-category-slider {
    display: flex;
    justify-content: center; /* căn giữa ngang */
    gap: 18px; /* khoảng cách giữa các danh mục */
}


.active-category-slider .category-slide-item {
    margin: 10px; /* tạo khoảng cách ngoài item */
    transition: box-shadow 0.2s;
}

.active-category-slider::-webkit-scrollbar {
    height: 6px;
}
.active-category-slider::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

/* Category item */
.category-slide-item {
    flex: 0 0 auto;
    width: 160px;
    background: #fff;
    border-radius: 12px;
    padding: 20px 15px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
    cursor: pointer;
}
.category-slide-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
}

/* Hình ảnh danh mục */
.category-slide-item img {
    max-height: 60px;
    max-width: 100px;
    object-fit: contain;
    transition: transform 0.3s ease;
}
.category-slide-item:hover img {
    transform: scale(1.05);
}

/* Tên danh mục */
.category-slide-item .category-name {
    margin-top: 12px;
    font-weight: 600;
    font-size: 15px;
    color: #222;
    min-height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.category-slide-item a {
    text-decoration: none;
    color: inherit;
}
.category-slide-item:hover .category-name {
    color: #007bff;
}

</style> 