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
    
    /**
     * search
     *
     * @param  mixed $key
     * @param  mixed $location
     * @param  mixed $sport
     * @return void
     */
    public function search($key='', $location='', $sport='');
}