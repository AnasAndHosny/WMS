<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class StoredProductStatusFilter implements Filter
{
    public function __invoke(Builder $query, $value, String $property): Builder
    {
        if ($value == 'valid') {
            return $query->valid();
        }

        if ($value == 'expired') {
            return $query->expired();
        }

        return $query;
    }
}

