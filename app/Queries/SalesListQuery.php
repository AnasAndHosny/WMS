<?php

namespace App\Queries;

use Spatie\QueryBuilder\QueryBuilder;

class SalesListQuery extends QueryBuilder
{
    public function __construct($sales, $request)
    {

        parent::__construct($sales);

        $this
            ->defaultSort('-id')
            ->allowedSorts([])
            ->allowedFilters([])
            // Dynamic filters based on request input
            ->applyDynamicFilters($request);
    }

    /**
     * Apply dynamic filters based on the request input.
     *
     * @param $request
     * @param $morphTo
     */
    private function applyDynamicFilters($request)
    {
        // General search filter
        $this->when($request->has('search'), function ($query) use ($request) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('id', '=', $this->extractIdFromString($searchTerm))
                    ->orWhere('total_price', '=', (float)$searchTerm * 100 ?: $searchTerm)
                    ->orWhere('buyer_name', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('user', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('email', 'like', '%' . $searchTerm . '%');
                        $query->orWhereHas('employee', function ($query) use ($searchTerm) {
                            $query->where('full_name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('phone_number', 'like', '%' . $searchTerm . '%')
                                ->orWhere('ssn', 'like', '%' . $searchTerm . '%');
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
