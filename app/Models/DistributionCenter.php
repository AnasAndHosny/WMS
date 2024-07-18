<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\App;

class DistributionCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name_ar',
        'name_en',
        'state_id',
        'street_address_ar',
        'street_address_en',
        'warehouse_id'
    ];

    // implement the attributes
    public function getNameAttribute($value)
    {
        return $this->{'name_' . App::getlocale()};
    }

    public function getStreetAddressAttribute($value)
    {
        return $this->{'street_address_' . App::getlocale()};
    }

    public function getCityAttribute($value)
    {
        return $this->state->city;
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function employees(): MorphMany
    {
        return $this->morphMany(Employee::class, 'employable');
    }

    public function storedProducts(): MorphMany
    {
        return $this->morphMany(StoredProduct::class, 'storable');
    }

    public function buyOrders(): MorphMany
    {
        return $this->morphMany(Order::class, 'orderable_by');
    }

    public function sales(): MorphMany
    {
        return $this->morphMany(Sale::class, 'salable');
    }
}
