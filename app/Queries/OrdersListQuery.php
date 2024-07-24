<?php

namespace App\Queries;

use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class OrdersListQuery extends QueryBuilder
{
    public function __construct($orders, $request, bool $sellOrders = true)
    {
        // Determine the morphTo relation based on the type of orders
        $morphTo = $sellOrders ? 'orderableBy' : 'orderableFrom';

        // Handle ID filter: convert string IDs to integers if necessary
        if ($request->has('filter.id')) {
            $filters = $request->get('filter');
            $filters['id'] = $this->extractIdFromString($filters['id']);
            $request->merge(['filter' => $filters]);
        }

        // Handle cost filter: convert cost to cents
        if ($request->has('filter.cost')) {
            $filters = $request->get('filter');
            $filters['cost'] = (float)$filters['cost'] * 100 ?: $filters['cost'];
            $request->merge(['filter' => $filters]);
        }

        parent::__construct($orders);

        $this
            ->defaultSort('-created_at')
            ->allowedSorts(
                'id',
                AllowedSort::field('date', 'created_at'),
                AllowedSort::field('cost', 'total_cost'),
            )
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('date', 'created_at'),
                AllowedFilter::exact('cost', 'total_cost'),
                AllowedFilter::scope('ordered_before'),
                AllowedFilter::scope('ordered_after'),
                AllowedFilter::scope('cheaper_than'),
                AllowedFilter::scope('more_expensive_than'),
            ])
            // Dynamic filters based on request input
            ->applyDynamicFilters($request, $morphTo);
    }

    /**
     * Apply dynamic filters based on the request input.
     *
     * @param $request
     * @param $morphTo
     */
    private function applyDynamicFilters($request, $morphTo)
    {
        // Filter by status
        $this->when($request->has('filter.status'), function ($query) use ($request) {
            $query->withWhereHas('status', function ($query) use ($request) {
                $query->where('name_en', 'like', '%' . $request->input('filter.status') . '%')
                    ->orWhere('name_ar', 'like', '%' . $request->input('filter.status') . '%');
            });
        });

        // Filter by name
        $this->when($request->has('filter.name'), function ($query) use ($request, $morphTo) {
            $query->whereHasMorph($morphTo, '*', function ($query) use ($request) {
                $query->where('name_en', 'like', '%' . $request->input('filter.name') . '%')
                    ->orWhere('name_ar', 'like', '%' . $request->input('filter.name') . '%');
            });
        });

        // Filter by address
        $this->when($request->has('filter.address'), function ($query) use ($request, $morphTo) {
            $query->whereHasMorph($morphTo, '*', function ($query) use ($request) {
                $query->where('street_address_en', 'like', '%' . $request->input('filter.address') . '%')
                    ->orWhere('street_address_ar', 'like', '%' . $request->input('filter.address') . '%');
            });
        });

        // Filter by city
        $this->when($request->has('filter.city'), function ($query) use ($request, $morphTo) {
            $query->whereHasMorph($morphTo, '*', function ($query) use ($request) {
                $query->whereHas('state', function ($query) use ($request) {
                    $query->whereHas('city', function ($query) use ($request) {
                        $query->where('name_en', 'like', '%' . $request->input('filter.city') . '%')
                            ->orWhere('name_ar', 'like', '%' . $request->input('filter.city') . '%');
                    });
                });
            });
        });

        // Filter by state
        $this->when($request->has('filter.state'), function ($query) use ($request, $morphTo) {
            $query->whereHasMorph($morphTo, '*', function ($query) use ($request) {
                $query->whereHas('state', function ($query) use ($request) {
                    $query->where('name_en', 'like', '%' . $request->input('filter.state') . '%')
                        ->orWhere('name_ar', 'like', '%' . $request->input('filter.state') . '%');
                });
            });
        });

        // General search filter
        $this->when($request->has('search'), function ($query) use ($request, $morphTo) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm, $morphTo) {
                $query->where('id', '=', $this->extractIdFromString($searchTerm))
                    ->orWhere('total_cost', '=', (float)$searchTerm * 100 ?: $searchTerm)
                    ->orWhere('created_at', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('status', function ($query) use ($searchTerm) {
                        $query->where('name_en', 'like', '%' . $searchTerm . '%')
                            ->orWhere('name_ar', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHasMorph($morphTo, '*', function ($query) use ($searchTerm) {
                        $query->where('name_en', 'like', '%' . $searchTerm . '%')
                            ->orWhere('name_ar', 'like', '%' . $searchTerm . '%')
                            ->orWhere('street_address_en', 'like', '%' . $searchTerm . '%')
                            ->orWhere('street_address_ar', 'like', '%' . $searchTerm . '%')
                            ->orWhereHas('state', function ($query) use ($searchTerm) {
                                $query->where('name_en', 'like', '%' . $searchTerm . '%')
                                    ->orWhere('name_ar', 'like', '%' . $searchTerm . '%')
                                    ->orWhereHas('city', function ($query) use ($searchTerm) {
                                        $query->where('name_en', 'like', '%' . $searchTerm . '%')
                                            ->orWhere('name_ar', 'like', '%' . $searchTerm . '%');
                                    });
                            });
                    });
            });
        });
    }

    /**
     * Extracts ID from a given string, removing the '#' character and converting to an integer.
     *
     * @param string $string
     * @return int|string
     */
    private function extractIdFromString($string)
    {
        // Remove the '#' character
        $idString = ltrim($string, '#');

        // Convert the string to an integer if possible
        $id = (int)$idString ?: $string;

        return $id;
    }
}
