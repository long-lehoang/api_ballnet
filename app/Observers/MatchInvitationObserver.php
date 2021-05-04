<?php

namespace App\Observers;

use App\Models\MatchInvitation;

class MatchInvitationObserver
{
    /**
     * Handle the MatchInvitation "created" event.
     *
     * @param  \App\Models\MatchInvitation  $matchInvitation
     * @return void
     */
    public function created(MatchInvitation $matchInvitation)
    {
        //TODO: notify all members of team
        //Notify to team which is invited.
    }

    /**
     * Handle the MatchInvitation "updated" event.
     *
     * @param  \App\Models\MatchInvitation  $matchInvitation
     * @return void
     */
    public function updated(MatchInvitation $matchInvitation)
    {
        //
    }

    /**
     * Handle the MatchInvitation "deleted" event.
     *
     * @param  \App\Models\MatchInvitation  $matchInvitation
     * @return void
     */
    public function deleted(MatchInvitation $matchInvitation)
    {
        //TODO: delete notification of matchInvitation
    }

    /**
     * Handle the MatchInvitation "restored" event.
     *
     * @param  \App\Models\MatchInvitation  $matchInvitation
     * @return void
     */
    public function restored(MatchInvitation $matchInvitation)
    {
        //
    }

    /**
     * Handle the MatchInvitation "force deleted" event.
     *
     * @param  \App\Models\MatchInvitation  $matchInvitation
     * @return void
     */
    public function forceDeleted(MatchInvitation $matchInvitation)
    {
        //
    }
}
