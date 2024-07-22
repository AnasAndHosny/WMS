<?php

namespace App\Policies;

use App\Models\User;
use App\Helpers\ExceptionHelper;
use App\Models\DistributionCenter;
use Illuminate\Auth\Access\Response;

class DistributionCenterPolicy
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
    public function view(User $user, DistributionCenter $distributionCenter): Response
    {
            return ($user->can('center.index') ||
                ($user->can('center.own.show') && $distributionCenter->warehouse == $user->employee->employable))
                ? Response::allow()
                : ExceptionHelper::throwModelNotFound($distributionCenter);
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
    public function update(User $user, DistributionCenter $distributionCenter): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DistributionCenter $distributionCenter): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DistributionCenter $distributionCenter): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DistributionCenter $distributionCenter): bool
    {
        //
    }
}
