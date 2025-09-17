<div class="category-section py-4">
  <div class="container-fluid px-3">
    <h4 class="section-title text-center mb-3 px-3">Danh Mục Sản Phẩm</h4>
    <div class="category-scroll-container">
      <div class="category-scroll">
        @foreach($categories as $category)
          <div class="category-item">
            <a href="{{ url('shop?category=' . $category->ID) }}">
              <div class="category-icon">
                @if($category->Image)
                  <img src="{{ asset($category->Image) }}" alt="{{ $category->Name }}" loading="lazy">
                @else
                  <img src="https://via.placeholder.com/80x80?text=No+Img" alt="No Image">
                @endif
              </div>
              <div class="category-title">{{ $category->Name }}</div>
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>


<style>
.category-scroll-container {
  width: 100%;
  overflow-x: auto;
  padding: 10px 0;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none; /* Ẩn thanh cuộn trên Firefox */
  margin-left: -15px;
  margin-right: -15px;
  padding-left: 15px;
  padding-right: 15px;
}

.category-scroll-container::-webkit-scrollbar {
  display: none; /* Ẩn thanh cuộn trên Chrome/Safari */
}

.category-scroll {
  display: inline-flex;
  gap: 15px;
  padding: 0;
  min-width: 100%;
  width: max-content;
}

/* Điều chỉnh cho container lớn */
.container-fluid {
  padding: 0;
  max-width: 100%;
}

/* Mobile */
@media (max-width: 768px) {
  .category-scroll {
    padding: 0 10px;
    gap: 10px;
  }
  
  .category-item {
    width: 120px;
  }
  
  .category-icon {
    width: 100px;
    height: 100px;
  }
}

.category-item {
  flex: 0 0 auto;
  width: 150px;
  text-align: center;
  margin: 0 5px;
}

.category-icon {
  width: 130px;
  height: 130px;
  border-radius: 12px;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: all 0.2s ease;
  margin: 0 auto 10px;
  overflow: hidden;
  border: 1px solid #f0f0f0;
}

.category-icon img {
  width: 70%;
  height: 70%;
  object-fit: contain;
  transition: transform 0.2s ease;
}

/* Ẩn thanh cuộn trên Chrome, Safari và Opera */
.category-scroll-container::-webkit-scrollbar {
  height: 6px;
}

.category-scroll-container::-webkit-scrollbar-thumb {
  background: #ccc;
  border-radius: 3px;
}

.category-scroll-container::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

/* Ẩn thanh cuộn trên Firefox */
@-moz-document url-prefix() {
  .category-scroll-container {
    scrollbar-width: thin;
    scrollbar-color: #ccc #f1f1f1;
  }
}

.category-title {
  font-size: 1rem;
  font-weight: 500;
  color: #333;
  text-align: center;
  line-height: 1.4;
  max-width: 140px;
  margin: 0 auto;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Mobile */
@media (max-width: 768px) {
  .category-scroll {
    gap: 12px;
    padding: 0 10px;
  }
  
  .category-item {
    width: 110px;
  }
  
  .category-icon {
    width: 100px;
    height: 100px;
  }
  
  .category-title {
    font-size: 0.8rem;
    max-width: 100%;
    margin-top: 4px;
  }
  
  .section-title {
    margin-bottom: 15px;
  }
}


</style>