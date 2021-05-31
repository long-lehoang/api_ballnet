<?php

namespace App\Repository;

use App\Repository\BaseRepository;
use Illuminate\Support\Facades\Auth;

class MatchResultRepo extends BaseRepository
{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\MatchResult::class;
    }
    
}