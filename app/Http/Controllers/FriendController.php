<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\FriendRepo;
use App\Repository\UserRepo;

class FriendController extends Controller
{

    
    /**
     * __construct
     *
     * @param  mixed $repo
     * @param  mixed $user
     * @param  mixed $friendService
     * @return void
     */
    protected $repo;    
    protected $user;

    public function __construct(FriendRepo $repo, UserRepo $user)
    {
        $this->user = $user;
        $this->repo = $repo;
    }

    /**
     * Show list friends
     * 
     */
    public function index(Request $request)
    {
        $result = $this->repo->getFriends();
        if($result['success']){
            return $this->sendResponse($result['data']);
        }else{
            return $this->sendError();
        }
    }
    
    /**
     * count
     *
     * @param  mixed $username
     * @return void
     */
    public function count($username){
        $user = $this->user->findUser($username);
        if($user['success']){
            $friends = $user['data']->friends()->count();
            return $this->sendResponse($friends);
        }
        else{
            return $this->sendError();
        }
    }
}