<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSaleProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'flash_sale_id',
        'product_variant_id',
        'original_price',
        'sale_price',
        'sale_quantity',
        'initial_stock',
        'remaining_stock',
        'priority',
        'status',
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'sale_quantity' => 'integer',
        'initial_stock' => 'integer',
        'remaining_stock' => 'integer',
        'priority' => 'integer'
    ];

    /**
     * Relationship với FlashSale - FlashSaleProduct thuộc về một FlashSale
     */
    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class);
    }

    /**
     * Relationship với ProductVariant - FlashSaleProduct thuộc về một ProductVariant
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Scope để lấy các sản phẩm flash sale còn stock
     */
    public function scopeInStock($query)
    {
        return $query->where('remaining_stock', '>', 0);
    }

    /**
     * Scope để lấy các sản phẩm flash sale đang active
     */
    public function scopeActive($query)
    {
        return $query->whereHas('flashSale', function ($q) {
            $q->ongoing();
        });
    }

    /**
     * Kiểm tra sản phẩm flash sale còn stock không
     */
    public function hasStock()
    {
        return $this->remaining_stock > 0;
    }

    /**
     * Tính phần trăm giảm giá
     */
    public function getDiscountPercentage()
    {
        if ($this->original_price <= 0) {
            return 0;
        }
        
        return round((($this->original_price - $this->sale_price) / $this->original_price) * 100);
    }

    /**
     * Tính số tiền tiết kiệm
     */
    public function getSavingAmount()
    {
        return $this->original_price - $this->sale_price;
    }

    /**
     * Giảm stock khi có người mua
     */
    /**
     * Lấy số lượng đã bán từ order_details
     */
    public function getSoldQuantityAttribute()
    {
        return \App\Models\OrderDetail::where('flash_sale_id', $this->flash_sale_id)
            ->where('product_variant_id', $this->product_variant_id)
            ->sum('quantity');
    }

    public function decreaseStock($quantity = 1)
    {
        if ($this->remaining_stock >= $quantity) {
            $this->remaining_stock -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Tăng stock khi hủy đơn hàng
     */
    public function increaseStock($quantity = 1)
    {
        $this->remaining_stock += $quantity;
        $this->save();
    }
}
