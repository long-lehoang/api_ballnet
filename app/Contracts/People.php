<?php

namespace App\Contracts;

interface People{    
    /**
     * getPeople
     *
     * @return void
     */
    public function getPeople();
    
    /**
     * getUser
     *
     * @return void
     */
    public function getUser($username);
}