<?php

namespace App\Repository;

use App\Repository\BaseRepository;
use Illuminate\Support\Facades\Auth;

class MatchInvitationRepo extends BaseRepository
{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\MatchInvitation::class;
    }
    
    /**
     * invitation
     *
     * @return void
     */
    public function create($keys, $data)
    {
        return $this->_model->firstOrCreate($keys,$data);
    }

    public function deleteByMatchId($matchId)
    {
        return $this->_model::where('match_id', $matchId)->delete();
    }
}