<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use App\Helpers\ExceptionHelper;
use App\Models\Manufacturer;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class OrderPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): Response
    {
        $userEmployable = $user->employee->employable;

        $orderableFrom = $order->orderableFrom;

        $orderableBy = $order->orderableBy;

        return ($user->can('orders.show') && ($userEmployable == $orderableFrom || $userEmployable == $orderableBy))
            ? Response::allow()
            : ExceptionHelper::throwModelNotFound($order);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateManufacturer(User $user, Order $order)
    {
        Gate::allows('view', $order);

        return ((get_class($order->orderableFrom) == Manufacturer::class)
            && ($user->can('orders.buy.update') || ($user->can('orders.own.update') && $order->user_id == $user->id)))
            ? Response::allow()
            : Response::deny('Sorry, you are not authorized to update this order.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateBuy(User $user, Order $order)
    {
        Gate::allows('view', $order);

        return (($order->orderableBy == $user->employee->employable)
            && ($user->can('orders.buy.update') || ($user->can('orders.own.update') && $order->user_id == $user->id)))
            ? Response::allow()
            : Response::deny('Sorry, you are not authorized to update this order.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateSell(User $user, Order $order)
    {
        Gate::allows('view', $order);

        return (($order->orderableFrom == $user->employee->employable)
            && ($user->can('orders.sell.update') || ($user->can('orders.own.update') && $order->user_id == $user->id)))
            ? Response::allow()
            : Response::deny('Sorry, you are not authorized to update this order.');
    }
}
