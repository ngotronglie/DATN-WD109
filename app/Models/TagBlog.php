<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagBlog extends Model
{
    use HasFactory;

    protected $table = 'tag_blog';

    protected $fillable = [
        'name_tag',
        'content',
    ];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'tag_blog_blog', 'tag_id', 'blog_id');
    }
} 