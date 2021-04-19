<?php

namespace App\Observers;

use App\Models\MemberTeam;
use App\Notifications\RequestJoinTeam;

class MemberTeamObserver
{
    /**
     * Handle the MemberTeam "created" event.
     *
     * @param  \App\Models\MemberTeam  $memberTeam
     * @return void
     */
    public function created(MemberTeam $memberTeam)
    {
        if($memberTeam->invited_by === null){
            $team = $memberTeam->team;
            $user = $memberTeam->member;
            $requestId = $memberTeam->id;
            
            $admins = $team->admins;
            foreach ($admins as $key => $admin) {
                $admin->admin->notify(new RequestJoinTeam($user, $team, $requestId));
            }
            $team->captain->notify(new RequestJoinTeam($user, $team, $requestId));
        }
    }

    /**
     * Handle the MemberTeam "updated" event.
     *
     * @param  \App\Models\MemberTeam  $memberTeam
     * @return void
     */
    public function updated(MemberTeam $memberTeam)
    {
        //
    }

    /**
     * Handle the MemberTeam "deleted" event.
     *
     * @param  \App\Models\MemberTeam  $memberTeam
     * @return void
     */
    public function deleted(MemberTeam $memberTeam)
    {
        //
    }

    /**
     * Handle the MemberTeam "restored" event.
     *
     * @param  \App\Models\MemberTeam  $memberTeam
     * @return void
     */
    public function restored(MemberTeam $memberTeam)
    {
        //
    }

    /**
     * Handle the MemberTeam "force deleted" event.
     *
     * @param  \App\Models\MemberTeam  $memberTeam
     * @return void
     */
    public function forceDeleted(MemberTeam $memberTeam)
    {
        //
    }
}
