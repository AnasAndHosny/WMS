<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoredProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'storable_type',
        'storable_id',
        'product_id',
        'expiration_date',
        'valid_quantity',
        'active',
        'max'
    ];

    // Define the scope method
    public function scopeActive($query)
    {
        // Apply the query condition
        return $query->where('active', true)
            ->where('valid_quantity', '!=', 0);
    }

    /**
     * Get the parent storable model (warehouse or distribution center).
     */
    public function storable(): MorphTo
    {
        return $this->morphTo();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
