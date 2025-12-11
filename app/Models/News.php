<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $table = 'tb_news';

    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'category',
        'image',
        'author_id',
        'status',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function getExcerptAttribute($value)
    {
        return $value ?: Str::limit(strip_tags($this->content), 150);
    }
}
