<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\FollowRepo;
use App\Repository\UserRepo;

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