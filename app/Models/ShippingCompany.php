<?php

namespace App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'state_id',
        'street_address_ar',
        'street_address_en'
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
}
