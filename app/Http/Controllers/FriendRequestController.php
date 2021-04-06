<?php

namespace App\Http\Controllers;

use App\Http\Requests\FriendRequest\AddFriendRequest;
use Illuminate\Support\Facades\Auth;
use App\Contracts\Friend;
use App\Repository\FriendRequestRepo;

class FriendRequestController extends Controller
{    
    /**
     * __construct
     *
     * @return void
     */
    protected $friendService;
    protected $fRRepo;
    
    public function __construct(Friend $friendService, FriendRequestRepo $fRRepo)
    {
        $this->friendService = $friendService;
        $this->fRRepo = $fRRepo;
    }
    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $user = Auth::guard('api')->user();
        return $this->sendResponse($user->request);
    }
        
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(AddFriendRequest $request)
    {
        $username = $request->only('username');
        $result = $this->friendService->addFriend($username);
        
        if($result['success']){
            return $this->sendResponse(null,$result['message'],$result['code']);
        }else{
            return $this->sendError(null,$result['message'],$result['code']);
        }
    }
    
    /**
     * delete
     *
     * @param  mixed $id
     * @return void
     */
    public function delete($id)
    {
        $result = $this->fRRepo->delete($id);
        if($result){
            return $this->sendResponse();
        }else{
            return $this->sendError(null, 'Not Found Request', 404);
        }
    }
    
    /**
     * acceptRequest
     *
     * @param  mixed $id
     * @return void
     */
    public function acceptRequest($id)
    {
        //call accept request
        $result = $this->friendService->acceptRequest($id);
        //response
        if($result['success']){
            return $this->sendResponse();
        }else{
            return $this->sendError(null, $result['message'], $result['code']);
        }
    }
}
