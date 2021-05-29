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
            $people->map->info;
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
        return array_values(array_slice($people->toArray(),0,10));
    }
    
    /**
     * match
     * Get match which have location in same with user 
     *
     * @return void
     */
    public function match()
    {        
        $list = $this->matchRepo->all();
        $matchs = $list->filter(function($match){
            $user = Auth::guard('api')->user();
            $addrUser = $user->info->address;
            return $match->status === 'new' && $addrUser === $match->location;
        });
        return array_values(array_slice($matchs->toArray(),0,5));

    }
    
    /**
     * stadium
     *
     * @return void
     */
    public function stadium()
    {
        $list = $this->stdRepo->all();
        $stadiums = $list->filter(function($stadium){
            $user = Auth::guard('api')->user();
            $addrUser = $user->info->address;
            $location = explode(", ",$stadium->location);
            return $stadium->status == 1 && $addrUser === $location[2].", ".$location[3];
        });
        return array_values(array_slice($stadiums->toArray(),0,5));
        
    }
}