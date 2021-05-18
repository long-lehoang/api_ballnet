<?php

namespace App\Observers;

use App\Models\MatchResult;
use App\Models\Match;
use Log;

class MatchResultObserver
{
    /**
     * Handle the MatchResult "created" event.
     *
     * @param  \App\Models\MatchResult  $matchResult
     * @return void
     */
    public function created(MatchResult $matchResult)
    {
        
        //delete notification
        $matchResult->reviewer->notifications()->where([
            ['type', 'App\\Notifications\\ReviewMatch'],
            ['data','LIKE','%"match_id":'.$matchResult->match_id.'%']
        ])->delete();

        //if rating 0, team is left match
        if($matchResult->rating > 0){
            //update result
            $results = MatchResult::where('match_id', $matchResult->match_id)->pluck('result')->toArray();

            $results = array_count_values($results);
            $result = array_keys($results, max($results))[0];

            $match = $matchResult->match;
            $match->result = $result;
            $match->save();
            
            //update num_match
            $number = MatchResult::where('opponent_team_id', $matchResult->opponent_team_id)->groupByRaw('opponent_team_id, match_id')->count();
            $rating = MatchResult::selectRaw('AVG(rating) as rating')->where('opponent_team_id', $matchResult->opponent_team_id)->first();

            $team = $matchResult->opponent;
            $team->num_match = $number;
            $team->rating = $rating->rating;
            $team->save();
        }
    }

    /**
     * Handle the MatchResult "updated" event.
     *
     * @param  \App\Models\MatchResult  $matchResult
     * @return void
     */
    public function updated(MatchResult $matchResult)
    {
        //
    }

    /**
     * Handle the MatchResult "deleted" event.
     *
     * @param  \App\Models\MatchResult  $matchResult
     * @return void
     */
    public function deleted(MatchResult $matchResult)
    {
        //
    }

    /**
     * Handle the MatchResult "restored" event.
     *
     * @param  \App\Models\MatchResult  $matchResult
     * @return void
     */
    public function restored(MatchResult $matchResult)
    {
        //
    }

    /**
     * Handle the MatchResult "force deleted" event.
     *
     * @param  \App\Models\MatchResult  $matchResult
     * @return void
     */
    public function forceDeleted(MatchResult $matchResult)
    {
        //
    }
}
