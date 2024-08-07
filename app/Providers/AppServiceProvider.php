<?php

namespace App\Providers;

use App\Policies\OrderPolicy;
use App\Events\ProductExpired;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Events\ProductQuantityDecreased;
use App\Listeners\DeleteExpiredProducts;
use App\Listeners\SendProductExpiredNotification;
use App\Listeners\SendProductQuantityWarningNotification;

class AppServiceProvider extends ServiceProvider
{
    private $listen = [
        ProductExpired::class => [
            DeleteExpiredProducts::class,
            SendProductExpiredNotification::class
        ],
        ProductQuantityDecreased::class => [
            SendProductQuantityWarningNotification::class
        ],
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('updateBuy-order', [OrderPolicy::class, 'updateBuy']);

        Gate::define("view-mailweb", function ($user = null) {
            return true;
        });

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}
