<?php

namespace App\Services;

use App\Contracts\Friend;
use Exception;
use App\Repository\FriendRequestRepo;
use App\Repository\FriendRepo;
use Illuminate\Support\Facades\Auth;
use App\Repository\UserRepo;
use DB;

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

    /**
     * getFriendOfUser
     *
     * @param  mixed $username
     * @return void
     */
    public function getFriendOfUser($username)
    {
        //find user
        $user = $this->user->findUser($username);
        if(!$user['success']){
            //case not found username
            return [
                "success" => false,
                "message" => "Not Found User",
                "code" => 404
            ];
        }
        $user = $user['data'];
        //get current user
        $curUser = Auth::guard('api')->user();
        try{
            $friends = $user->friends->map(function ($friend){
                $user = $friend->friend;
                $user_id = $user->id;
                $name = $user->name;
                $username = $user->username;
                $avatar = $user->info->avatar;
                $point = $user->info->points;
                $mutualFriend = $this->countMutualFriend(Auth::guard('api')->user()->id, $user_id);
                $isFriend = $this->fRepo->friendship($user_id);
                return [
                    "user_id" => $user_id,
                    "name" => $name,
                    "username" => $username,
                    "avatar" => $avatar,
                    "point" => $point,
                    "mutual_friends" => $mutualFriend,
                    "is_friend" => $isFriend,
                    "created_at" => $friend->created_at,
                ];
            });
            //case success

            return [
                "success" => true,
                "data" => $friends
            ];
        }catch(Exception $e){
            //case exception
            return [
                "success" => false,
                "message" => "Server Error",
                "code" => 500
            ];
        }
    }
    
    /**
     * countMutualFriend
     *
     * @param  mixed $username1
     * @param  mixed $username2
     * @return void
     */
    public function countMutualFriend($id1, $id2)
    {
        $count = DB::select("
            SELECT COUNT(a.id) as num
            FROM friends as a, friends as b 
            WHERE a.id_friend = b.id_friend AND  a.user_id = $id1 AND b.user_id = $id2
            GROUP BY a.user_id
        ");
        if (empty($count))
        {
            return 0;
        }else{
            return $count[0]->num;
        }
    }
    
    /**
     * getFriendRequests
     *
     * @return void
     */
    public function getFriendRequests()
    {
        $user = Auth::guard('api')->user();

        $friendRequests = $user->friendRequests;
        $friendRequests = $friendRequests->map(function($fR){
            $obj = new \stdClass;
            
            $user = $fR->request;
            $info = $user->info;
            
            $obj->idRequest = $fR->id;
            $obj->name = $user->name;
            $obj->username = $user->username;
            $obj->avatar = $info->avatar;
            $obj->address = $info->address;
            $obj->points = $info->points;
            $obj->sport = $user->sports->pluck('sport');
            
            return $obj;
        });
        return $friendRequests;
    }
}