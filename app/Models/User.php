<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function results()
    {
        return $this->hasMany(QuizResult::class);
    }

    /**
     * Get the face data associated with the user.
     */
    public function faceData()
    {
        return $this->hasOne(FaceData::class);
    }

    /**
     * Check if the user has registered their face.
     */
    public function hasFaceRegistered()
    {
        return $this->faceData && $this->faceData->face_descriptor !== null;
    }

    /**
     * Get the two-factor authentication configuration for the user.
     */
    public function twoFactorAuth()
    {
        return $this->hasOne(TwoFactorAuth::class);
    }

    /**
     * Check if the user has two-factor authentication enabled.
     */
    public function hasTwoFactorEnabled()
    {
        return $this->twoFactorAuth && $this->twoFactorAuth->enabled && $this->twoFactorAuth->confirmed_at !== null;
    }

    /**
     * Enable two-factor authentication for the user.
     */
    public function enableTwoFactorAuth($secretKey)
    {
        $twoFactorAuth = $this->twoFactorAuth ?? new TwoFactorAuth(['user_id' => $this->id]);
        $twoFactorAuth->secret_key = $secretKey;
        $twoFactorAuth->enabled = true;
        $twoFactorAuth->save();

        return $twoFactorAuth;
    }

    /**
     * Confirm two-factor authentication for the user.
     */
    public function confirmTwoFactorAuth()
    {
        if ($this->twoFactorAuth) {
            $this->twoFactorAuth->confirmed_at = now();
            $this->twoFactorAuth->save();

            return $this->twoFactorAuth->generateRecoveryCodes();
        }

        return null;
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disableTwoFactorAuth()
    {
        if ($this->twoFactorAuth) {
            $this->twoFactorAuth->enabled = false;
            $this->twoFactorAuth->confirmed_at = null;
            $this->twoFactorAuth->secret_key = null;
            $this->twoFactorAuth->recovery_codes = null;
            $this->twoFactorAuth->save();
        }
    }
}
