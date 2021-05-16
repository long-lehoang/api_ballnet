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
    
    /**
     * getNewMatch
     *
     * @return void
     */
    public function getNewMatch()
    {
        return $this->_model::where('status', 'active')->get();
    }
    
    /**
     * getUpcomingMatch
     *
     * @return void
     */
    public function getUpcomingMatch()
    {
        return $this->_model::where('status', 'upcoming')->get();
    }
    
    /**
     * getCurrentMatch
     *
     * @return void
     */
    public function getCurrentMatch()
    {
        return $this->_model::where('status', 'happening')->get();
    }
}