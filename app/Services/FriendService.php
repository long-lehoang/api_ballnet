<?php

namespace App\Services;

use App\Contracts\Friend;
use Exception;
use App\Repository\FriendRequestRepo;
use App\Repository\FriendRepo;
use Illuminate\Support\Facades\Auth;
use App\Repository\UserRepo;

class FriendService implements Friend{    
    /**
     * __construct
     *
     * @return void
     */
    protected $fRRepo;
    protected $user;
    protected $fRepo;

    public function __construct(FriendRequestRepo $fRRepo, FriendRepo $fRepo, UserRepo $user)
    {
        $this->fRRepo = $fRRepo;
        $this->user = $user;
        $this->fRepo = $fRepo;
    }
    
    /**
     * addFriend
     *
     * @param  mixed $username
     * @return void
     */
    public function addFriend($username)
    {
        //get user
        $user = Auth::guard('api')->user();

        //get friend
        $friend = $this->user->findUser($username);
        if(!$friend['success']){
            return [
                'success' => false,
                'message' => 'Not Found People', 
                'code' => 404
            ];
        }
        
        //check self addfriend
        if($friend['data']==$user){
            return [
                'success' => false,
                'message' => 'Bad request', 
                'code' => 400
            ];
        }

        //check duplicate friend
        $friendship = $this->fRepo->friendship($friend['data']->id);
        if(isset($friendship['error'])){
            return [
                'success' => false,
                'message' => 'Server Error', 
                'code' => 500
            ];
        }
        if($friendship){
            return [
                'success' => false,
                'message' => 'Friend was existed', 
                'code' => 400
            ];
        }

        //add friend request
        $result = $this->fRRepo->addRequest($friend['data']->id, $user->id);

        //response
        if($result['success']){
            return [
                'success' => true,
                'message' => 'success',
                'code' => 200,
                'data' => $result['data']
            ];
        }else{
            return [
                'success' => false,
                'message' => 'Server Error',
                'code' => 500
            ];
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
        //find request
        try{
            $request = $this->fRRepo->find($id);
        }catch(Exception $e){
            return [
                'success' => false,
                'message' => 'Not Found Request',
                'code' => 404
            ];
        }

        
        //get user
        $user = $request->user;
        $from_id = $request->from_id;

        //check duplicate friend
        $friendship = $this->fRepo->friendship($from_id);
        if(isset($friendship['error'])){
            return [
                'success' => false,
                'message' => 'Server Error', 
                'code' => 500
            ];
        }
        if($friendship){
            return [
                'success' => false,
                'message' => 'Friend was existed', 
                'code' => 400
            ];
        }

        //add friend
        $this->fRepo->create([
            'user_id' => $user->id,
            'id_friend' => $from_id
        ]);

        $this->fRepo->create([
            'user_id' => $from_id,
            'id_friend' => $user->id
        ]);

        //delete request
        $del = $this->fRRepo->delete($id);
        if($del){
            return [
                'success' => true,
                'message' => 'success',
                'code' => 200
            ];
        }else{
            return [
                'success' => false,
                'message' => 'Server Error',
                'code' => 500
            ];
        }
    }
}