<?php

namespace App\Repository;

use App\Repository\BaseRepository;

class AdminTeamRepo extends BaseRepository
{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\AdminTeam::class;
    }

}