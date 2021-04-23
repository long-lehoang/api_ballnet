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
}