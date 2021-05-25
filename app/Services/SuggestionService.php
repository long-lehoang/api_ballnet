<?php

namespace App\Services;

use App\Contracts\Suggestion;
use App\Repository\UserRepo;
use App\Repository\MatchRepo;
use App\Repository\StadiumRepo;
use App\Models\User;

use Auth;

class SuggestionService implements Suggestion{

    protected $userRepo;
    protected $matchRepo;
    protected $stdRepo;

    function __construct(UserRepo $userRepo, MatchRepo $matchRepo, StadiumRepo $stdRepo){
        $this->userRepo = $userRepo;
        $this->matchRepo = $matchRepo;
        $this->stdRepo = $stdRepo;
    }
    
    /**
     * friend
     *
     * @return void
     */
    public function friend()
    {
        //get current location of user
        $user = Auth::guard('api')->user();
        $addrUser = $user->info->address;

        //get friend id
        $friends = $user->friends->map->id_friend->toArray();
        array_push($friends,Auth::id());

        $people = [];
        if(is_null($addrUser)){
            $people = User::whereNotIn('id', $friends)->offset(0)->limit(10)->get(); 
        }else{
            $list = User::whereNotIn('id', $friends)->get();
            //get same location
            $people = $list->filter(function($pp){
                $user = Auth::guard('api')->user();
                $addrUser = $user->info->address;

                return $pp->info->address === $addrUser;
            });

            //get same city
            if(empty($people->toArray())){
                $people = $list->filter(function($pp){
                    $user = Auth::guard('api')->user();
                    $addrUser = explode(', ',$user->info->address)[1];

                    if(is_null($pp->info->address)){
                        return false;
                    }
                    return explode(', ',$pp->info->address)[1] === $addrUser;
                });
            }

            //get another
            if(empty($people->toArray())){
                $people = $list;
            }
            
        }
        return $people;
    }
    
    /**
     * match
     *
     * @return void
     */
    public function match()
    {
        return [];

    }
    
    /**
     * stadium
     *
     * @return void
     */
    public function stadium()
    {
        return [];
        
    }
}