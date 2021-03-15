<?php
namespace App\Repository;

use App\Repository\BaseRepository;

class InfoRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Info::class;
    }
    
    
}