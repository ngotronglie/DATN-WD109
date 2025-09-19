<div class="category-section py-5 bg-light">
  <div class="container">
    <div class="section-title text-center mb-5">
      <h2 class="mb-3">DANH MỤC SẢN PHẨM</h2>
      <div class="title-divider">
        <span class="divider-line"></span>
        <i class="zmdi zmdi-apps"></i>
        <span class="divider-line"></span>
      </div>
    </div>
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

            </a>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<style>
/* Category Section Styling */
.category-section {
  padding: 2rem 0;
  background: #fff;
  border-top: 1px solid #f5f5f5;
  border-bottom: 1px solid #f5f5f5;
}
.category-scroll-container {
  width: 100%;
  overflow-x: auto;
  padding: 15px 0 30px;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
  margin: 0 -15px;
  padding: 15px;
  position: relative;
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

/* Hover/Focus effects */
.category-item:hover .category-icon {
  transform: translateY(-4px);
  box-shadow: 0 10px 18px rgba(0,0,0,0.12);
  border-color: #ffd3b3;
}

.category-item:hover .category-icon img {
  transform: scale(1.06);
}

/* Keyboard accessibility */
.category-item a:focus .category-icon,
.category-item a:active .category-icon {
  transform: translateY(-4px);
  box-shadow: 0 10px 18px rgba(0,0,0,0.12);
  border-color: #ffd3b3;
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
  font-size: 14px;
  color: #333;
  font-weight: 600;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-top: 8px;
  transition: color 0.3s ease;
}

.category-item:hover .category-title {
  color: #ff6b00;
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
