<?php

namespace App\Services;

use App\Contracts\People;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repository\FriendRequestRepo;
use App\Repository\FriendRepo;

class PeopleService implements People{
    /**
     * __construct
     *
     * @param  mixed $friendRequest
     * @return void
     */
    protected $friendRequest;    
    protected $friend;    
    
    public function __construct(FriendRequestRepo $friendRequest,FriendRepo $friend )
    {
        $this->friendRequest = $friendRequest;
        $this->friend = $friend;
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
            // $idFriends = $user->friends()->pluck('id_friend')->all();
            // array_push($idFriends, $user->id);

            // //get friends request
            // $sendedRequest = $this->friendRequest->getSendedRequest($user->id);
            // $sendedRequest = $sendedRequest['data'];
            // $list = array_merge($idFriends, $sendedRequest);
            
            //get people without friends
            $data = DB::table('users')
            ->join('infos', 'users.id', '=', 'infos.user_id')
            ->select('users.id as id','users.username as username', 'users.name as name', 'infos.avatar as avatar', 'infos.address as address', 'infos.points as points')
            ->whereNotIn('users.id',[$user->id])
            ->get();

            $data->map(function($people){
                $people->isFriend = $this->friend->friendship($people->id);
                $request = $this->friendRequest->isRequest($people->id);
                if(!is_null($request)){
                    $people->idRequest = $request->id;
                }
                $waiting = $this->friendRequest->isWaiting($people->id);
                if(!is_null($waiting)){
                    $people->idRequest = $waiting->id;
                }
                $people->isRequest = !is_null($request);
                $people->isWaiting = !is_null($waiting);
                return $people;
            });
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