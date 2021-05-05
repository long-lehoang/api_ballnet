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
}