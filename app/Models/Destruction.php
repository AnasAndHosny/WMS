<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Destruction extends Model
{
    use HasFactory;

    protected $fillable = [
        'destructionable_type',
        'destructionable_id',
        'product_id',
        'expiration_date',
        'quantity',
        'cause_id',
        'user_id'
    ];

    public function destructionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function cause(): BelongsTo
    {
        return $this->belongsTo(DestructionCause::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
