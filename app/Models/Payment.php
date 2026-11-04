<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['membership_id', 'amount', 'status', 'method', 'reference', 'paid_at'])]
class Payment extends Model
{
    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'paid_at' => 'datetime'];
    }

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }
}
