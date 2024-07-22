<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use App\Helpers\ExceptionHelper;
use Illuminate\Auth\Access\Response;

class SalePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sale $sale): Response
    {
        return ($user->can('sale.show') && ($sale->salable == $user->employee->employable))
            ? Response::allow()
            : ExceptionHelper::throwModelNotFound($sale);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sale $sale): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sale $sale): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Sale $sale): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Sale $sale): bool
    {
        //
    }
}
