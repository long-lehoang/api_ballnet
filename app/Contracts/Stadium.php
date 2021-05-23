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

}