<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'is_active',
        'view_count',
        'categories_id',
        'image',
        'price'
    ];

    // Relationship with category
    public function category()
    {
        return $this->belongsTo(Categories::class, 'categories_id');
    }

    // Relationship with product variants
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    // Lấy biến thể chính (ví dụ: biến thể đầu tiên)
    public function mainVariant()
    {
        return $this->hasOne(ProductVariant::class, 'product_id')->orderBy('id');
    }

    /**
     * Relationship với Favorite - Product có nhiều favorites
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Relationship với User thông qua Favorite - Product được yêu thích bởi nhiều users
     */
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class);
    }

    // Get all active products with pagination
    public function loadAll()
    {
        return $this->with(['category', 'variants'])->orderBy('id', 'desc')->paginate(10);
    }

    // Get single product by ID
    public function loadOneID($id)
    {
        return $this->with(['category', 'variants'])->find($id);
    }

    // Insert new product
    public function insertData($params)
    {
        return $this->create($params);
    }

    // Update product
    public function updateData($params, $id)
    {
        return $this->find($id)->update($params);
    }

    // Delete product
    public function deleteData($id)
    {
        return $this->find($id)->delete();
    }
}
