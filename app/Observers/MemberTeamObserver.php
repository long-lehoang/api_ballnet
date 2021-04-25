<?php

namespace App\Observers;

use App\Models\MemberTeam;
use App\Notifications\RequestJoinTeam;
use App\Notifications\IniviteJoinTeam;
use App\Notifications\NewMember;
use Log;

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
        Log::info(__CLASS__.' :: '.__FUNCTION__.' : '.'request_id='.$memberTeam->id);

        if($memberTeam->invited_by === null){
            $team = $memberTeam->team;
            $user = $memberTeam->member;
            $requestId = $memberTeam->id;
            
            $admins = $team->admins;
            foreach ($admins as $key => $admin) {
                $admin->admin->notify(new RequestJoinTeam($user, $team, $requestId));
            }
            $team->captain->notify(new RequestJoinTeam($user, $team, $requestId));
        }else{
            $team = $memberTeam->team;
            $user = $memberTeam->invitedBy;
            $requestId = $memberTeam->id;
            
            $member = $memberTeam->member;
            $member->notify(new IniviteJoinTeam($user, $team, $requestId));
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
        Log::info(__CLASS__.' :: '.__FUNCTION__.' : '.'request_id='.$memberTeam->id);

        $member = $memberTeam->member;
        $team = $memberTeam->team;
        $member->notify(new NewMember($team));

        if($memberTeam->invited_by === null){
            $admins = $memberTeam->team->admins;

            foreach ($admins as $key => $value) {
                $ntf = $value->admin->notifications()
                ->where('data','LIKE','%"request_id":'.$memberTeam->id.'%')
                ->get();
                $ntf->map->delete();
            }
            $ntf = $memberTeam->team->captain->notifications()
            ->where('data','LIKE','%"request_id":'.$memberTeam->id.'%')
            ->get();
            $ntf->map->delete();
        }else{
            $ntf = $memberTeam->member->notifications()
            ->where('data','LIKE','%"request_id":'.$memberTeam->id.'%')
            ->get();
            $ntf->map->delete();
        }
    }

    /**
     * Handle the MemberTeam "deleted" event.
     *
     * @param  \App\Models\MemberTeam  $memberTeam
     * @return void
     */
    public function deleted(MemberTeam $memberTeam)
    {
        Log::info(__CLASS__.' :: '.__FUNCTION__.' : '.'request_id='.$memberTeam->id);

        if($memberTeam->invited_by === null){
            $admins = $memberTeam->team->admins;

            foreach ($admins as $key => $value) {
                $ntf = $value->admin->notifications()
                ->where('data','LIKE','%"request_id":'.$memberTeam->id.'%')
                ->get();
                $ntf->map->delete();
            }
            $ntf = $memberTeam->team->captain->notifications()
            ->where('data','LIKE','%"request_id":'.$memberTeam->id.'%')
            ->get();
            $ntf->map->delete();
        }else{
            $ntf = $memberTeam->member->notifications()
            ->where('data','LIKE','%"request_id":'.$memberTeam->id.'%')
            ->get();
            $ntf->map->delete();
        }
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
