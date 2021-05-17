<?php

namespace App\Services;

use App\Contracts\Match;
use App\Repository\MatchRepo;
use App\Repository\MatchJoiningRepo;
use App\Repository\MatchInvitationRepo;
use App\Repository\MatchResultRepo;
use App\Repository\MatchAttendantRepo;
use App\Repository\TeamRepo;
use Auth;
use Carbon\Carbon;

class MatchService implements Match{
    
    protected $matchRepo;
    protected $matchInviteRepo;
    protected $matchJoining;
    protected $teamRepo;
    protected $matchResultRepo;
    protected $matchAttendantRepo;

    function __construct(MatchRepo $matchRepo,MatchAttendantRepo $matchAttendantRepo, MatchResultRepo $matchResultRepo, MatchInvitationRepo $matchInviteRepo, MatchJoiningRepo $matchJoining, TeamRepo $teamRepo){
        $this->matchRepo = $matchRepo;
        $this->matchInviteRepo = $matchInviteRepo;
        $this->matchJoining = $matchJoining;
        $this->teamRepo = $teamRepo;
        $this->matchResultRepo = $matchResultRepo;
        $this->matchAttendantRepo = $matchAttendantRepo;
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
                        "player_id" => Auth::id(),
                    ],
                    [
                        "team_id" => $teamId,
                        "status" => "active"
                    ]
                );
            }else{
                return $this->matchJoining->create(
                    [
                        "match_id" => $matchId,
                        "player_id" => Auth::id(),
                    ],
                    [
                        "team_id" => $teamId,
                        "status" => "requested"
                    ]
                );
            }
        }else{
            //check admin
            if($this->teamRepo->isAdmin(Auth::id(),$teamId)['success']){
                return $this->matchJoining->create(
                    [
                        "match_id" => $matchId,
                        "player_id" => $playerId,
                    ],
                    [
                        "team_id" => $teamId,
                        "invited_by" => Auth::id(),
                        "status" => "invited"
                    ]
                );
            }else{
                return $this->matchJoining->create(
                    [
                        "match_id" => $matchId,
                        "player_id" => $playerId,
                    ],
                    [
                        "team_id" => $teamId,
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
            if($invitation->status === 'requested'){
                $obj = new \stdClass;
                $obj = clone $invitation->team;
                $obj->request_id = $invitation->id;
                $obj->status = $invitation->status;
                return $obj;
            }
        });

        return array_values(array_filter($invitations->toArray()));
    }
    
    /**
     * requestOfTeam
     *
     * @param  mixed $matchId
     * @param  mixed $teamId
     * @return void
     */
    public function requestOfTeam($matchId, $teamId)
    {
        $match = $this->matchRepo->find($matchId);
        $joins = $match->joinings()->where([
            ["team_id", $teamId],
            ["status", "requested"]
        ])->get();
        $list = $joins->map(function($join){
            $obj = new \stdClass;
            $obj->id = $join->user->id;
            $obj->username = $join->user->username;
            $obj->name = $join->user->name;
            $obj->avatar = $join->user->info->avatar;
            $obj->request_id = $join->id;

            return $obj;
        });

        return $list;
    }
    
    /**
     * updateStatus
     *
     * @return void
     */
    public function updateStatus()
    {
        $new = $this->matchRepo->getNewMatch();
        $upcoming = $this->matchRepo->getUpcomingMatch();
        $current = $this->matchRepo->getCurrentMatch();

        foreach ($new as $match) {
            $time = explode(', ', $match->time);
            $startTime = new Carbon($time[0]);
            if($startTime->diffInHours() == 0){
                $match->status = 'upcoming';
            }

            $match->save();
        }

        foreach ($upcoming as $match) {
            $time = explode(', ', $match->time);
            $startTime = new Carbon($time[0]);
            if($startTime->diffInMinutes() == 0){
                $match->status = 'happening';
            }

            $match->save();
        }

        foreach ($current as $match) {
            $time = explode(', ', $match->time);
            $endTime = new Carbon($time[1]);
            
            if($endTime->diffInMinutes() == 0){
                $match->status = 'old';
            }

            $match->save();
        }
    }
    
    /**
     * getReviewMember (team_id, team_avatar, team_name, member_id, member_avatar, member_name)
     *
     * @param  mixed $id
     * @return void
     */
    public function getReviewMember($id)
    {
        $match = $this->matchRepo->find($id);
        
        $data = new \stdClass;

        //get my join
        $join = $match->joinings()->where([
            ['status', 'active'],
            ['player_id', Auth::id()]
        ])->first();
        
        //get opponent team
        $team = $match->team_1 == $join->team_id ? $match->team2 : $match->team1;
        
        $data->team_id = $team->id;
        $data->team_name = $team->name;
        $data->team_avatar = $team->avatar;

        $joinings = $match->joinings()->where('team_id', $join->team_id)->get();
        //get member
        $data->members = $joinings->map(function($join){
            $obj = new \stdClass;
            $user = $join->user;
            $obj->id = $user->id;
            $obj->avatar = $user->info->avatar;
            $obj->name = $user->name;
            $obj->join_id = $join->id;
            return $obj;
        });

        //return
        return $data;
    }
    
    /**
     * getReviewStadium
     *
     * @param  mixed $id
     * @return void
     */
    public function getReviewStadium($id)
    {
        $match = $this->matchRepo->find($id);

        $stadium = $match->booking->stadium;

        $data = new \stdClass;
        $data->id = $stadium->id;
        $data->name = $stadium->name;
        
        return $data;
    }

    public function reviewMember($result, $matchId, $teamId, $teamRating, $members)
    {
        //add result
        $this->matchResultRepo->create([
            "reviewer_id" => Auth::id(),
            "match_id" => $matchId,
        ],[
            "opponent_team_id" => $teamId,
            "rating" => $teamRating,
            "result" => $result,
        ]);

        //add review for member
        $members = json_decode($members);
        foreach ($members as $member) {
            $this->matchAttendantRepo->create([
                "id_match_joining" => $member->join_id,
                "user_id" => Auth::id()
            ],[
                "rating" => $member->rating,
                "attendance" => $member->rating == 0 ? 0 : 1,
            ]);
        }
    }
}