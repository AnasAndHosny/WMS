<?php

namespace App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'previous_status_id',
    ];

    // implement the attributes
    public function getNameAttribute($value)
    {
        return $this->{'name_' . App::getlocale()};
    }

    // Define the scope method
    public function scopeFindByName($query, $name)
    {
        // Apply the query condition
        return $query->where('name_en', $name)->first();
    }

    public function previosStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'previous_status_id');
    }

    public function nextStatuses(): HasMany
    {
        return $this->hasMany(OrderStatus::class, 'previous_status_id');
    }
}
