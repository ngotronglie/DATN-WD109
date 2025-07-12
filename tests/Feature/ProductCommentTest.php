<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductComment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ProductCommentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo user test
        $this->user = User::factory()->create([
            'role_id' => 2, // User role
        ]);
        
        // Tạo product test
        $this->product = Product::factory()->create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function user_can_create_comment()
    {
        $this->actingAs($this->user);

        $commentData = [
            'content' => 'Great product!',
            'rating' => 5,
        ];

        $response = $this->post(route('product.comments.store', $this->product->id), $commentData);

        $response->assertRedirect();
        $this->assertDatabaseHas('product_comments', [
            'product_id' => $this->product->id,
            'user_id' => $this->user->id,
            'content' => 'Great product!',
            'rating' => 5,
        ]);
    }

    /** @test */
    public function user_can_reply_to_comment()
    {
        $this->actingAs($this->user);

        // Tạo comment gốc
        $parentComment = ProductComment::create([
            'product_id' => $this->product->id,
            'user_id' => $this->user->id,
            'content' => 'Original comment',
            'rating' => 4,
        ]);

        $replyData = [
            'content' => 'This is a reply',
        ];

        $response = $this->post(route('product.comments.reply', $parentComment->id), $replyData);

        $response->assertRedirect();
        $this->assertDatabaseHas('product_comments', [
            'product_id' => $this->product->id,
            'user_id' => $this->user->id,
            'content' => 'This is a reply',
            'parent_id' => $parentComment->id,
        ]);
    }

    /** @test */
    public function user_can_delete_own_comment()
    {
        $this->actingAs($this->user);

        $comment = ProductComment::create([
            'product_id' => $this->product->id,
            'user_id' => $this->user->id,
            'content' => 'Test comment',
            'rating' => 3,
        ]);

        $response = $this->delete(route('product.comments.destroy', $comment->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('product_comments', [
            'id' => $comment->id,
        ]);
    }

    /** @test */
    public function guest_cannot_create_comment()
    {
        $commentData = [
            'content' => 'Test comment',
            'rating' => 4,
        ];

        $response = $this->post(route('product.comments.store', $this->product->id), $commentData);

        $response->assertRedirect(route('auth.login'));
    }

    /** @test */
    public function comment_validation_works()
    {
        $this->actingAs($this->user);

        // Test empty content
        $response = $this->post(route('product.comments.store', $this->product->id), [
            'content' => '',
            'rating' => 5,
        ]);

        $response->assertSessionHasErrors(['content']);

        // Test invalid rating
        $response = $this->post(route('product.comments.store', $this->product->id), [
            'content' => 'Valid content',
            'rating' => 6, // Invalid rating
        ]);

        $response->assertSessionHasErrors(['rating']);
    }

    /** @test */
    public function product_has_comments_relationship()
    {
        ProductComment::create([
            'product_id' => $this->product->id,
            'user_id' => $this->user->id,
            'content' => 'Test comment',
            'rating' => 4,
        ]);

        $this->assertCount(1, $this->product->comments);
    }

    /** @test */
    public function comment_has_user_relationship()
    {
        $comment = ProductComment::create([
            'product_id' => $this->product->id,
            'user_id' => $this->user->id,
            'content' => 'Test comment',
            'rating' => 4,
        ]);

        $this->assertEquals($this->user->id, $comment->user->id);
    }

    /** @test */
    public function comment_has_replies_relationship()
    {
        $parentComment = ProductComment::create([
            'product_id' => $this->product->id,
            'user_id' => $this->user->id,
            'content' => 'Parent comment',
            'rating' => 4,
        ]);

        $reply = ProductComment::create([
            'product_id' => $this->product->id,
            'user_id' => $this->user->id,
            'content' => 'Reply comment',
            'parent_id' => $parentComment->id,
        ]);

        $this->assertCount(1, $parentComment->replies);
        $this->assertEquals($reply->id, $parentComment->replies->first()->id);
    }

    /** @test */
    public function admin_can_access_product_comments_management()
    {
        $admin = User::factory()->create([
            'role_id' => 1, // Admin role
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.product-comments.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_delete_any_comment()
    {
        $admin = User::factory()->create([
            'role_id' => 1, // Admin role
        ]);

        $this->actingAs($admin);

        $comment = ProductComment::create([
            'product_id' => $this->product->id,
            'user_id' => $this->user->id,
            'content' => 'Test comment',
            'rating' => 4,
        ]);

        $response = $this->delete(route('admin.product-comments.destroy', $comment->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('product_comments', [
            'id' => $comment->id,
        ]);
    }

    /** @test */
    public function product_detail_page_shows_comments()
    {
        ProductComment::create([
            'product_id' => $this->product->id,
            'user_id' => $this->user->id,
            'content' => 'Test comment',
            'rating' => 4,
        ]);

        $response = $this->get(route('product.detail', $this->product->slug));

        $response->assertStatus(200);
        $response->assertSee('Test comment');
    }
}
