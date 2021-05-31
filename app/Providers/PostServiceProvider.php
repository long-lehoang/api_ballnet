<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Post;
use App\Services\PostService;

class PostServiceProvider extends ServiceProvider
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
        $this->app->bind(Post::class, PostService::class);
    }
}