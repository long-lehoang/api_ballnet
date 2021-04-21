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
}