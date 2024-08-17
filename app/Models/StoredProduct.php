<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Scope;

class StoredProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'storable_type',
        'storable_id',
        'product_id',
        'expiration_date',
        'valid_quantity',
        'expired_quantity',
        'active',
        'max'
    ];

    // Define the scope method
    public function scopeActive($query)
    {
        // Apply the query condition
        return $query->where('active', true)
            ->where('valid_quantity', '!=', 0)
            ->valid();
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

    public function scopeValid($query)
    {
        return $query->where(function ($query) {
            $query->where('expiration_date', '>', Carbon::today()->endOfDay())
                ->orWhereNull('expiration_date');
        });
    }
    public function scopeExpired($query)
    {
        return $query->where(function ($query) {
            $query->whereNotNull('expiration_date')
                ->where('expiration_date', '<=', Carbon::today()->endOfDay());
        });
    }

    public function scopeExpireBefore($query, $date)
    {
        return $query->where(function ($query) use ($date) {
            $query->whereNotNull('expiration_date')
                ->where('expiration_date', '<=', Carbon::parse($date));
        });
    }

    public function scopeExpireAfter($query, $date)
    {
        return $query->where('expiration_date', '>=', Carbon::parse($date));
    }

    public function scopeQuantityLessThan($query, $quantity)
    {
        return $query->where('valid_quantity', '<=', $quantity);
    }

    public function scopeQuantityMoreThan($query, $quantity)
    {
        return $query->where('valid_quantity', '>=', $quantity);
    }
}
