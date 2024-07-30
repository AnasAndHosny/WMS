<?php

namespace App\Queries;

use Spatie\QueryBuilder\QueryBuilder;

class EmployeesListQuery extends QueryBuilder
{
    public function __construct($employees, $request)
    {
        parent::__construct($employees);

        $this
            ->select('employees.*')
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
                $query->where('full_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('phone_number', 'like', '%' . $searchTerm . '%')
                    ->orWhere('ssn', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('user', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('email', 'like', '%' . $searchTerm . '%');
                    });
            });
        });
    }
}
