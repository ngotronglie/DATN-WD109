<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductComment;
use App\Models\Product;
use App\Models\User;

class ProductCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $users = User::all();

        if ($products->isEmpty() || $users->isEmpty()) {
            return;
        }

        $comments = [
            [
                'content' => 'Sản phẩm rất tốt, chất lượng cao và giá cả hợp lý. Tôi rất hài lòng với việc mua sản phẩm này!',
                'rating' => 5
            ],
            [
                'content' => 'Giao hàng nhanh, sản phẩm đúng như mô tả. Đóng gói cẩn thận, sẽ mua lại!',
                'rating' => 4
            ],
            [
                'content' => 'Chất lượng tốt, nhưng giá hơi cao một chút. Nhìn chung là hài lòng.',
                'rating' => 4
            ],
            [
                'content' => 'Sản phẩm đẹp, phù hợp với nhu cầu sử dụng. Khuyến nghị mọi người nên mua!',
                'rating' => 5
            ],
            [
                'content' => 'Tốt nhưng có thể cải thiện thêm về mặt thiết kế. Nhìn chung là ổn.',
                'rating' => 3
            ]
        ];

        $replies = [
            'Cảm ơn bạn đã đánh giá tích cực! Chúng tôi sẽ cố gắng phục vụ tốt hơn.',
            'Chúng tôi rất vui khi bạn hài lòng với sản phẩm. Cảm ơn bạn!',
            'Cảm ơn phản hồi của bạn. Chúng tôi sẽ xem xét để cải thiện sản phẩm.'
        ];

        foreach ($products as $product) {
            // Tạo bình luận gốc
            foreach ($comments as $commentData) {
                $comment = ProductComment::create([
                    'product_id' => $product->id,
                    'user_id' => $users->random()->id,
                    'content' => $commentData['content'],
                    'rating' => $commentData['rating']
                ]);

                // Tạo một số trả lời cho bình luận
                if (rand(0, 1)) {
                    ProductComment::create([
                        'product_id' => $product->id,
                        'user_id' => $users->random()->id,
                        'content' => $replies[array_rand($replies)],
                        'parent_id' => $comment->id
                    ]);
                }
            }
        }
    }
}
