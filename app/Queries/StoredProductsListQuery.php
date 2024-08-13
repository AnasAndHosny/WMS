<?php

namespace App\Queries;

use App\Filters\StoredProductStatusFilter;
use Illuminate\Support\Facades\App;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class StoredProductsListQuery extends QueryBuilder
{
    public function __construct($storedProducts, $request, $owner = true)
    {
        $allowedFilters = [
            AllowedFilter::scope('expire_before'),
            AllowedFilter::scope('expire_after'),
        ];

        if ($owner) {
            $allowedFilters[] = AllowedFilter::custom('status', new StoredProductStatusFilter());
            $allowedFilters[] = AllowedFilter::scope('quantity_less_than');
            $allowedFilters[] = AllowedFilter::scope('quantity_more_than');
        }

        parent::__construct($storedProducts);

        $this
            ->select('stored_products.*')
            ->join('products', 'products.id', '=', 'product_id')
            ->join('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
            ->join('sub_categories', 'sub_categories.id', '=', 'products.subcategory_id')
            ->defaultSort('-created_at')
            ->allowedSorts(
                AllowedSort::field('name', 'products.name_' . App::getlocale()),
                AllowedSort::field('manufacturer', 'manufacturers.name_' . App::getlocale()),
                AllowedSort::field('price', 'products.price'),
                AllowedSort::field('sub_category', 'sub_categories.name_' . App::getlocale()),
                AllowedSort::field('expiration_date', 'stored_products.expiration_date'),
                AllowedSort::field('valid_quantity', 'stored_products.valid_quantity'),
            )
            ->allowedFilters($allowedFilters)
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
        // Filter by product name
        $this->when($request->has('filter.name'), function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('products.name_en', 'like', '%' . $request->input('filter.name') . '%')
                    ->orWhere('products.name_ar', 'like', '%' . $request->input('filter.name') . '%');
            });
        });

        // Filter by manufacturer name
        $this->when($request->has('filter.manufacturer'), function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('manufacturers.name_en', 'like', '%' . $request->input('filter.manufacturer') . '%')
                    ->orWhere('manufacturers.name_ar', 'like', '%' . $request->input('filter.manufacturer') . '%');
            });
        });

        // Filter by sub category name
        $this->when($request->has('filter.sub_category'), function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('sub_categories.name_en', 'like', '%' . $request->input('filter.sub_category') . '%')
                    ->orWhere('sub_categories.name_ar', 'like', '%' . $request->input('filter.sub_category') . '%');
            });
        });

        // Filter by price
        $this->when($request->has('filter.cheaper_than'), function ($query) use ($request) {
            $query->where('products.price', '<=', $request->input('filter.cheaper_than') * 100);
        });

        $this->when($request->has('filter.more_expensive_than'), function ($query) use ($request) {
            $query->where('products.price', '>=', $request->input('filter.more_expensive_than') * 100);
        });

        // Filter by barcode
        $this->when($request->has('filter.barcode'), function ($query) use ($request) {
            $query->where('products.barcode', 'like', '%' . $request->input('filter.barcode') . '%');
        });

        // General search filter
        $this->when($request->has('search'), function ($query) use ($request) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('products.name_en', 'like', '%' . $searchTerm . '%')
                    ->orWhere('products.name_ar', 'like', '%' . $searchTerm . '%')
                    ->orWhere('manufacturers.name_en', 'like', '%' . $searchTerm . '%')
                    ->orWhere('manufacturers.name_ar', 'like', '%' . $searchTerm . '%')
                    ->orWhere('sub_categories.name_en', 'like', '%' . $searchTerm . '%')
                    ->orWhere('sub_categories.name_ar', 'like', '%' . $searchTerm . '%')
                    ->orWhere('products.barcode', 'like', '%' . $searchTerm . '%');
            });
        });
    }
}
