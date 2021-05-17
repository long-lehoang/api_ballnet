<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Booking;
use App\Services\BookingService;

class BookingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(Booking::class,BookingService::class);
    }
}
