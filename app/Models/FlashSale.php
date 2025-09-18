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
     * Lấy sản phẩm Flash Sale theo priority (cao nhất trước)
     */
    public function flashSaleProductsByPriority()
    {
        return $this->hasMany(FlashSaleProduct::class)
                    ->where('status', 'active')
                    ->orderBy('priority', 'desc')
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Lấy sản phẩm nổi bật (featured)
     */
    public function featuredProducts()
    {
        return $this->hasMany(FlashSaleProduct::class)
                    ->where('status', 'featured')
                    ->orderBy('priority', 'desc')
                    ->orderBy('created_at', 'asc');
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
     * Kiểm tra xem flash sale có đang hoạt động không
     */
    public function isActive()
    {
        return $this->is_active && 
               $this->start_time <= now() && 
               $this->end_time > now();
    }

    /**
     * Kiểm tra xem flash sale có đang diễn ra không (alias cho isActive)
     */
    public function isOngoing()
    {
        return $this->isActive();
    }

    /**
     * Trạng thái theo thời gian: upcoming | ongoing | ended
     */
    public function getStatusCodeAttribute()
    {
        $now = Carbon::now();
        if ($this->end_time && $now->gt($this->end_time)) {
            return 'ended';
        }
        if ($this->start_time && $now->lt($this->start_time)) {
            return 'upcoming';
        }
        return 'ongoing';
    }

    /**
     * Nhãn trạng thái hiển thị
     */
    public function getStatusLabelAttribute()
    {
        switch ($this->status_code) {
            case 'upcoming':
                return 'Chưa bắt đầu';
            case 'ended':
                return 'Đã kết thúc';
            default:
                return 'Đã bắt đầu';
        }
    }

    /**
     * Lấy Flash Sale đang hoạt động
     */
    public static function getActiveFlashSales()
    {
        return self::where('is_active', true)
                   ->where('end_time', '>', now())
                   ->with(['flashSaleProductsByPriority.productVariant.product', 
                          'flashSaleProductsByPriority.productVariant.color',
                          'flashSaleProductsByPriority.productVariant.capacity'])
                   ->orderBy('start_time')
                   ->get();
    }

    /**
     * Lấy sản phẩm nổi bật từ tất cả Flash Sale đang hoạt động
     */
    public static function getFeaturedProducts($limit = 8)
    {
        return FlashSaleProduct::whereHas('flashSale', function($query) {
                    $query->where('is_active', true)
                          ->where('start_time', '<=', now())
                          ->where('end_time', '>', now());
                })
                ->where('status', 'featured')
                ->where('remaining_stock', '>', 0)
                ->with(['productVariant.product', 'productVariant.color', 'productVariant.capacity', 'flashSale'])
                ->orderBy('priority', 'desc')
                ->orderBy('created_at', 'asc')
                ->limit($limit)
                ->get();
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
