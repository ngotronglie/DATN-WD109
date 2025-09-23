# Blog CRUD System

Hệ thống CRUD hoàn chỉnh cho phần blog với các tính năng:

## Tính năng chính

### 1. Hiển thị danh sách blog
- Hiển thị tất cả blog đã kích hoạt
- Phân trang (9 bài viết/trang)
- Hiển thị thông tin: tiêu đề, nội dung tóm tắt, lượt xem, tác giả, ngày tạo
- Hình ảnh đại diện cho mỗi blog

### 2. Chi tiết blog
- Hiển thị đầy đủ nội dung blog
- Tự động tăng lượt xem
- Hiển thị tags liên quan
- Blog liên quan và blog gần đây
- Thông tin tác giả

### 3. Quản lý blog (Admin)
- **Tạo blog mới**: Form tạo blog với validation
- **Chỉnh sửa blog**: Cập nhật thông tin blog
- **Xóa blog**: Xóa blog và ảnh liên quan
- **Upload ảnh**: Hỗ trợ upload ảnh đại diện

### 4. Tìm kiếm và lọc
- Tìm kiếm theo từ khóa
- Lọc theo tags
- Hiển thị số lượng blog cho mỗi tag

### 5. Tags
- Quản lý tags cho blog
- Hiển thị tags trong sidebar
- Liên kết tags với blog

## Cấu trúc file

### Controllers
- `app/Http/Controllers/Client/BlogDetailController.php` - Controller chính cho blog
- `app/Http/Controllers/Client/ClientController.php` - Cập nhật phương thức blog

### Models
- `app/Models/Blog.php` - Model Blog
- `app/Models/TagBlog.php` - Model TagBlog

### Views
- `resources/views/layouts/user/blog.blade.php` - Trang danh sách blog
- `resources/views/layouts/user/blogDetail.blade.php` - Trang chi tiết blog
- `resources/views/layouts/user/blog/create.blade.php` - Form tạo blog
- `resources/views/layouts/user/blog/edit.blade.php` - Form chỉnh sửa blog

### Routes
- `/blog-detail` - Trang danh sách blog
- `/blog-detail/{slug}` - Trang chi tiết blog
- `/blog-detail/tag/{tagId}` - Lọc blog theo tag
- `/blog-detail/search` - Tìm kiếm blog
- `/blog-detail/create` - Tạo blog mới (Admin)
- `/blog-detail/{slug}/edit` - Chỉnh sửa blog (Admin)
- `/blog-detail/{slug}/delete` - Xóa blog (Admin)

### CSS
- `public/css/blog-custom.css` - CSS tùy chỉnh cho blog

### Database
- `database/seeders/BlogSeeder.php` - Seeder tạo dữ liệu mẫu

## Cài đặt và sử dụng

### 1. Chạy migration
```bash
php artisan migrate
```

### 2. Chạy seeder để tạo dữ liệu mẫu
```bash
php artisan db:seed --class=BlogSeeder
```

### 3. Tạo symbolic link cho storage
```bash
php artisan storage:link
```

### 4. Truy cập các trang
- Danh sách blog: `/blog-detail`
- Chi tiết blog: `/blog-detail/{slug}`
- Tạo blog mới: `/blog-detail/create` (cần đăng nhập admin)

## Tính năng bảo mật

- Chỉ admin mới có thể tạo, sửa, xóa blog
- Validation đầy đủ cho form
- Xác thực quyền truy cập
- Bảo vệ CSRF

## Responsive Design

- Giao diện responsive cho mobile
- CSS tùy chỉnh cho trải nghiệm tốt nhất
- Hover effects và animations

## Tùy chỉnh

### Thêm tính năng mới
1. Thêm phương thức vào `BlogDetailController`
2. Tạo route mới trong `routes/web.php`
3. Tạo view tương ứng
4. Cập nhật CSS nếu cần

### Thay đổi giao diện
- Chỉnh sửa file CSS: `public/css/blog-custom.css`
- Cập nhật các file blade trong `resources/views/layouts/user/`

## Lưu ý

- Đảm bảo đã cài đặt và cấu hình Laravel đúng cách
- Cần có user admin để sử dụng tính năng quản lý
- Ảnh upload sẽ được lưu trong `storage/app/public/images/blogs/`
- Database cần có bảng `blogs`, `tag_blog`, `tag_blog_blog`

## Hỗ trợ

Nếu có vấn đề hoặc cần hỗ trợ, vui lòng kiểm tra:
1. Log lỗi trong `storage/logs/`
2. Cấu hình database
3. Quyền truy cập file và thư mục 