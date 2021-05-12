<?php

namespace App\Observers;

use App\Models\MatchInvitation;
use App\Notifications\MatchInvitation as InviteNotice;
use App\Notifications\SuggestMatch;
use App\Notifications\TeamRequestMatch;
use Log;

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
        Log::info($matchInvitation);
        $team1 = $matchInvitation->match->team1;
        $team2 = $matchInvitation->team;
        $match = $matchInvitation->match;
        if($matchInvitation->status == 'requested'){
            $team1->captain->notify(new TeamRequestMatch($team1, $team2, $match, $matchInvitation));
        }else{
            if($matchInvitation->invited_by === $matchInvitation->match->team1->id_captain){
                $team2->captain->notify(new SuggestMatch($team2, $matchInvitation->invitedBy, $match, $matchInvitation));
            }else{
                $team2->captain->notify(new InviteNotice($team2, $team1, $match, $matchInvitation));
            }
        }
    }

    /**
     * Handle the MatchInvitation "updated" event.
     *
     * @param  \App\Models\MatchInvitation  $matchInvitation
     * @return void
     */
    public function updated(MatchInvitation $matchInvitation)
    {
        //case team accept suggested join match
        //notify for captain team 1
        $team1 = $matchInvitation->match->team1;
        $team2 = $matchInvitation->team;
        $match = $matchInvitation->match;

        $match->team1->captain->notify(new TeamRequestMatch($team1, $team2, $match, $matchInvitation));
    }

    /**
     * Handle the MatchInvitation "deleted" event.
     *
     * @param  \App\Models\MatchInvitation  $matchInvitation
     * @return void
     */
    public function deleted(MatchInvitation $matchInvitation)
    {
        //delete notification of matchInvitation
        if($matchInvitation->status == 'requested'){
            $matchInvitation->match->team1->captain->notifications()->where([
                ['type', 'App\\Notifications\\MatchInvitation'],
                ['data','LIKE','%"id_match":'.$matchInvitation->match_id.'%'],
                ['data','LIKE','%"id_team":'.$matchInvitation->team_id.'%'],
            ])->delete();
        }else{
            $matchInvitation->team->captain->notifications()->where([
                ['type', 'App\\Notifications\\MatchInvitation'],
                ['data','LIKE','%"id_match":'.$matchInvitation->match_id.'%'],
                ['data','LIKE','%"id_team":'.$matchInvitation->team_id.'%'],
            ])->delete();
        }
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
