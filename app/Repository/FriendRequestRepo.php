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
            $request = $this->_model::updateOrCreate([
                "user_id" => $user_id, 
                "from_id" => $from_id
            ]);
            return $this->sendSuccess($request->id);
        }catch(Exception $e){
            Log::error(__CLASS__.' :: '.__FUNCTION__.' : '.$e);
            return $this->sendFailed();
        }
    }
    
    /**
     * getSendedRequest
     *
     * @param  mixed $from_id
     * @return void
     */
    public function getSendedRequest($from_id)
    {
        try{
            $request = $this->_model::where('from_id', $from_id)->get()->pluck('user_id')->toArray();
            return $this->sendSuccess($request);
        }catch(Exception $e){
            Log::error(__CLASS__.' :: '.__FUNCTION__.' : '.$e);
        }
    }
}