<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Match;
use App\Models\MatchJoining;

class InviteJoinMatch extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $user;
    protected $match;
    protected $join;

    public function __construct(Match $match, MatchJoining $join, User $user)
    {
        $this->user = $user;
        $this->match = $match;
        $this->join = $join;
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
            'match_id' => $this->match->id,
            'sport' => $this->match->sport,
            'type_sport' => $this->match->type,
            'location' => $this->match->location,
            'time_start' => explode(', ',$this->match->time)[0],
            'username' => $this->user->username,
            'name' => $this->user->name,
            'avatar' => $this->user->info->avatar,
            'request_id' => $this->join->id,
        ];
    }
}