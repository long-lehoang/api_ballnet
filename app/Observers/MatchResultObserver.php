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
        ]);

        //if rating 0, team is left match
        if($matchResult->rating > 0){
            //update result
            $results = MatchResult::where('match_id', $matchResult->id)->pluck('result')->toArray();
            $results = array_count_values($results);
            $result = array_keys($results, max($results));
            Log::debug($result);
            $match = $matchResult->match;
            $match->result = $result;
            $match->save();
            
            //update number team 1
            $team1 = $match->team1;
            $team1->number++;
            $team1->save();

            //update number team 2
            $team2 = $match->team2;
            $team2->number++;
            $team2->save();
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
