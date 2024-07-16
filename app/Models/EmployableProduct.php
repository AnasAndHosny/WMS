<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployableProduct extends Model
{
    use HasFactory, HasCompositeKey;
    protected $fillable = [
        'employable_type',
        'employable_id',
        'product_id',
        'total_quantity',
        'min_quantity'
    ];

    /**
     * Get the parent employable model (warehouse or distribution center).
     */
    public function employable(): MorphTo
    {
        return $this->morphTo();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected $primaryKey = ['employable_type', 'employable_id', 'product_id'];

    public $incrementing = false;

    public function getKeyName()
{
    return ['employable_type', 'employable_id', 'product_id'];
}
}
