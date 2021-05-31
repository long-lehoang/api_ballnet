<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Suggestion;
use App\Services\SuggestionService;

class SuggestionServiceProvider extends ServiceProvider
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
        $this->app->bind(Suggestion::class, SuggestionService::class);
        
    }
}
