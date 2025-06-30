<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'con_name',
        'con_email',
        'con_subject',
        'con_phone',
        'con_message',
        'status',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Chờ xử lý',
            'replied' => 'Đã phản hồi',
            default => 'Không xác định'
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'badge-warning',
            'replied' => 'badge-success',
            default => 'badge-secondary'
        };
    }
} 