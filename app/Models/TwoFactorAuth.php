<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoFactorAuth extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'two_factor_auth';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'enabled',
        'secret_key',
        'recovery_codes',
        'confirmed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'enabled' => 'boolean',
        'confirmed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the two-factor authentication.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the recovery codes as an array.
     *
     * @return array
     */
    public function getRecoveryCodesArray()
    {
        return json_decode($this->recovery_codes, true) ?? [];
    }

    /**
     * Set the recovery codes from an array.
     *
     * @param array $codes
     * @return void
     */
    public function setRecoveryCodesArray(array $codes)
    {
        $this->recovery_codes = json_encode($codes);
    }

    /**
     * Generate new recovery codes.
     *
     * @return array
     */
    public function generateRecoveryCodes()
    {
        $recoveryCodes = [];

        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = $this->generateRecoveryCode();
        }

        $this->setRecoveryCodesArray($recoveryCodes);
        $this->save();

        return $recoveryCodes;
    }

    /**
     * Generate a single recovery code.
     *
     * @return string
     */
    protected function generateRecoveryCode()
    {
        return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10);
    }
}
