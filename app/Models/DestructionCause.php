<?php

namespace App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DestructionCause extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar'
    ];

    // implement the attributes
    public function getNameAttribute($value)
    {
        return $this->{'name_' . App::getlocale()};
    }

    public function destructions(): HasMany
    {
        return $this->hasMany('destructions', 'cause_id');
    }
}
