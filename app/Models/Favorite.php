<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    /**
     * Các trường có thể mass assign
     */
    protected $fillable = [
        'user_id',
        'product_id'
    ];

    /**
     * Relationship với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship với Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
