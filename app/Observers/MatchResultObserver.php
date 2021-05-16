<?php

namespace App\Observers;

use App\Models\MatchResult;

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
        //TODO
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
