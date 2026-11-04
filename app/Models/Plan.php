<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'price', 'is_active', 'includes_all_disciplines', 'discipline_limit'])]
class Plan extends Model
{
    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'is_active' => 'boolean', 'includes_all_disciplines' => 'boolean', 'discipline_limit' => 'integer'];
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }
}
