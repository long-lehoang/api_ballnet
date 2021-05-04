<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\FollowRepo;
use App\Repository\UserRepo;
use Auth;
use Log;

class FollowController extends Controller
{
    //
    protected $repo;
    protected $user;
    
    public function __construct(FollowRepo $repo, UserRepo $user)
    {
        $this->repo = $repo;
        $this->user = $user;
    }

    /**
     * Count friends
     * 
     * @return [json]
     */
    public function count($username){
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

        $user = $this->user->findUser($username);
        if($user['success']){
            $follow = $user['data']->follower()->count();
            return $this->sendResponse($follow);
        }
        else{
            return $this->sendError();
        }
    }
}