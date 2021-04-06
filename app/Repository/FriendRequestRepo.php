<?php

namespace App\Repository;

use App\Repository\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Exception;
use Log;

class FriendRequestRepo extends BaseRepository
{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\FriendRequest::class;
    }
    
    /**
     * addRequest
     *
     * @param  mixed $user_id
     * @param  mixed $from_id
     * @return void
     */
    public function addRequest($user_id, $from_id)
    {
        try{
            $this->_model::updateOrCreate([
                "user_id" => $user_id, 
                "from_id" => $from_id
            ]);
            return $this->sendSuccess();
        }catch(Exception $e){
            Log::error(__CLASS__.' :: '.__FUNCTION__.' : '.$e);
            return $this->sendFailed();
        }
    }

}