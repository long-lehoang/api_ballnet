<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\FriendRepo;
use App\Repository\UserRepo;
use App\Contracts\Friend;

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
    protected $friendService;

    public function __construct(FriendRepo $repo, UserRepo $user, Friend $friendService)
    {
        $this->user = $user;
        $this->repo = $repo;
        $this->friendService = $friendService;
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
    
    /**
     * getFriendOfUser
     *
     * @param  mixed $username
     * @return void
     */
    public function getFriendOfUser($username)
    {
        $result = $this->friendService->getFriendOfUser($username);
        if($result['success']){
            return $this->sendResponse($result['data']);
        }else{
            return $this->sendError(null,$result['message'],$result['code']);
        }
    }
    
    /**
     * delete
     *
     * @param  mixed $username
     * @return void
     */
    public function delete($username)
    {
        $user = $this->user->findUser($username);
        if(!$user['success']){
            return $this->sendError(null, 'Not Found User', 404);
        }
        $result = $this->repo->unfriend($user['data']->id);

        if($result['success']){
            return $this->sendResponse();
        }else{
            return $this->sendError();
        }
    }
}