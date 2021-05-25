<?php

namespace App\Contracts;

interface Suggestion{    
                
    /**
     * friend
     *
     * @return void
     */
    public function friend();    

    /**
     * match
     *
     * @return void
     */
    public function match();
        
    /**
     * stadium
     *
     * @return void
     */
    public function stadium();
}