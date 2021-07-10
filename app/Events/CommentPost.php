<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Comment;

class CommentPost implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $comment;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Comment $comment)
    {
        $this->user = $user;
        $this->comment = $comment;
    }


    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'comment';
    }
    /**
    * Get the data to broadcast.
    *
    * @return array
    */
    public function broadcastWith()
    {
        return [
            'cmt_id' => $this->comment->id,
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'username' => $this->user->username,
            'avatar' => $this->user->info->avatar,
            'comment' => $this->comment->comment,
            'time' => $this->comment->created_at,
            'count' => count($this->comment->post->comments)
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [new PrivateChannel('App.Models.Post.'.$this->comment->post_id)];
    }
}
