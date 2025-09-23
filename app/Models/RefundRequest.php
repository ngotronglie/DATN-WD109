<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }


    protected $fillable = [
        'user_id',
        'order_id',
        'type',
        'bank_name',
        'account_name',
        'bank_number',
        'reason',
        'image',
        'refund_requested_at',
    ];
}
