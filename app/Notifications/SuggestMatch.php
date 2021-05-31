<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Team;
use App\Models\Match;
use App\Models\MatchInvitation;
use App\Models\User;

//Suggest match for team
class SuggestMatch extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $user;
    protected $team;
    protected $match;
    protected $invitation;

    public function __construct(Team $team, User $user, Match $match, MatchInvitation $invitation)
    {
        $this->user = $user;
        $this->team = $team;
        $this->invitation = $invitation;
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
            'username' => $this->user->username,
            'name' => $this->user->name,
            'avatar' => $this->user->info->avatar,
            'team_id' => $this->team->id,
            'team_name' => $this->team->name,
            'team_avatar' => $this->team->avatar,
            'match_id' => $this->match->id,
            'sport' => $this->match->sport,
            'type_sport' => $this->match->type,
            'location' => $this->match->location,
            'time_start' => explode(', ',$this->match->time)[0],
            'request_id' => $this->invitation->id,
        ];
    }
}
