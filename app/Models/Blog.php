<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'image',
        'content',
        'is_active',
        'user_id',
        'view',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Automatically create slug from 'slug' field if it is used as a title
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($blog) {
            $blog->slug = Str::slug($blog->slug);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(TagBlog::class, 'tag_blog_blog', 'blog_id', 'tag_id');
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'post_id');
    }
} 