<?php

namespace App\Listeners;

use App\Events\ProductExpiredNotifyUser;
use App\Models\User;
use App\Events\ProductExpired;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProductExpiredNotification;

class SendProductExpiredNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProductExpired $event): void
    {
        $storedProduct = $event->storedProduct;

        $users = User::permission('product.expired.notify')
            ->whereHas('employee', function ($query) use ($storedProduct) {
                $query->where('employable_type', $storedProduct->storable_type)
                    ->where('employable_id', $storedProduct->storable_id);
            })
            ->get();

        Notification::send($users, new ProductExpiredNotification($storedProduct));

        foreach ($users as $user) {
            event(new ProductExpiredNotifyUser($storedProduct, $user));
        }
    }
}
