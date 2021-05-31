<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Match;
use App\Services\MatchService;

class MatchServiceProvider extends ServiceProvider
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
        $this->app->bind(Match::class,MatchService::class);
    }
}
