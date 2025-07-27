<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSaleItem extends Model
{
    use HasFactory;

    protected $table = 'flash_sale_items';

    protected $fillable = [
        'flash_sale_id',
        'product_variant_id',
        'flash_price',
        'quantity_limit',
        'sold_quantity',
    ];

    // Quan hệ với FlashSale
    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class);
    }

    // Quan hệ với ProductVariant
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
