<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Team;
use App\Models\Match;

//TODO
class TeamLeaveMatch extends Notification implements ShouldQueue
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

    public function __construct(Team $myTeam, Team $team, Match $match)
    {
        $this->myTeam = $myTeam;
        $this->team = $team;
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
            'id_my_team' => $this->team->id,
            'name_my_team' => $this->team->name,
            'id_team' => $this->team->id,
            'name_team' => $this->team->name,
            'id_match' => $this->match->id,
            'sport' => $this->match->sport,
            'type' => $this->match->type,
            'location' => $this->match->location,
            'time_start' => explode(', ',$this->match->time)[0]
        ];
    }
}
