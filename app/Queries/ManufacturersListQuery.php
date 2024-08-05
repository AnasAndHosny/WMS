<?php

namespace App\Queries;

use Spatie\QueryBuilder\QueryBuilder;

class ManufacturersListQuery extends QueryBuilder
{
    public function __construct($manufacturers, $request)
    {
        parent::__construct($manufacturers);

        $this
            ->select('manufacturers.*')
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
                $query->where('name_ar', 'like', '%' . $searchTerm . '%')
                    ->orWhere('name_en', 'like', '%' . $searchTerm . '%');
            });
        });
    }
}
