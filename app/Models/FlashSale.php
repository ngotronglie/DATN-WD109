<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    use HasFactory;

    /**
     * Tên bảng tương ứng trong cơ sở dữ liệu
     */
    protected $table = 'flash_sales';

    /**
     * Các cột có thể được gán giá trị hàng loạt (mass assignable)
     */
    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'status',
    ];

    /**
     * Định nghĩa các kiểu dữ liệu cho các cột
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
