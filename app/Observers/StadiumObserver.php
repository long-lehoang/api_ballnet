<?php

namespace App\Observers;

use App\Models\Stadium;
use App\Models\User;
use App\Notifications\NewStadium;

class StadiumObserver
{
    /**
     * Handle the Stadium "created" event.
     *
     * @param  \App\Models\Stadium  $stadium
     * @return void
     */
    public function created(Stadium $stadium)
    {
        //notify for admin
        $admin = User::find(1);
        $admin->notify(new NewStadium($stadium));
    }

    /**
     * Handle the Stadium "updated" event.
     *
     * @param  \App\Models\Stadium  $stadium
     * @return void
     */
    public function updated(Stadium $stadium)
    {
        //
    }

    /**
     * Handle the Stadium "deleted" event.
     *
     * @param  \App\Models\Stadium  $stadium
     * @return void
     */
    public function deleted(Stadium $stadium)
    {
        //
    }

    /**
     * Handle the Stadium "restored" event.
     *
     * @param  \App\Models\Stadium  $stadium
     * @return void
     */
    public function restored(Stadium $stadium)
    {
        //
    }

    /**
     * Handle the Stadium "force deleted" event.
     *
     * @param  \App\Models\Stadium  $stadium
     * @return void
     */
    public function forceDeleted(Stadium $stadium)
    {
        //
    }
}
