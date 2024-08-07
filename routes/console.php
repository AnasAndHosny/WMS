<?php

use App\Events\ProductExpired;
use App\Models\StoredProduct;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Mchev\Banhammer\Banhammer;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    Banhammer::unbanExpired();
})->daily();

Schedule::command('otp:clean')->daily();

Schedule::call(function () {
    $expiredProducts = StoredProduct::query()
    ->where('valid_quantity', '!=', 0)
    ->where('expired_quantity', 0)
    ->whereDate('expiration_date', '<=', now()->endOfDay())
    ->get();

    foreach ($expiredProducts as $expiredProduct) {
        event(new ProductExpired($expiredProduct));
    }
})->daily();
