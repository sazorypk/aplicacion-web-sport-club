<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const PRIVACY_POLICY_VERSION = '2026-11-11';

    protected $fillable = ['name', 'username', 'cedula', 'phone', 'email', 'password', 'privacy_consent_at', 'privacy_policy_version'];

    protected $hidden = ['password', 'remember_token', 'profile_photo_path'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'privacy_consent_at' => 'datetime', 'anonymized_at' => 'datetime', 'password' => 'hashed'];
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }
}
