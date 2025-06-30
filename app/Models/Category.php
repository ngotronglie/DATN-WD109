<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'ID';

    protected $fillable = [
        'Name',
        'Is_active',
        'Parent_id',
        'Image'
    ];

    protected $casts = [
        'Is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'ID');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'Parent_id', 'ID');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'Parent_id', 'ID');
    }

    public static function loadAll()
    {
        return self::where('Is_active', true)->get();
    }
} 