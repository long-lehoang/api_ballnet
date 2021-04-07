<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Observers\LikeObserver;
use App\Models\Like;
use App\Observers\FriendRequestObserver;
use App\Models\FriendRequest;
use App\Observers\FriendObserver;
use App\Models\Friend;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Like::observe(LikeObserver::class);
        FriendRequest::observe(FriendRequestObserver::class);
        Friend::observe(FriendObserver::class);
    }
}
