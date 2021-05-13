<?php

namespace App\Observers;

use App\Models\MatchJoining;
use App\Notifications\NewJoiningMatch;
use App\Notifications\RequestJoiningMatch;
use App\Notifications\UserLeaveMatch;
use App\Notifications\InviteJoinMatch;
use App\Notifications\SuggestMatchMember;

class MatchJoiningObserver
{
    /**
     * Handle the MatchJoining "created" event.
     *
     * @param  \App\Models\MatchJoining  $matchJoining
     * @return void
     */
    public function created(MatchJoining $matchJoining)
    {
        //TODO
        if($matchJoining->status === 'active'){
            $joins = $matchJoining->match->joinings;
            foreach ($joins as $join) {
                if($join->status === 'active' && $join->player_id !== $matchJoining->player_id)
                {
                    $join->user->notify(new NewJoiningMatch($matchJoining->match, $matchJoining->user));
                }
            }
        }else if($matchJoining->status === 'requested'){
            $matchJoining->team->admins->map->admin->notify(new RequestJoiningMatch($matchJoining->match, $matchJoining, $matchJoining->user));
            $matchJoining->team->captain->notify(new RequestJoiningMatch($matchJoining->match, $matchJoining, $matchJoining->user));
            
        }else if($matchJoining->status === 'invited'){
            $matchJoining->user->notify(new InviteJoinMatch($matchJoining->match, $matchJoining, $matchJoining->invitedBy));
        }else{
            $matchJoining->user->notify(new SuggestMatchMember($matchJoining->match, $matchJoining, $matchJoining->invitedBy));
        }
    }

    /**
     * Handle the MatchJoining "updated" event.
     *
     * @param  \App\Models\MatchJoining  $matchJoining
     * @return void
     */
    public function updated(MatchJoining $matchJoining)
    {
        //delete other invitation

        $joins = $matchJoining->match->joinings;
        foreach ($joins as $join) {
            if($join->status === 'active' && $join->player_id !== $matchJoining->player_id)
            {
                $join->user->notify(new NewJoiningMatch($matchJoining->match, $matchJoining->user));
            }
        }

        //delete all notifications
        $matchJoining->user->notifications()->where([
            ['type', 'App\\Notifications\\InviteJoinMatch'],
            ['data','LIKE' ,'%"request_id":'.$matchJoining->id.'%']
        ])->delete();

        $admins = $matchJoining->team->admins->map->admin;
        foreach ($admins as $admin) {
            $admin->notifications()->where([
                ['type', 'App\\Notifications\\RequestJoiningMatch'],
                ['data','LIKE', '%"request_id":'.$matchJoining->id.'%']
            ])->delete();
        }
        
        $matchJoining->team->captain->notifications()->where([
            ['type', 'App\\Notifications\\RequestJoiningMatch'],
            ['data','LIKE', '%"request_id":'.$matchJoining->id.'%']
        ])->delete();
    }

    /**
     * Handle the MatchJoining "deleted" event.
     *
     * @param  \App\Models\MatchJoining  $matchJoining
     * @return void
     */
    public function deleted(MatchJoining $matchJoining)
    {
        //notify user leave match
        $joins = $matchJoining->match->joinings;
        foreach ($joins as $join) {
            if($join->status === 'active' && $join->player_id !== $matchJoining->player_id)
            {
                $join->user->notify(new UserLeaveMatch($matchJoining->match, $matchJoining->user));
            }
        }
    }

    /**
     * Handle the MatchJoining "restored" event.
     *
     * @param  \App\Models\MatchJoining  $matchJoining
     * @return void
     */
    public function restored(MatchJoining $matchJoining)
    {
        //
    }

    /**
     * Handle the MatchJoining "force deleted" event.
     *
     * @param  \App\Models\MatchJoining  $matchJoining
     * @return void
     */
    public function forceDeleted(MatchJoining $matchJoining)
    {
        //
    }
}
