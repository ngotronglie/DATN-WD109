<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FlashSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'is_active'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean'
    ];

    /**
     * Relationship với FlashSaleProduct - FlashSale có nhiều products
     */
    public function flashSaleProducts()
    {
        return $this->hasMany(FlashSaleProduct::class);
    }

    /**
     * Relationship với ProductVariant thông qua FlashSaleProduct
     */
    public function productVariants()
    {
        return $this->belongsToMany(ProductVariant::class, 'flash_sale_products')
                    ->withPivot('sale_price', 'sale_quantity', 'initial_stock', 'remaining_stock', 'original_price')
                    ->withTimestamps();
    }

    /**
     * Scope để lấy flash sales đang active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope để lấy flash sales đang diễn ra
     */
    public function scopeOngoing($query)
    {
        $now = Carbon::now();
        return $query->where('start_time', '<=', $now)
                    ->where('end_time', '>=', $now)
                    ->where('is_active', true);
    }

    /**
     * Scope để lấy flash sales sắp diễn ra
     */
    public function scopeUpcoming($query)
    {
        $now = Carbon::now();
        return $query->where('start_time', '>', $now)
                    ->where('is_active', true);
    }

    /**
     * Kiểm tra flash sale có đang diễn ra không
     */
    public function isOngoing()
    {
        $now = Carbon::now();
        return $this->is_active && 
               $this->start_time <= $now && 
               $this->end_time >= $now;
    }

    /**
     * Kiểm tra flash sale đã kết thúc chưa
     */
    public function isExpired()
    {
        return Carbon::now() > $this->end_time;
    }

    /**
     * Lấy thời gian còn lại của flash sale (seconds)
     */
    public function getTimeRemaining()
    {
        if ($this->isExpired()) {
            return 0;
        }
        
        return Carbon::now()->diffInSeconds($this->end_time, false);
    }
}
