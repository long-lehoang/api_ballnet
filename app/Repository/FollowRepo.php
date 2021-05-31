<?php

namespace App\Repository;

use App\Repository\BaseRepository;

class FollowRepo extends BaseRepository
{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Follow::class;
    }

}