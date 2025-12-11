<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $table = 'tb_pages';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_description',
        'meta_keywords',
        'status',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
