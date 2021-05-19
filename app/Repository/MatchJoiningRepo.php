<?php

namespace App\Repository;

use App\Repository\BaseRepository;
use Illuminate\Support\Facades\Auth;

class MatchJoiningRepo extends BaseRepository
{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\MatchJoining::class;
    }

}