<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceData extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'face_descriptor',
        'face_image_path',
        'is_verified',
        'last_verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'face_descriptor' => 'array',
        'is_verified' => 'boolean',
        'last_verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the face data.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
