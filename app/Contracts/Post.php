<?php

namespace App\Contracts;

interface Post{    
    
    /**
     * getPostOfUser
     *
     * @param  mixed $id
     * @return void
     */
    public function getPostOfUser($id);
}