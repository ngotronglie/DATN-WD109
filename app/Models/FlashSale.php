<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlashSale extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'flash_sales'; // Sửa tên table cho đúng chuẩn số nhiều

    protected $fillable = [
        'product_id',
        'sale_price',
        'sale_quantity',
        'start_time',
        'end_time',
        'trang_thai',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'sale_price' => 'decimal:0' // Sửa thành decimal:0 vì giá không cần 2 số thập phân
    ];

    // Các relations và methods khác giữ nguyên
}
