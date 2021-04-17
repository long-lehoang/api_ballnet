<?php
namespace App\Repository;

use Exception;
use App\Repository\BaseRepository;

class TeamRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Team::class;
    }

}