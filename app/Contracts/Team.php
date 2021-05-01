<?php

namespace App\Contracts;

interface Team{    
        
    /**
     * getMyTeam
     *
     * @return void
     */
    public function getMyTeam();

    /**
     * leave
     *
     * @param  mixed $teamId
     * @return void
     */
    public function leave($teamId);
    
    /**
     * getPermission
     *
     * @param  mixed $teamId
     * @return void
     */
    public function getPermission($teamId);
    
    /**
     * setAdmin
     *
     * @param  mixed $teamId
     * @param  mixed $admins
     * @return void
     */
    public function setAdmin($teamId, $admins);
    
    /**
     * getTeam
     *
     * @param  mixed $id
     * @return void
     */
    public function getTeam($id);
    
    /**
     * kickMember
     *
     * @param  mixed $memberId
     * @return void
     */
    public function kickMember($memberId);
    
    /**
     * getFriendToInvite
     *
     * @param  mixed $teamId
     * @return void
     */
    public function getFriendToInvite($teamId);
    
    /**
     * changeCaptain
     *
     * @param  mixed $teamId
     * @param  mixed $captainId
     * @return void
     */
    public function changeCaptain($teamId, $captainId);
}