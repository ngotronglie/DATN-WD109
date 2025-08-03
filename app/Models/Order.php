<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'price',
        'name',
        'address',
        'email',
        'phone',
        'note',
        'total_amount',
        'status',
        'payment_method',
        'order_code',
        'voucher_id',
        'status_method',
        'return_requested',
        'return_reason',
    ];
    public function refundRequest()
{
    return $this->hasOne(\App\Models\RefundRequest::class, 'order_id');
}
    // Quan hệ: Một đơn hàng có nhiều chi tiết đơn hàng
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    // Quan hệ với User (nếu có bảng users)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Quan hệ với Voucher (nếu có bảng vouchers)
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }
    
}
