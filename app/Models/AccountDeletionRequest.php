<?php

namespace App\Models;

use Database\Factories\AccountDeletionRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountDeletionRequest extends Model
{
    /** @use HasFactory<AccountDeletionRequestFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'reviewed_at', 'completed_at'];

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime', 'completed_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
