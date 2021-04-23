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
    
    /**
     * getFriendOfUser
     *
     * @param  mixed $username
     * @return void
     */
    public function getFriendOfUser($username);
    
    /**
     * countMutualFriend
     *
     * @param  mixed $username1
     * @param  mixed $username2
     * @return void
     */
    public function countMutualFriend($username1, $username2);
    
    /**
     * getFriendRequests
     *
     * @return void
     */
    public function getFriendRequests();
}