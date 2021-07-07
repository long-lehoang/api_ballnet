<?php

namespace App\Contracts;

interface Stadium{    
                
    /**
     * setExtension
     *
     * @param  mixed $id
     * @param  mixed $extensions
     * @return void
     */
    public function setExtension($id, $extensions);    
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