<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Destruction;
use App\Helpers\ExceptionHelper;
use Illuminate\Auth\Access\Response;

class DestructionPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Destruction $destruction): Response
    {
        return ($user->can('destruction.show') && $destruction->destructionable == $user->employee->employable)
            ? Response::allow()
            : ExceptionHelper::throwModelNotFound($destruction);
    }
}
