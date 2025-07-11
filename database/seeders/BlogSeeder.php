<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\TagBlog;
use App\Models\User;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo tags mẫu
        $tags = [
            ['name_tag' => 'Công nghệ', 'content' => 'Các bài viết về công nghệ'],
            ['name_tag' => 'Điện thoại', 'content' => 'Các bài viết về điện thoại'],
            ['name_tag' => 'Laptop', 'content' => 'Các bài viết về laptop'],
            ['name_tag' => 'Phụ kiện', 'content' => 'Các bài viết về phụ kiện'],
            ['name_tag' => 'Tin tức', 'content' => 'Tin tức công nghệ'],
        ];

        foreach ($tags as $tag) {
            TagBlog::create($tag);
        }

        // Lấy user đầu tiên hoặc tạo user mới
        $user = User::first() ?? User::factory()->create();

        // Tạo blogs mẫu
        $blogs = [
            [
                'slug' => 'dien-thoai-thong-minh-2024',
                'content' => '<h2>Điện thoại thông minh 2024</h2>
                <p>Năm 2024 chứng kiến sự phát triển mạnh mẽ của các công nghệ điện thoại thông minh. Các hãng sản xuất lớn như Apple, Samsung, Xiaomi đã cho ra mắt những sản phẩm với nhiều tính năng đột phá.</p>
                <h3>Những xu hướng chính:</h3>
                <ul>
                    <li>Camera AI thông minh</li>
                    <li>Pin sạc nhanh</li>
                    <li>Màn hình gập</li>
                    <li>5G phổ biến</li>
                </ul>
                <p>Với những cải tiến này, điện thoại thông minh ngày càng trở nên không thể thiếu trong cuộc sống hiện đại.</p>',
                'is_active' => true,
                'user_id' => $user->id,
            ],
            [
                'slug' => 'laptop-gaming-cho-game-thu',
                'content' => '<h2>Laptop Gaming cho Game thủ</h2>
                <p>Laptop gaming đã trở thành lựa chọn phổ biến cho các game thủ nhờ tính di động và hiệu suất cao. Các thương hiệu như ASUS, MSI, Lenovo đang dẫn đầu thị trường này.</p>
                <h3>Đặc điểm quan trọng:</h3>
                <ul>
                    <li>Card đồ họa mạnh mẽ</li>
                    <li>CPU hiệu suất cao</li>
                    <li>RAM dung lượng lớn</li>
                    <li>Màn hình tần số quét cao</li>
                </ul>
                <p>Việc chọn laptop gaming phù hợp sẽ giúp bạn có trải nghiệm chơi game tốt nhất.</p>',
                'is_active' => true,
                'user_id' => $user->id,
            ],
            [
                'slug' => 'phu-kien-cong-nghe-can-thiet',
                'content' => '<h2>Phụ kiện công nghệ cần thiết</h2>
                <p>Phụ kiện công nghệ không chỉ giúp bảo vệ thiết bị mà còn nâng cao trải nghiệm sử dụng. Dưới đây là những phụ kiện không thể thiếu.</p>
                <h3>Danh sách phụ kiện:</h3>
                <ul>
                    <li>Tai nghe không dây</li>
                    <li>Bàn phím cơ</li>
                    <li>Chuột gaming</li>
                    <li>Ổ cứng di động</li>
                    <li>Sạc dự phòng</li>
                </ul>
                <p>Đầu tư vào phụ kiện chất lượng sẽ giúp bạn tận dụng tối đa tiềm năng của các thiết bị công nghệ.</p>',
                'is_active' => true,
                'user_id' => $user->id,
            ],
            [
                'slug' => 'xu-huong-cong-nghe-2024',
                'content' => '<h2>Xu hướng công nghệ 2024</h2>
                <p>Năm 2024 đánh dấu sự phát triển vượt bậc của nhiều công nghệ mới. AI, IoT, và công nghệ xanh đang định hình tương lai.</p>
                <h3>Các xu hướng chính:</h3>
                <ul>
                    <li>Trí tuệ nhân tạo (AI)</li>
                    <li>Internet of Things (IoT)</li>
                    <li>Năng lượng tái tạo</li>
                    <li>Thực tế ảo (VR/AR)</li>
                    <li>Blockchain</li>
                </ul>
                <p>Những công nghệ này sẽ thay đổi cách chúng ta sống và làm việc trong tương lai.</p>',
                'is_active' => true,
                'user_id' => $user->id,
            ],
            [
                'slug' => 'bao-mat-du-lieu-trong-thoi-dai-so',
                'content' => '<h2>Bảo mật dữ liệu trong thời đại số</h2>
                <p>Với sự phát triển của công nghệ, vấn đề bảo mật dữ liệu ngày càng trở nên quan trọng. Các mối đe dọa mạng đang gia tăng với tốc độ chóng mặt.</p>
                <h3>Biện pháp bảo vệ:</h3>
                <ul>
                    <li>Mật khẩu mạnh</li>
                    <li>Xác thực hai yếu tố</li>
                    <li>Mã hóa dữ liệu</li>
                    <li>Backup định kỳ</li>
                    <li>Cập nhật phần mềm</li>
                </ul>
                <p>Việc bảo vệ dữ liệu cá nhân là trách nhiệm của mỗi người dùng công nghệ.</p>',
                'is_active' => true,
                'user_id' => $user->id,
            ],
        ];

        foreach ($blogs as $blogData) {
            $blog = Blog::create($blogData);
            
            // Gán tags ngẫu nhiên cho blog
            $randomTags = TagBlog::inRandomOrder()->limit(rand(1, 3))->get();
            $blog->tags()->attach($randomTags->pluck('id'));
        }
    }
} 