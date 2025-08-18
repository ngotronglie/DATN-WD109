<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'color_id',
        'capacity_id',
        'image',
        'price',
        'price_sale',
        'quantity'
    ];

    // Relationship with product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship with color
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    // Relationship with capacity
    public function capacity()
    {
        return $this->belongsTo(Capacity::class);
    }

    public function flashSaleProducts()
    {
        return $this->hasMany(FlashSaleProduct::class, 'product_variant_id');
    }
}
