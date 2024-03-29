<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Team;
use App\Models\Match;
use App\Models\MatchInvitation as Invitation;

//Match Invitation for team
class MatchInvitation extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $myTeam;
    protected $team;
    protected $match;
    protected $invitation;

    public function __construct(Team $myTeam, Team $team, Match $match, Invitation $invitation)
    {
        $this->myTeam = $myTeam;
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
            'my_team_id' => $this->myTeam->id,
            'my_team_name' => $this->myTeam->name,
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
