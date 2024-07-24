<?php

namespace App\Policies;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\StoredProduct;
use App\Helpers\ExceptionHelper;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class StoredProductPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StoredProduct $storedProduct)
    {
        $userEmployable = $user->employee->employable;
        $productStorable = $storedProduct->storable;

        if ($user->can('product.show') && ($productStorable == $userEmployable))
            return Response::allow();

        if (
            $user->can('warehouses.product.index')
            && (get_class($productStorable) == Warehouse::class)
            && ($storedProduct->active == true)
            && (Carbon::createFromDate($storedProduct->expiration_date) > Carbon::today()->endOfDay())
        )
            return Response::allow();

        if (
            $user->can('warehouse.product.index')
            && ($productStorable == $userEmployable->warehouse)
            && ($storedProduct->active == true)
            && (Carbon::createFromDate($storedProduct->expiration_date) > Carbon::today()->endOfDay())
        )
            return Response::allow();

        ExceptionHelper::throwModelNotFound($storedProduct);
    }

    /**
     * Determine whether the user can destruct the product.
     */
    public function destruct(User $user, StoredProduct $storedProduct): Response
    {
        Gate::allows('view', $storedProduct);
        return ($user->can('destruction.store') && ($storedProduct->storable == $user->employee->employable))
            ? Response::allow()
            : Response::deny();
    }
}
