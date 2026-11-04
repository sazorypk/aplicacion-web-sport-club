<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'plan_id', 'sede_id', 'price_at_signup', 'status', 'start_date', 'end_date'])]
class Membership extends Model
{
    protected function casts(): array
    {
        return ['price_at_signup' => 'decimal:2', 'start_date' => 'date', 'end_date' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
