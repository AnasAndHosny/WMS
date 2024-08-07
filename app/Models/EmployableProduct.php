<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\ProductQuantityDecreased;
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

    public static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            // Store the current total_quantity before updating
            $model->quantityBeforeUpdate = $model->getOriginal('total_quantity');
        });

        static::updated(function ($model) {
            $quantityBeforeUpdate = $model->quantityBeforeUpdate;
            $quantityAfterUpdate = $model->total_quantity;

            // Check if quantity goes below min_quantity after update and was above min_quantity before update
            if ($quantityAfterUpdate < $model->min_quantity && $quantityBeforeUpdate >= $model->min_quantity) {
                event(new ProductQuantityDecreased($model->employable_type, $model->employable_id, $model->product));
            }
        });
    }
}
