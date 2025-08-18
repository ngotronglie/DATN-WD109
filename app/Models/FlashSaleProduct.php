<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSaleProduct extends Model
{
    use HasFactory;

    /**
     * Tên bảng tương ứng với model.
     */
    protected $table = 'flash_sale_products';

    /**
     * Những cột có thể được gán giá trị hàng loạt.
     */
    protected $fillable = [
        'flash_sale_id',
        'product_id',
        'original_price',
        'sale_price',
        'initial_stock',
        'remaining_stock',
        'sold_quantity',
    ];

    /**
     * Định nghĩa quan hệ với model FlashSale.
     */
    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class, 'flash_sale_id');
    }

    /**
     * Định nghĩa quan hệ với model Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Định nghĩa accessor cho `discount_percent`.
     * Tính toán dựa trên giá gốc `original_price` và giá giảm `sale_price`.
     */
    public function getDiscountPercentAttribute()
    {
        if ($this->original_price > 0) {
            return round(100 - ($this->sale_price / $this->original_price * 100));
        }
        return 0;
    }
}
