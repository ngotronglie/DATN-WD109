<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 
        'user_id', 
        'content', 
        'parent_id', 
        'rating'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(ProductComment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(ProductComment::class, 'parent_id');
    }

    // Lấy tất cả bình luận gốc (không phải reply)
    public function scopeRootComments($query)
    {
        return $query->whereNull('parent_id');
    }

    // Lấy bình luận với replies
    public function scopeWithReplies($query)
    {
        return $query->with(['replies.user', 'user'])->rootComments();
    }
}
