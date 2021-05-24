<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Observers\LikeObserver;
use App\Models\Like;
use App\Observers\CommentObserver;
use App\Models\Comment;
use App\Observers\ShareObserver;
use App\Models\Share;
use App\Observers\FriendRequestObserver;
use App\Models\FriendRequest;
use App\Observers\FriendObserver;
use App\Models\Friend;
use App\Observers\TeamObserver;
use App\Models\Team;
use App\Observers\MemberTeamObserver;
use App\Models\MemberTeam;
use App\Models\User;
use App\Observers\UserObserver;
use App\Models\Match;
use App\Observers\MatchObserver;
use App\Models\MatchInvitation;
use App\Observers\MatchInvitationObserver;
use App\Models\MatchJoining;
use App\Observers\MatchJoiningObserver;
use App\Models\MatchResult;
use App\Observers\MatchResultObserver;
use App\Models\Booking;
use App\Observers\BookingObserver;
use App\Models\AttendanceMatchJoining;
use App\Observers\AttendanceMatchJoiningObserver;
use App\Models\Stadium;
use App\Observers\StadiumObserver;

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
        Comment::observe(CommentObserver::class);
        Share::observe(ShareObserver::class);
        FriendRequest::observe(FriendRequestObserver::class);
        Friend::observe(FriendObserver::class);
        Team::observe(TeamObserver::class);
        MemberTeam::observe(MemberTeamObserver::class);
        User::observe(UserObserver::class);
        Match::observe(MatchObserver::class);
        MatchInvitation::observe(MatchInvitationObserver::class);
        MatchJoining::observe(MatchJoiningObserver::class);
        MatchResult::observe(MatchResultObserver::class);
        Booking::observe(BookingObserver::class);
        AttendanceMatchJoining::observe(AttendanceMatchJoiningObserver::class);
        Stadium::observe(StadiumObserver::class);
    }
}
