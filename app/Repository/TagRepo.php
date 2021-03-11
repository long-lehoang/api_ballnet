<?php
namespace App\Repository;

use App\Repository\BaseRepository;

class TagRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Tag::class;
    }
    
}