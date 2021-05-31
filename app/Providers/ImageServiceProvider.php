<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Image;
use App\Services\ImageService;

class ImageServiceProvider extends ServiceProvider
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
        $this->app->bind(Image::class,ImageService::class);
    }
}