<?php

namespace App\Repository;

use App\Repository\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Exception;

class FriendRepo extends BaseRepository
{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Friend::class;
    }

    /**
     * Get list friends
     * 
     */
    public function getFriends()
    {
        $result = [];

        $user = Auth::guard('api')->user();

        try{
            $friends = $user->friends;
            foreach($friends as $friend){

                array_push($result,[
                    "id" => $friend->id_friend,
                    "name" => $friend->friend->name,
                    "avatar" => $friend->friend->avatar == null ? null : $friend->friend->avatar
                ]);
            }
            return $this->sendSuccess($result);
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }

    /**
     * Count friends of user
     * 
     * @param int user_id
     * @return int 
     */
    public function count($user_id){
        try{
            $count = $this->_model::where("user_id",$user_id)->count();
            return $this->sendSuccess($count);
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
}