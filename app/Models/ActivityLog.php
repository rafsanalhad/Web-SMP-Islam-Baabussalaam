<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'tb_activity_log';

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'activity_type',
        'description',
        'module',
        'record_id',
        'ip_address',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function logActivity($userId, $type, $description, $module = null, $recordId = null)
    {
        return self::create([
            'user_id' => $userId,
            'activity_type' => $type,
            'description' => $description,
            'module' => $module,
            'record_id' => $recordId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
