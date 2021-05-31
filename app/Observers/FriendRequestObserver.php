<?php

namespace App\Observers;

use App\Models\FriendRequest;
use App\Notifications\FriendRequest as FRN;
use Log;

class FriendRequestObserver
{
    /**
     * Handle the FriendRequest "created" event.
     *
     * @param  \App\Models\FriendRequest  $friendRequest
     * @return void
     */
    public function created(FriendRequest $friendRequest)
    {
        Log::info(__CLASS__.' :: '.__FUNCTION__.' : '.'friendrequest_id='.$friendRequest->id);
        $user = $friendRequest->user;
        $from_id = $friendRequest->request;
        $user->notify(new FRN($from_id, $friendRequest));
    }

    /**
     * Handle the FriendRequest "updated" event.
     *
     * @param  \App\Models\FriendRequest  $friendRequest
     * @return void
     */
    public function updated(FriendRequest $friendRequest)
    {
        Log::info(__CLASS__.' :: '.__FUNCTION__.' : '.'friendrequest_id='.$friendRequest->id);
    }

    /**
     * Handle the FriendRequest "deleted" event.
     *
     * @param  \App\Models\FriendRequest  $friendRequest
     * @return void
     */
    public function deleted(FriendRequest $friendRequest)
    {
        $ntf = $friendRequest->user->notifications()
        ->where('data','LIKE','%"friend_request":'.$friendRequest->id.'%')
        ->get();
        $ntf->map->delete();
    }

    /**
     * Handle the FriendRequest "restored" event.
     *
     * @param  \App\Models\FriendRequest  $friendRequest
     * @return void
     */
    public function restored(FriendRequest $friendRequest)
    {
        //
    }

    /**
     * Handle the FriendRequest "force deleted" event.
     *
     * @param  \App\Models\FriendRequest  $friendRequest
     * @return void
     */
    public function forceDeleted(FriendRequest $friendRequest)
    {
        //
    }
}
