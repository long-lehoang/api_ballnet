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

    /**
     * Count follow of user
     * 
     * @param int user_id
     * @return int 
     */
    public function count($user_id){
        try{
            $count = $this->_model::where("id_follow",$user_id)->count();
            return $this->sendSuccess($count);
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
}