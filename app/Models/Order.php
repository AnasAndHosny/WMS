<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'orderable_from_type',
        'orderable_from_id',
        'orderable_by_type',
        'orderable_by_id',
        'status_id',
        'order_cost',
        'shipping_cost',
        'total_cost',
        'user_id'
    ];

    protected static $availableOrderableFrom = [
        'Warehouse' => Warehouse::class,
        'Manufacturer' => Manufacturer::class,
    ];

    protected function orderableFromType(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => array_search($value, self::$availableOrderableFrom) ?: $value,
            set: fn ($value) => self::$availableOrderableFrom[$value] ?? $value
        );
    }

    protected static $availableOrderableBy = [
        'Warehouse' => Warehouse::class,
        'DistributionCenter' => DistributionCenter::class,
    ];

    protected function orderableByType(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => array_search($value, self::$availableOrderableBy) ?: $value,
            set: fn ($value) => self::$availableOrderableBy[$value] ?? $value
        );
    }

    protected function orderCost(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100
        );
    }

    protected function shippingCost(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100
        );
    }

    protected function totalCost(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100
        );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

    public function orderableFrom(): MorphTo
    {
        return $this->morphTo();
    }

    public function orderableBy(): MorphTo
    {
        return $this->morphTo();
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function orderedProducts(): HasMany
    {
        return $this->hasMany(OrderedProduct::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'ordered_products', 'order_id', 'product_id');
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class, 'order_id');
    }

    public static function getAvailableOrderableFrom(): array
    {
        return self::$availableOrderableFrom;
    }
}
