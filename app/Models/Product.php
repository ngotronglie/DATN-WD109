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
        'categories_id'
    ];

    // Relationship with category
    public function category()
    {
        return $this->belongsTo(Categories::class, 'categories_id');
    }

    // Relationship with product variants
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
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
