<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'activity', 'table_name', 'record_id',
        'old_values', 'new_values', 'ip_address',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log(string $activity, string $tableName = null, int $recordId = null, array $oldValues = null, array $newValues = null): void
    {
        static::create([
            'user_id'    => Auth::id(),
            'activity'   => $activity,
            'table_name' => $tableName,
            'record_id'  => $recordId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }
}