<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Team;

class InviteJoinTeam extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $user;
    protected $team;
    protected $requestId;

    public function __construct(User $user, Team $team, $requestId)
    {
        $this->user = $user;
        $this->team = $team;
        $this->requestId = $requestId;
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
            'user_name' => $this->user->name,
            'team_avatar' => $this->team->avatar,
            'team_name' => $this->team->name,
            'team_id' => $this->team->id,
            'request_id' => $this->requestId,
        ];
    }
}
