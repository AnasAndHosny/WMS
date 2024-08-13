<?php

namespace App\Models;

use App\Models\SubCategory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'manufacturer_id',
        'price',
        'subcategory_id',
        'barcode'
    ];

    /**
     * Interact with the product's price.
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100
        );
    }

    // implement the attributes
    public function getNameAttribute($value)
    {
        return $this->{'name_' . App::getlocale()};
    }

    public function getDescriptionAttribute($value)
    {
        return $this->{'description_' . App::getlocale()};
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id', 'id');
    }

    public function employableProduct(): HasOne
    {
        $employable = Auth::user()->employee->employable;
        $employableType = get_class($employable);

        return $this->hasOne(EmployableProduct::class)
            ->where('employable_type', $employableType)
            ->where('employable_id', $employable->id);
    }

    public function orderedProducts(): HasMany
    {
        return $this->hasMany(OrderedProduct::class);
    }
}
