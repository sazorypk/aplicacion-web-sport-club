<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'address', 'is_active'])]
class Sede extends Model
{
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }
}
