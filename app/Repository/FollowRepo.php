<?php

namespace App\Repository;

use App\Repository\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Exception;

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