<?php

namespace App\Listeners;

use App\Events\ProductExpired;
use App\Models\EmployableProduct;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteExpiredProducts implements ShouldQueue
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

        $storedProduct->increment('expired_quantity', $storedProduct->valid_quantity);

        EmployableProduct::query()
                        ->where('employable_type', $storedProduct->storable_type)
                        ->where('employable_id', $storedProduct->storable_id)
                        ->where('product_id', $storedProduct->product_id)
                        ->first()
                        ->decrement('total_quantity', $storedProduct->valid_quantity);
    }
}
