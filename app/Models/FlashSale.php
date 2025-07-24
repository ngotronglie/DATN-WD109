<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    use HasFactory;

    protected $table = 'flash_sale';

    protected $fillable = [
        'product_id',
        'sale_price',
        'sale_quantity',
        'start_time',
        'end_time',
        'trang_thai'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'sale_price' => 'decimal:2'
    ];

    /**
     * Relationship với Product - FlashSale thuộc về một Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope để lấy các flash sale đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 'kích_hoạt')
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>=', now());
    }

    /**
     * Kiểm tra xem flash sale có đang hoạt động không
     */
    public function isActive()
    {
        return $this->trang_thai === 'kích_hoạt' &&
               $this->start_time <= now() &&
               $this->end_time >= now();
    }

    /**
     * Kiểm tra xem flash sale còn hàng không
     */
    public function hasStock()
    {
        return $this->sale_quantity > 0;
    }

    /**
     * Tính số tiền tiết kiệm được
     */
    public function getSavingAmountAttribute()
    {
        return $this->product->price - $this->sale_price;
    }

    /**
     * Tính phần trăm giảm giá
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->product->price == 0) return 0;
        return round(($this->getSavingAmountAttribute() / $this->product->price) * 100);
    }
}
