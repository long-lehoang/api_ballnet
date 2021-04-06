<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Friend;
use App\Services\FriendService;

class FriendServiceProvider extends ServiceProvider
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
        $this->app->bind(Friend::class, FriendService::class);
    }
}
