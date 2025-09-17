<div class="category-slider-section pt-30 pb-30">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="active-category-slider">
                    @foreach($categories as $category)
                        <div class="category-slide-item text-center p-3" style="background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin: 0 8px;">
                            <a href="{{ url('shop?category=' . $category->ID) }}" style="text-decoration: none; color: inherit;">
                                <div style="height: 70px; display: flex; align-items: center; justify-content: center;">
                                    @if($category->Image)
                                        <img src="{{ asset($category->Image) }}" alt="{{ $category->Name }}" style="max-height: 60px; max-width: 100px; object-fit: contain;">
                                    @else
                                        <img src="https://via.placeholder.com/100x60?text=No+Image" alt="No Image" style="max-height: 60px; max-width: 100px; object-fit: contain;">
                                    @endif
                                </div>
                                <div class="mt-2" style="font-weight: 500; font-size: 1rem; min-height: 40px; color: #222;">
                                    {{ $category->Name }}
                                </div>
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
</style> 