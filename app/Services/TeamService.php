<?php

namespace App\Services;

use App\Contracts\Team;
use Illuminate\Support\Facades\Auth;

class TeamService implements Team{
    
    /**
     * getMyTeam
     *
     * @return void
     */
    public function getMyTeam()
    {
        $user = Auth::guard('api')->user();

        //teams attended
        $team = $user->teams->map(function($team){
            if($team->status === 'active'){
                return $team->team;
            }
        });

        //captain of team
        $captainTeam = $user->captainTeam;

        if(!is_null($captainTeam)){
            $team[] = $captainTeam;
        }

        return $team;
    }
}
