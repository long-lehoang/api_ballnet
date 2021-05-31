<?php

namespace App\Repository;

use App\Repository\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Exception;

class SportRepo extends BaseRepository
{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Sport::class;
    }

    /**
     * Count follow of user
     * 
     * @param int user_id
     * @return int 
     */
    public function mainSport($user_id){
        try{
            $records = $this->_model::where("user_id",$user_id)->orderBy("num_match","desc")->first();
            return $this->sendSuccess($records);
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
}