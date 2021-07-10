<?php

namespace App\Observers;

use App\Models\Like;
use App\Notifications\LikePost;
use App\Events\LikePost as LikeEvent;

class LikeObserver
{
    /**
     * Handle the Like "created" event.
     *
     * @param  \App\Models\Like  $like
     * @return void
     */
    public function created(Like $like)
    {
        $user = $like->user;
        $post = $like->post;
        broadcast(new LikeEvent($post))->toOthers();

        $author = $post->user;
        if($author != $user)
        $author->notify(new LikePost($user, $post));

    }

    /**
     * Handle the Like "updated" event.
     *
     * @param  \App\Models\Like  $like
     * @return void
     */
    public function updated(Like $like)
    {
        //
        broadcast(new LikeEvent($like->post))->toOthers();

    }

    /**
     * Handle the Like "deleted" event.
     *
     * @param  \App\Models\Like  $like
     * @return void
     */
    public function deleted(Like $like)
    {
        broadcast(new LikeEvent($like->post))->toOthers();
    }

    /**
     * Handle the Like "restored" event.
     *
     * @param  \App\Models\Like  $like
     * @return void
     */
    public function restored(Like $like)
    {
        //
    }

    /**
     * Handle the Like "force deleted" event.
     *
     * @param  \App\Models\Like  $like
     * @return void
     */
    public function forceDeleted(Like $like)
    {
        //
    }
}
