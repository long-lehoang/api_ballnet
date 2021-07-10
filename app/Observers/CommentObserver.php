<?php

namespace App\Observers;

use App\Models\Comment;
use App\Notifications\CommentPost;
use App\Events\CommentPost as CommentEvent;
use App\Events\UnCommentPost as UnCommentEvent;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        $user = $comment->user;
        $post = $comment->post;
        $author = $post->user;
        if($author != $user)
        $author->notify(new CommentPost($user, $post));
        broadcast(new CommentEvent($user, $comment))->toOthers();
    }

    /**
     * Handle the Comment "updated" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        //
    }

    /**
     * Handle the Comment "deleted" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        broadcast(new UnCommentEvent($comment))->toOthers();
    }

    /**
     * Handle the Comment "restored" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function restored(Comment $comment)
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function forceDeleted(Comment $comment)
    {
        //
    }
}
