<?php

namespace App\Observers;

use App\Models\Match;
use App\Notifications\NewMatch;
use App\Notifications\MatchInvitation;
use App\Notifications\AcceptMatchInvitation;
use App\Notifications\TeamLeaveMatch;
use App\Notifications\DeleteMatch;
use App\Notifications\UpcomingMatch;
use App\Notifications\ReviewMatch;
use App\Notifications\ReviewStadium;
use App\Notifications\UpdateMatch;
use App\Models\Team;
use Log;

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
        //notify for all member of team about new team join to match
        if($match->isDirty('team_2')){
            // team has cancel
            $new_team = $match->team2;
            $old_team = $match->getOriginal('team_2');
            Log::debug($old_team);
            Log::debug($new_team);
            if($new_team === null && $old_team !==null){
                //case leave match
                //notify to members of team 1
                $team2 = Team::find($old_team);
                foreach ($match->joinings as $join) {
                    if($join->team_id === $match->team_1 && $join->status === 'active'){
                        $join->user->notify(new TeamLeaveMatch($team2, $match));
                    }
                }
                
                //delete all joining with team2 
                $match->joinings()->where('team_id', $old_team)->delete();
            }else{
                //case join match
                //notify to members of team 1
                foreach ($match->joinings as $join) {
                    if($join->team_id === $match->team_1 && $join->status === 'active'){
                        $join->user->notify(new AcceptMatchInvitation($new_team, $match));
                    }
                }
                
                //notify to member of team2 (NewMatch)
                $new_team->members->map->member->map->notify(new NewMatch($new_team, $match));
                
                Log::debug($match->id);
                // delete notification invitation
                $match->team1->captain->notifications()->where([
                    ['type', 'App\\Notifications\\TeamRequestMatch'],
                    ['data','LIKE','%"match_id":'.$match->id.'%']
                ])->delete();

                $users = $match->invitations->map->team->map->captain;
                foreach ($users as $user) {
                    $user->notifications()->where([
                        ['type', 'App\\Notifications\\MatchInvitation'],
                        ['data','LIKE','%"match_id":'.$match->id.'%']
                    ])->delete();
                }
                
                $match->invitations()->delete();
            }
        }

        if($match->isDirty('type')||$match->isDirty('location')||$match->isDirty('time')){
            foreach ($match->joinings as $join) {
                if($join->status === 'active'){
                    $join->user->notify(new UpdateMatch($match,$match->team1));
                }
            }
        }

        //notify before match, after match
        if($match->isDirty('status')){
            //notify before match
            if($match->status === 'upcoming'){
                foreach ($match->joinings as $join) {
                    $join->user->notify(new UpcomingMatch($match));
                }
            }

            //notify before match
            if($match->status === 'old'){
                $delay = now()->addMinutes(30);

                foreach ($match->joinings as $join) {
                    $join->user->notify((new ReviewMatch($match))->delay($delay));
                }

                if(!is_null($match->booking))
                $match->createBy->notify((new ReviewStadium($match->booking, $match->booking->stadium))->delay($delay));
            }
        }
    }

    /**
     * Listen to the User updating event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updating(Match $match)
    {

        
    }
    /**
     * Handle the Match "deleted" event.
     *
     * @param  \App\Models\Match  $match
     * @return void
     */
    public function deleted(Match $match)
    {
        //notify all member Notifications
        $users = $match->joinings->map->user;
        $users->map->notify(new DeleteMatch($match->team1, $match));
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
