<?php

namespace App\Contracts;

interface Match{    
            
    /**
     * acceptTeam
     *
     * @param  mixed $invitationId
     * @return void
     */
    public function acceptTeam($invitationId);
    
    /**
     * cancelTeam
     *
     * @param  mixed $invitationId
     * @return void
     */
    public function cancelTeam($invitationId);
    
    /**
     * inviteTeam
     *
     * @param  mixed $teams
     * @param  mixed $matchId
     * @return void
     */
    public function inviteTeam($teams, $matchId);
    
        
    /**
     * userJoin
     *
     * @param  mixed $matchId
     * @param  mixed $teamId
     * @param  mixed $playerId
     * @return void
     */
    public function userJoin($matchId, $teamId, $playerId);
    
    /**
     * getFriendNotInMatch
     *
     * @param  mixed $matchId
     * @return void
     */
    public function getFriendNotInMatch($matchId);
    
    /**
     * memberOfTeam
     *
     * @param  mixed $id
     * @param  mixed $team_id
     * @return void
     */
    public function memberOfTeam($id, $team_id);
            
    /**
     * getTeamRequestOfMatch
     *
     * @param  mixed $id
     * @return void
     */
    public function getTeamRequestOfMatch($id);
        
    /**
     * requestOfTeam
     *
     * @param  mixed $matchId
     * @param  mixed $teamId
     * @return void
     */
    public function requestOfTeam($matchId, $teamId);

    /**
     * updateStatus
     *
     * @return void
     */
    public function updateStatus();
    
    /**
     * getReviewMember
     *
     * @param  mixed $id
     * @return void
     */
    public function getToReview($id);
        
    /**
     * reviewMember
     *
     * @param  mixed $result
     * @param  mixed $matchId
     * @param  mixed $teamId
     * @param  mixed $teamRating
     * @param  mixed $members
     * @return void
     */
    public function review($result, $matchId, $teamId, $teamRating, $members);

}