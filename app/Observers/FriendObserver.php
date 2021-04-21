<?php

namespace App\Observers;

use App\Models\Friend;
use App\Notifications\NewFriend;

class FriendObserver
{
    /**
     * Handle the Friend "created" event.
     *
     * @param  \App\Models\Friend  $friend
     * @return void
     */
    public function created(Friend $friend)
    {
        $user = $friend->user;
        $fr = $friend->friend;
        $user->notify(new NewFriend($fr));
    }

    /**
     * Handle the Friend "updated" event.
     *
     * @param  \App\Models\Friend  $friend
     * @return void
     */
    public function updated(Friend $friend)
    {
        //
    }

    /**
     * Handle the Friend "deleted" event.
     *
     * @param  \App\Models\Friend  $friend
     * @return void
     */
    public function deleted(Friend $friend)
    {
        //
    }

    /**
     * Handle the Friend "restored" event.
     *
     * @param  \App\Models\Friend  $friend
     * @return void
     */
    public function restored(Friend $friend)
    {
        //
    }

    /**
     * Handle the Friend "force deleted" event.
     *
     * @param  \App\Models\Friend  $friend
     * @return void
     */
    public function forceDeleted(Friend $friend)
    {
        //
    }
}
