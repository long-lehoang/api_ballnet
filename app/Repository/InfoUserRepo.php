<?php
namespace App\Repository;

use App\Repository\BaseRepository;

class InfoUserRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\InfoUser::class;
    }
    
    
}