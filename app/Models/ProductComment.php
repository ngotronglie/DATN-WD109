<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
{
    protected $fillable = ['product_id', 'user_id', 'content', 'parent_id', 'is_hidden', 'flash_sale_id'];

    protected $casts = [
        'is_hidden' => 'boolean',
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
}


