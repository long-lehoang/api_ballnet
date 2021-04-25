<?php
namespace App\Repository;

use Exception;
use App\Repository\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Log;

class TeamRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\Team::class;
    }
        
    /**
     * findMember
     *
     * @param  mixed $memberId
     * @param  mixed $teamId
     * @return void
     */
    public function findMember($memberId, $teamId)
    {
        try{
            $team = $this->find($teamId);

            $member = $team->members()->where([['status','active'],['id',$memberId]])->first();
            if(is_null($member)){
                Log::error('Not found member at function findMember');
                return $this->sendFailed();
            }else{
                return $this->sendSuccess($member);
            }
        }catch(Exception $e){
            Log::error($e->getMessage());
            return $this->sendFailed();
        }
    }
    /**
     * isMember
     *
     * @param  mixed $memberId
     * @param  mixed $teamId
     * @return void
     */
    public function isMember($memberId, $teamId)
    {
        try{
            $team = $this->find($teamId);

            if($team->id_captain === $memberId)
                return $this->sendSuccess();

            $members = $team->members;

            foreach ($members as $key => $member) {
                if($member->member_id === $memberId && $member->status === 'active') 
                    return $this->sendSuccess();
            }

            return $this->sendFailed();
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
    
    public function isAdmin($memberId, $teamId)
    {
        try{
            $team = $this->find($teamId);

            if($team->id_captain === $memberId)
                return $this->sendSuccess();

            $admins = $team->admins;

            foreach ($admins as $key => $admin) {
                if($admin->admin_id === $memberId) 
                    return $this->sendSuccess();
            }

            return $this->sendFailed();
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
    /**
     * isWaitingForApprove
     *
     * @param  mixed $userId
     * @param  mixed $teamId
     * @return void
     */
    public function isWaitingForApprove($userId, $teamId)
    {
        try{
            $team = $this->find($teamId);

            $members = $team->members;

            foreach ($members as $key => $member) {
                if($member->member_id === $userId && $member->status === 'waiting' && $member->invited_by === null) 
                    return $this->sendSuccess();
            }

            return $this->sendFailed();
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
    
    /**
     * isInvitedBy
     *
     * @param  mixed $userId
     * @param  mixed $teamId
     * @return void
     */
    public function isInvitedBy($userId, $teamId)
    {
        try{
            $team = $this->find($teamId);

            $members = $team->members;

            foreach ($members as $key => $member) {
                if($member->member_id === $memberId && $member->status === 'waiting' && $member->invited_by !== null) 
                    return $this->sendSuccess();
            }

            return $this->sendFailed();
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
    
    /**
     * topMember
     *
     * @param  mixed $teamId
     * @return void
     */
    public function topMember($teamId)
    {
        try{
            $team = $this->find($teamId);
            $captain = $team->captain;
            $members = $team->members;
            foreach ($members as $key => $member) {
                if ($member->member_id === $captain->id) {
                    unset($members[$key]);
                }
            }
            $member = $members->where('status','active')->sortByDesc("num_match")->first();

            return $this->sendSuccess($member);
        }catch(Exception $e){
            
            return $this->sendFailed();
        }
    }
    
    public function getAdmin($id)
    {
        $team = $this->find($id);
        $admins = $team->admins;

        $admins = $admins->map(function($admin){
            $user = $admin->admin;
            $obj = new \stdClass;
            $obj->id = $user->id;
            $obj->name = $user->name;
            $obj->username = $user->username;
            $obj->avatar = $user->info->avatar;
            $obj->address = $user->info->address;

            return $obj;
        });

        return $admins;
    }
    /**
     * topAdmin
     *
     * @return void
     */
    public function topAdmin($teamId){
        try{
            $team = $this->find($teamId);
            $admins = $team->admins;
            $admins = $admins->map(function($admin){
                $member = $this->findMember($admin->admin_id, $admin->team_id);
                return $member['data'];
            });
            $member = $admins->where('status','active')->sortByDesc("num_match")->first();

            return $this->sendSuccess($member);
        }catch(Exception $e){

            return $this->sendFailed();
        }
    }
    
    /**
     * replaceCaptain
     *
     * @param  mixed $captainId
     * @param  mixed $teamId
     * @return void
     */
    public function replaceCaptain($captainId, $teamId)
    {
        try{
            $result = $this->update($teamId, [
                'id_captain' => $captainId
            ]);
            if($result === false){
                return $this->sendFailed();
            }

            return $this->sendSuccess();
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
    
    /**
     * leave
     *
     * @param  mixed $memberId
     * @param  mixed $teamId
     * @return void
     */
    public function leave($memberId, $teamId)
    {
        $member = $this->findMember($memberId, $teamId);
        if($member['success']){
            $member['data']->delete();
            return $this->sendSuccess();
        }else{
            Log::error('Not found member');
            return $this->sendFailed();
        }
    }
    
    /**
     * requestJoinTeam
     *
     * @param  mixed $teamId
     * @return void
     */
    public function requestJoinTeam($teamId)
    {
        try{
            $team = $this->find($teamId);
            
            $requests = $team->members;
            $requests = $requests->map(function($request){
                if($request->status === 'waiting' && $request->invited_by === null){
                    $obj = new \stdClass;
                    $user = $request->member;
                    $obj->requestId = $request->id;
                    $obj->username = $user->username;
                    $obj->name = $user->name;
                    $obj->avatar = $user->info->avatar;
                    $obj->points = $user->info->points;
                    $obj->requestTime = $request->created_at;
                    return $obj;
                }
            });
            return $this->sendSuccess(array_values(array_filter($requests->toArray())));
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
    
    /**
     * myInvitation
     *
     * @return void
     */
    public function myInvitation()
    {
        $user = Auth::guard('api')->user();
        try{
            $requests = $user->teams;
            foreach ($requests as $key => $request) {
                if($request->status === 'waiting' && $request->invited_by !== null){
                    $team = $request->team;
                    $team->requestId = $request->id;
                    $team->member = $this->countMember($team->id);
                }
                unset($requests[$key]);
            }
    
            return $this->sendSuccess($requests);
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }

    public function countMember($teamId)
    {
        try{
            $team = $this->find($teamId);
            $members = $team->members()->where('status', 'active')->get();
            return $this->sendSuccess(count($members));
        }catch(Exception $e){
            Log::error(__CLASS__.' -> '.__FUNCTION__.' -> '.__LINE__.': '.$e->getMessage());
            return $this->sendFailed();
        }
    }

    public function getMembers($teamId)
    {
        $user = Auth::guard('api')->user();
        $team = $this->find($teamId);
        $members = $team->members()->whereNotIn('member_id', [$user->id])->get();
        
        $members = $members->map(function($member){
            $user = $member->member;
            $obj = new \stdClass;
            $obj->id = $user->id;
            $obj->name = $user->name;
            $obj->username = $user->username;
            $obj->avatar = $user->info->avatar;
            $obj->isAdmin = $this->isAdmin($user->id, $member->team->id)['success'];
            $obj->isCaptain = $member->team->id_captain === $user->id;
            $obj->joinedDate = $member->updated_at;
            $obj->num_match = $member->num_match;
            $obj->points = $user->info->points;
            $obj->memberId = $member->id;
            return $obj;
        });
        return $members;
    }
}