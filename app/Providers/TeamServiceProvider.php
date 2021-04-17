<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Team;
use App\Services\TeamService;

class TeamServiceProvider extends ServiceProvider
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
        $this->app->bind(Team::class, TeamService::class);
        
    }
}
