<?php

namespace App\Services;

use App\Contracts\Match;
use App\Repository\MatchRepo;
use App\Repository\MatchInvitationRepo;
use Auth;

class MatchService implements Match{
    
    protected $matchRepo;
    protected $matchInviteRepo;
    protected $matchJoining;

    function __construct(MatchRepo $matchRepo, MatchInvitationRepo $matchInviteRepo, MatchJoining $matchJoining){
        $this->matchRepo = $matchRepo;
        $this->matchInviteRepo = $matchInviteRepo;
        $this->matchJoining = $matchJoining;
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
    
    /**
     * cancelTeam
     *
     * @param  mixed $invitationId
     * @return void
     */
    public function cancelTeam($invitationId){
        $invitation = $this->matchInviteRepo->find($invitationId);
        $invitation->delete();
    }
    
    /**
     * inviteTeam
     *
     * @param  mixed $teams
     * @param  mixed $matchId
     * @return void
     */
    public function inviteTeam($teams, $matchId){
        foreach ($teams as $key => $team) {
            $this->matchInviteRepo->create(
                [
                    "match_id" => $matchId,
                    "team_id" => $team,
                ],
                [
                    "status" => "invited",
                ]
            );
        }
    }
    
        
    /**
     * userJoin
     *
     * @param  mixed $matchId
     * @param  mixed $teamId
     * @param  mixed $playerId
     * @return void
     */
    public function userJoin($matchId, $teamId, $playerId)
    {
        if(is_null($playerId)){
            $this->matchJoining->create(
                [
                    "match_id" => $matchId,
                ],
                [
                    "team_id" => $team,
                    "player_id" => Auth::id(),
                ]
            );
        }else{
            $this->matchJoining->create(
                [
                    "team_id" => $team,
                ],
                [
                    "match_id" => $matchId,
                    "player_id" => $playerId,
                    "invited_by" => Auth::id()
                ]
            );
        }
    }
}