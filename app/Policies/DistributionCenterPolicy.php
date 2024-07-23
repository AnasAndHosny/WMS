<?php

namespace App\Policies;

use App\Models\User;
use App\Helpers\ExceptionHelper;
use App\Models\DistributionCenter;
use Illuminate\Auth\Access\Response;

class DistributionCenterPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DistributionCenter $distributionCenter): Response
    {
            return ($user->can('center.index') ||
                ($user->can('center.own.show') && ($distributionCenter->warehouse == $user->employee->employable)))
                ? Response::allow()
                : ExceptionHelper::throwModelNotFound($distributionCenter);
    }
}
