<?php

namespace App\Services;

use App\Contracts\People;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repository\FriendRequestRepo;

class PeopleService implements People{
    /**
     * __construct
     *
     * @param  mixed $friendRequest
     * @return void
     */
    protected $friendRequest;    
    
    public function __construct(FriendRequestRepo $friendRequest)
    {
        $this->friendRequest = $friendRequest;
    }
    /**
     * getPeople
     *
     * @return void
     */
    public function getPeople()
    {
        try{
            //get friends
            $user = Auth::guard('api')->user();
            $idFriends = $user->friends()->pluck('id_friend')->all();
            array_push($idFriends, $user->id);

            //get friends request
            $sendedRequest = $this->friendRequest->getSendedRequest($user->id);
            $sendedRequest = $sendedRequest['data'];
            $list = array_merge($idFriends, $sendedRequest);
            
            //get people without friends
            $data = DB::table('users')
            ->join('infos', 'users.id', '=', 'infos.user_id')
            ->select('users.username as username', 'users.name as name', 'infos.avatar as avatar', 'infos.address as address', 'infos.points as points')
            ->whereNotIn('users.id',$list)
            ->get();

            //return
            return [
                "success" => true,
                "data" => $data    
            ];
        }catch(Exception $e){
            return [
                "success" => false,
                "message" => $e
            ];
        }
    }
}