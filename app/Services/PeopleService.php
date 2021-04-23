<?php

namespace App\Services;

use App\Contracts\People;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repository\FriendRequestRepo;
use App\Repository\FriendRepo;
use App\Models\User;

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
            
            $data = User::whereNotIn('id',[$user->id])->get();

            $data = $data->map(function($people){
                $people->isFriend = $this->friend->friendship($people->id);
                $request = $this->friendRequest->isRequest($people->id);
                if(!is_null($request)){
                    $people->idRequest = $request->id;
                }
                $waiting = $this->friendRequest->isWaiting($people->id);
                if(!is_null($waiting)){
                    $people->idRequest = $waiting->id;
                }
                $people->sports = $people->sports->pluck('sport');
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