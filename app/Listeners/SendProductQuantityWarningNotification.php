<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\ProductQuantityDecreased;
use App\Events\ProductQuantityWarningNotifyUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProductQuantityWarningNotification;

class SendProductQuantityWarningNotification implements ShouldQueue
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
    public function handle(ProductQuantityDecreased $event): void
    {
        $users = User::permission('product.warning.notify')
            ->whereHas('employee', function ($query) use ($event) {
                $query->where('employable_type', $event->employableType)
                    ->where('employable_id', $event->employableId);
            })
            ->get();

        Notification::send($users, new ProductQuantityWarningNotification($event->product));

        foreach ($users as $user) {
            event(new ProductQuantityWarningNotifyUser($event->product, $user));
        }
    }
}
