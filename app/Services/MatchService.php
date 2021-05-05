<?php

namespace App\Services;

use App\Contracts\Match;
use App\Repository\MatchRepo;
use App\Repository\MatchInvitationRepo;

class MatchService implements Match{
    
    protected $matchRepo;
    protected $matchInviteRepo;

    function __construct(MatchRepo $matchRepo, MatchInvitationRepo $matchInviteRepo){
        $this->matchRepo = $matchRepo;
        $this->matchInviteRepo = $matchInviteRepo;
    }
    /**
     * acceptTeam
     *
     * @param  mixed $invitationId
     * @return void
     */
    public function acceptTeam($invitationId){
        $invitation = $this->matchInviteRepo->find($invitationId);
        //delete all invitation of match
        $this->matchInviteRepo->deleteByMatchId($invitation->match_id);

        //update match with new team 2
        $match = $this->matchRepo->find($invitation->match_id);
        $match->team_2 = $invitation->team_id;
        $match->save();
    }
}