<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $table = 'tb_facilities';

    protected $fillable = [
        'name',
        'description',
        'image',
        'features',
        'category',
    ];

    public function getFeaturesArrayAttribute()
    {
        return $this->features ? explode(',', $this->features) : [];
    }
}
