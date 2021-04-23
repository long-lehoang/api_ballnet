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
        $request = $this->friendService->getFriendRequests();
        return $this->sendResponse($request);
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
            return $this->sendResponse($result['data'],$result['message'],$result['code']);
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
        $request = $this->fRRepo->find($id);
        $this->authorize('cancelRequest', $request);

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
        $request = $this->fRRepo->find($id);
        $this->authorize('acceptRequest', $request);
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
