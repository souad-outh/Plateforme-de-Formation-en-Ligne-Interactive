<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        'description',
        'properties',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Get the user that performed the activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an activity.
     *
     * @param string $action
     * @param string|null $description
     * @param array|null $properties
     * @return ActivityLog
     */
    public static function log($action, $description = null, $properties = null)
    {
        $user = auth()->user();
        
        return self::create([
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
            'properties' => $properties,
        ]);
    }
}
