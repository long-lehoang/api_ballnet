<?php

namespace App\Contracts;

interface Friend{        
    /**
     * addFriend
     *
     * @param  mixed $username
     * @return void
     */
    public function addFriend($username);
    
    /**
     * acceptRequest
     *
     * @param  mixed $id
     * @return void
     */
    public function acceptRequest($id);
}