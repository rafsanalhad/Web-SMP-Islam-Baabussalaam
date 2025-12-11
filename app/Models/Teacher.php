<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'tb_teachers';

    protected $fillable = [
        'name',
        'position',
        'qualifications',
        'experience',
        'email',
        'phone',
        'photo',
        'category',
    ];

    public function scopePrincipal($query)
    {
        return $query->where('category', 'principal');
    }

    public function scopeTeachers($query)
    {
        return $query->where('category', 'teacher');
    }

    public function scopeStaff($query)
    {
        return $query->where('category', 'staff');
    }
}
