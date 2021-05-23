<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Stadium;
use App\Services\StadiumService;

class StadiumServiceProvider extends ServiceProvider
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
        $this->app->bind(Stadium::class, StadiumService::class);
    }
}
