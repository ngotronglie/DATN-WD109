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

    protected $casts = [
        'price' => 'decimal:2',
        'price_sale' => 'decimal:2',
        'quantity' => 'integer'
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

    /**
     * Relationship với FlashSaleProduct - ProductVariant có thể có nhiều flash sale products
     */
    public function flashSaleProducts()
    {
        return $this->hasMany(FlashSaleProduct::class);
    }

    /**
     * Relationship với FlashSale thông qua FlashSaleProduct
     */
    public function flashSales()
    {
        return $this->belongsToMany(FlashSale::class, 'flash_sale_products')
                    ->withPivot('sale_price', 'sale_quantity', 'initial_stock', 'remaining_stock', 'original_price')
                    ->withTimestamps();
    }

    /**
     * Lấy flash sale đang active cho product variant này
     */
    public function activeFlashSale()
    {
        return $this->hasOne(FlashSaleProduct::class)
                    ->whereHas('flashSale', function ($query) {
                        $query->ongoing();
                    })
                    ->where('remaining_stock', '>', 0);
    }

    /**
     * Kiểm tra product variant có đang trong flash sale không
     */
    public function isInFlashSale()
    {
        return $this->activeFlashSale()->exists();
    }

    /**
     * Lấy giá hiện tại (flash sale price nếu có, không thì price_sale hoặc price)
     */
    public function getCurrentPrice()
    {
        $flashSale = $this->activeFlashSale()->first();
        
        if ($flashSale && $flashSale->hasStock()) {
            return $flashSale->sale_price;
        }
        
        return $this->price_sale ?? $this->price;
    }

    /**
     * Lấy thông tin flash sale đang active
     */
    public function getActiveFlashSaleInfo()
    {
        return $this->activeFlashSale()->with('flashSale')->first();
    }
}
