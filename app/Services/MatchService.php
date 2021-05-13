<?php

namespace App\Services;

use App\Contracts\Match;
use App\Repository\MatchRepo;
use App\Repository\MatchJoiningRepo;
use App\Repository\MatchInvitationRepo;
use App\Repository\TeamRepo;
use Auth;

class MatchService implements Match{
    
    protected $matchRepo;
    protected $matchInviteRepo;
    protected $matchJoining;
    protected $teamRepo;

    function __construct(MatchRepo $matchRepo, MatchInvitationRepo $matchInviteRepo, MatchJoiningRepo $matchJoining, TeamRepo $teamRepo){
        $this->matchRepo = $matchRepo;
        $this->matchInviteRepo = $matchInviteRepo;
        $this->matchJoining = $matchJoining;
        $this->teamRepo = $teamRepo;
    }
    /**
     * acceptTeam
     *
     * @param  mixed $invitationId
     * @return void
     */
    public function acceptTeam($invitationId){
        $invitation = $this->matchInviteRepo->find($invitationId);
        //case invited by member
        if($invitation->status == 'suggested'){
            $invitation->status = 'requested';
            $invitation->save();
            return ;
        }
        //other case

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
    public function inviteTeam($team_id, $matchId){
        $match = $this->matchRepo->find($matchId);
        if($match->team1->id_captain === Auth::id()){
            $this->matchInviteRepo->create(
                [
                    "match_id" => $matchId,
                    "team_id" => $team_id,
                ],
                [
                    "status" => "invited",
                    "invited_by" => Auth::id()
                ]
            );
        }else{
            $this->matchInviteRepo->create(
                [
                    "match_id" => $matchId,
                    "team_id" => $team_id,
                ],
                [
                    "status" => "suggested",
                    "invited_by" => Auth::id()
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
            //check is member
            if($this->teamRepo->isMember(Auth::id(), $teamId)['success']){
                return $this->matchJoining->create(
                    [
                        "match_id" => $matchId,
                        "team_id" => $teamId,
                        "player_id" => Auth::id(),
                    ],
                    [
                        "status" => "active"
                    ]
                );
            }else{
                return $this->matchJoining->create(
                    [
                        "match_id" => $matchId,
                        "team_id" => $teamId,
                        "player_id" => Auth::id(),
                    ],
                    [
                        "status" => "requested"
                    ]
                );
            }
        }else{
            //check admin
            if($this->teamRepo->isAdmin(Auth::id(),$teamId)['success']){
                return $this->matchJoining->create(
                    [
                        "team_id" => $teamId,
                        "match_id" => $matchId,
                        "player_id" => $playerId,
                    ],
                    [
                        "invited_by" => Auth::id(),
                        "status" => "invited"
                    ]
                );
            }else{
                return $this->matchJoining->create(
                    [
                        "team_id" => $teamId,
                        "match_id" => $matchId,
                        "player_id" => $playerId,
                    ],
                    [
                        "invited_by" => Auth::id(),
                        "status" => "suggested"
                    ]
                );
            }
        }
    }
    
    /**
     * getFriendNotInMatch
     *
     * @param  mixed $matchId
     * @return void
     */
    public function getFriendNotInMatch($matchId)
    {
        $user = Auth::guard('api')->user();

        $match = $this->matchRepo->find($matchId);
        $joins = $match->joinings->pluck('player_id')->toArray();

        $friends = $user->friends->map->friend;
        $friendNotIn = $friends->whereNotIn('id',$joins);
        
        $list = $friendNotIn->map(function($item){
            $obj = new \stdClass;
            $obj = clone $item;
            $obj->avatar = $item->info->avatar;
            return $obj;
        });
        
        return array_values($list->toArray());
    }
    
    /**
     * memberOfTeam
     *
     * @param  mixed $id
     * @param  mixed $team_id
     * @return void
     */
    public function memberOfTeam($id, $team_id)
    {
        $match = $this->matchRepo->find($id);
        $joins = $match->joinings()->where('team_id', $team_id)->get();

        $members = $joins->map(function($join){
            $obj = new \stdClass;
            $obj = clone $join->user;
            $obj->avatar = $join->user->info->avatar;
            $obj->join_id = $join->id;
            return $obj;
        });
        return $members;
    }
    
    /**
     * getTeamRequestOfMatch
     *
     * @param  mixed $id
     * @return void
     */
    public function getTeamRequestOfMatch($id)
    {
        $match = $this->matchRepo->find($id);
        $invitations = $match->invitations->map(function($invitation){
            if($invitation->status !== 'suggested'){
                $obj = new \stdClass;
                $obj = clone $invitation->team;
                $obj->request_id = $invitation->id;
                $obj->status = $invitation->status;
                return $obj;
            }
        });

        return array_values(array_filter($invitations->toArray()));
    }
}