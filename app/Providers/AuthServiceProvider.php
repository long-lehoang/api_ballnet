<?php

namespace App\Providers;

use App\Models\Post;
use App\Policies\PostPolicy;
use App\Models\Team;
use App\Policies\TeamPolicy;
use App\Models\MemberTeam;
use App\Policies\FriendPolicy;
use App\Models\FriendRequest;
use App\Policies\MatchPolicy;
use App\Models\Match;
use App\Models\MatchInvitation;
use App\Models\MatchJoining;
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
        FriendRequest::class => FriendPolicy::class,
        Match::class => MatchPolicy::class,
        MatchInvitation::class => MatchPolicy::class,
        MatchJoining::class => MatchPolicy::class,
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
