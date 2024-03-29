<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\FriendRequest as FR;

class FriendRequest extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $friendRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, FR $friendRequest)
    {
        $this->user = $user;
        $this->friendRequest = $friendRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'avatar' => $this->user->info->avatar,
            'username' => $this->user->username,
            'name' => $this->user->name,
            'friend_request' => $this->friendRequest->id,
            'notification_id' => $this->id,
        ];
    }
}
