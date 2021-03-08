<?php

namespace App\Repository;

use App\Repository\BaseRepository;

class PostRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Post::class;
    }

    
}