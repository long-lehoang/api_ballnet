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
    
    /**
     * invitation
     *
     * @return void
     */
    public function invitation()
    {
        $data = $this->_model::where([
            ['status', 'waiting'],
            ['player_id', Auth::id()],
        ])->get();
        $data = $data->map->filter(function($mj){ return $mj->invited_by !== null;});

        return $data;
    }
}