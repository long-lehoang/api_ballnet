<?php

namespace App\Repository;

use App\Repository\BaseRepository;

class MatchRepo extends BaseRepository
{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Match::class;
    }
}