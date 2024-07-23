<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Shipment;
use App\Helpers\ExceptionHelper;
use Illuminate\Auth\Access\Response;

class ShipmentPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Shipment $shipment): Response
    {
        return ($user->can('shipment.show') && ($shipment->order->orderableFrom == $user->employee->employable))
            ? Response::allow()
            : ExceptionHelper::throwModelNotFound($shipment);
    }
}
