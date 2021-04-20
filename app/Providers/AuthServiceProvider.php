<?php

namespace App\Providers;

use App\Models\Post;
use App\Policies\PostPolicy;
use App\Models\Team;
use App\Policies\TeamPolicy;
use App\Models\MemberTeam;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        Team::class => TeamPolicy::class,
        MemberTeam::class => TeamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        if (! $this->app->routesAreCached()) {
            Passport::routes();
        }
        //
    }
}
