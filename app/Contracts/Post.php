<?php

namespace App\Contracts;

interface Post{    
    /**
     * getPostByUser
     *
     * @param  mixed $username
     * @return void
     */
    public function getPostByUser($username);
}