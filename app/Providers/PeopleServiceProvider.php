<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\People;
use App\Services\PeopleService;

class PeopleServiceProvider extends ServiceProvider
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
        $this->app->bind(People::class, PeopleService::class);
    }
}
