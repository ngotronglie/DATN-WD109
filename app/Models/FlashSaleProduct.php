<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSaleProduct extends Model
{
    use HasFactory;

    protected $table = 'flash_sale_products';

    protected $fillable = [
        'flash_sale_id',
        'product_variant_id',
        'original_price',
        'sale_price',
        'initial_stock',
        'remaining_stock',
        'sold_quantity',
    ];

    /**
     * Relationship to FlashSale.
     */
    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class, 'flash_sale_id');
    }

    /**
     * Relationship to ProductVariant.
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id')
            ->with(['product', 'color', 'capacity']);
    }

    /**
     * Calculate Discount Percent Attribute.
     */
    public function getDiscountPercentAttribute()
    {
        if ($this->original_price > 0) {
            return round(100 - ($this->sale_price / $this->original_price * 100));
        }
        return 0;
    }
}
