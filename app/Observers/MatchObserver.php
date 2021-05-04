<?php

namespace App\Observers;

use App\Models\Match;
use App\Notifications\NewMatch;
use App\Notifications\MatchInvitation;

class MatchObserver
{
    /**
     * Handle the Match "created" event.
     *
     * @param  \App\Models\Match  $match
     * @return void
     */
    public function created(Match $match)
    {
        //New Match, notify to all member of Team
        $team = $match->team1;
        $team->members->map->member->map->notify(new NewMatch($team, $match));
    }

    /**
     * Handle the Match "updated" event.
     *
     * @param  \App\Models\Match  $match
     * @return void
     */
    public function updated(Match $match)
    {
        //
    }

    /**
     * Listen to the User updating event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updating(Match $match)
    {
        $team1 = $match->team1;

        if($match->isDirty('team2')){
            // team has cancel
            $new_team = $match->team2;
            $old_team = $match->getOriginal('team2');

            if($new_team === null && $old_team !==null){
                //case leave match
                //notify to members of team 1
                $team1->members->map->member->notify(new TeamLeaveMatch($team1, $old_team, $match));
            }else{
                //case join match
                //notify to members of team 1
                $team1->members->map->member->notify(new AcceptMatchInvitation($team1, $new_team, $match));
                
                //notify to member of team2 (NewMatch)
                $new_team->members->map->member->notify(new NewMatch($new_team, $match));
            }
        }
    }
    /**
     * Handle the Match "deleted" event.
     *
     * @param  \App\Models\Match  $match
     * @return void
     */
    public function deleted(Match $match)
    {
        //TODO: delete all NewMatch Notifications
    }

    /**
     * Handle the Match "restored" event.
     *
     * @param  \App\Models\Match  $match
     * @return void
     */
    public function restored(Match $match)
    {
        //
    }

    /**
     * Handle the Match "force deleted" event.
     *
     * @param  \App\Models\Match  $match
     * @return void
     */
    public function forceDeleted(Match $match)
    {
        //
    }
}
