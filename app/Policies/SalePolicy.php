<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use App\Helpers\ExceptionHelper;
use Illuminate\Auth\Access\Response;

class SalePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sale $sale): Response
    {
        return ($user->can('sale.show') && ($sale->salable == $user->employee->employable))
            ? Response::allow()
            : ExceptionHelper::throwModelNotFound($sale);
    }
}
