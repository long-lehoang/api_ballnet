<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Match;

class NewJoiningMatch extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $user;
    protected $match;

    public function __construct(Match $match, User $user)
    {
        $this->user = $user;
        $this->match = $match;
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
        ];
    }
}