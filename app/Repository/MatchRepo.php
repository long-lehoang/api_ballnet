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
        return $this->_model::where('status', 'new')->get();
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
    
    /**
     * search
     *
     * @param  mixed $ids
     * @param  mixed $location
     * @param  mixed $sport
     * @return void
     */
    public function search($ids=[], $location='', $sport='')
    {
        $params = [];
        
        if(!empty($location)){
            $params[] = ["location", "LIKE" , "%$location%"];
        }
        if(!empty($sport)){
            $params[] = ["sport", $sport];
        }

        if(empty($ids)){
            $data = $this->_model::where($params)->orderBy("updated_at","desc")->paginate(config("constant.PAGINATION.MATCH.LIMIT"));
        }
        else {
            $data = $this->_model::whereIn('team_1', $ids)->where($params)->orderBy("updated_at","desc")->paginate(config("constant.PAGINATION.MATCH.LIMIT"));
        }

        return $data;
    }
}