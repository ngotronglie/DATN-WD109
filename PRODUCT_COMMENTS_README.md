# Hệ thống Bình luận Sản phẩm

## Tổng quan

Hệ thống bình luận sản phẩm cho phép người dùng đăng nhập bình luận và đánh giá sản phẩm. Hệ thống hỗ trợ:
- Bình luận với đánh giá sao (1-5)
- Trả lời bình luận
- Phân trang
- Quản lý bình luận cho admin

## Cấu trúc Database

### Bảng `product_comments`
```sql
- id (Primary Key)
- product_id (Foreign Key -> products.id)
- user_id (Foreign Key -> users.id, nullable)
- content (Text)
- parent_id (Foreign Key -> product_comments.id, nullable)
- rating (Integer, 1-5, nullable)
- created_at, updated_at
```

## Tính năng

### 1. Bình luận sản phẩm
- Người dùng đăng nhập có thể bình luận
- Hỗ trợ đánh giá sao từ 1-5
- Hiển thị avatar và tên người bình luận
- Thời gian bình luận

### 2. Trả lời bình luận
- Người dùng có thể trả lời bình luận khác
- Hiển thị dạng thread (cây bình luận)
- Chỉ hiển thị trả lời khi click nút "Trả lời"

### 3. Quản lý bình luận
- Xóa bình luận (chỉ người viết hoặc admin)
- Admin có thể trả lời bình luận
- Thống kê số lượng bình luận và đánh giá

### 4. Giao diện
- Responsive design
- Animation mượt mà
- Rating stars tương tác
- Phân trang

## Routes

### Client Routes
```php
// Bình luận sản phẩm (yêu cầu đăng nhập)
Route::post('/product/{productId}/comments', [ProductCommentController::class, 'store']);
Route::delete('/product-comments/{id}', [ProductCommentController::class, 'destroy']);
Route::post('/product-comments/{commentId}/reply', [ProductCommentController::class, 'reply']);
```

### Admin Routes
```php
// Quản lý bình luận sản phẩm
Route::get('/admin/product-comments', [AdminProductCommentController::class, 'index']);
Route::get('/admin/product-comments/{id}', [AdminProductCommentController::class, 'show']);
Route::delete('/admin/product-comments/{id}', [AdminProductCommentController::class, 'destroy']);
Route::post('/admin/product-comments/{id}/reply', [AdminProductCommentController::class, 'reply']);
```

## Models

### ProductComment
```php
// Relationships
public function product() // belongsTo Product
public function user() // belongsTo User
public function replies() // hasMany ProductComment
public function parent() // belongsTo ProductComment

// Scopes
public function scopeRootComments($query) // Lấy bình luận gốc
public function scopeWithReplies($query) // Lấy bình luận với replies
```

### Product
```php
// Relationships
public function comments() // hasMany ProductComment
public function rootComments() // Lấy bình luận gốc với replies
```

## Controllers

### ProductCommentController (Client)
- `store()`: Tạo bình luận mới
- `destroy()`: Xóa bình luận
- `reply()`: Trả lời bình luận

### AdminProductCommentController (Admin)
- `index()`: Danh sách bình luận
- `show()`: Chi tiết bình luận
- `destroy()`: Xóa bình luận
- `reply()`: Trả lời bình luận

## Views

### Client Views
- `resources/views/layouts/user/productDetail.blade.php`: Tab bình luận trong trang chi tiết sản phẩm

### Admin Views
- `resources/views/layouts/admin/product-comments/index.blade.php`: Danh sách bình luận
- `resources/views/layouts/admin/product-comments/show.blade.php`: Chi tiết bình luận

## CSS/JS

### CSS
- `public/css/product-comments.css`: Styles cho hệ thống bình luận
- Rating stars animation
- Responsive design
- Loading states

### JavaScript
- Reply functionality
- Rating stars interaction
- Success/error message display
- Form validation

## Cài đặt

### 1. Chạy Migration
```bash
php artisan migrate
```

### 2. Chạy Seeder (tùy chọn)
```bash
php artisan db:seed --class=ProductCommentSeeder
```

### 3. Kiểm tra Routes
```bash
php artisan route:list | grep product-comments
```

## Sử dụng

### 1. Thêm bình luận
- Đăng nhập vào hệ thống
- Vào trang chi tiết sản phẩm
- Chọn tab "Đánh giá & Bình luận"
- Điền đánh giá sao và nội dung
- Click "Gửi bình luận"

### 2. Trả lời bình luận
- Click nút "Trả lời" bên cạnh bình luận
- Điền nội dung trả lời
- Click "Gửi trả lời"

### 3. Quản lý (Admin)
- Vào Admin Panel
- Chọn "Bình luận sản phẩm"
- Xem danh sách và quản lý bình luận

## Tùy chỉnh

### 1. Thay đổi số sao tối đa
```php
// Trong migration
$table->integer('rating')->nullable(); // Thay đổi validation

// Trong view
@for($i = 1; $i <= 5; $i++) // Thay đổi số 5
```

### 2. Thay đổi số bình luận mỗi trang
```php
// Trong controller
$comments = $product->rootComments()->paginate(10); // Thay đổi số 10
```

### 3. Tùy chỉnh CSS
```css
/* Trong public/css/product-comments.css */
.rating-stars i {
    font-size: 18px; /* Thay đổi kích thước sao */
}
```

## Bảo mật

### 1. Validation
- Nội dung bình luận: required, max 1000 ký tự
- Rating: integer, min 1, max 5
- Parent_id: exists trong bảng product_comments

### 2. Authorization
- Chỉ người viết hoặc admin mới xóa được bình luận
- Chỉ người đăng nhập mới bình luận được

### 3. CSRF Protection
- Tất cả form đều có CSRF token
- Validation đầy đủ

## Troubleshooting

### 1. Bình luận không hiển thị
- Kiểm tra relationship trong model
- Kiểm tra query trong controller
- Kiểm tra view có đúng tên biến không

### 2. Rating stars không hoạt động
- Kiểm tra CSS có được load không
- Kiểm tra JavaScript có lỗi không
- Kiểm tra HTML structure

### 3. Reply không hoạt động
- Kiểm tra JavaScript event listeners
- Kiểm tra form action và method
- Kiểm tra route có đúng không

## Tương lai

### Tính năng có thể thêm
- Like/Dislike bình luận
- Báo cáo bình luận spam
- Filter bình luận theo rating
- Export bình luận
- Email notification khi có bình luận mới
- Moderation queue cho bình luận
- Rich text editor cho bình luận
- Upload ảnh trong bình luận 