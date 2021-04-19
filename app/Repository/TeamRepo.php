<?php
namespace App\Repository;

use Exception;
use App\Repository\BaseRepository;
use Illuminate\Support\Facades\Auth;

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

            $members = $team->members;

            foreach ($members as $key => $member) {
                if($member->member_id === $memberId && $member->status === 'active') 
                    return $this->sendSuccess($member);
            }

            return $this->sendFailed();
        }catch(Exception $e){
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
                if($member->member_id === $memberId && $member->status === 'waiting' && $member->invited_by === null) 
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
            $members = $team->members;
            $member = $members->where('status','active')->sortByDesc("num_match")->first();

            return $this->sendSuccess($member);
        }catch(Exception $e){
            
            return $this->sendFailed();
        }
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
        }else{
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
                    $member = $request->member;
                    $member->requestId = $request->id;
                    return $member;
                }
            });

            return $this->sendSuccess($requests);
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
            $requests = $requests->map(function($request){
                if($request->status === 'waiting' && $request->invited_by !== null){
                    $team = $request->team;
                    $team->requestId = $request->id;
                    return $team;
                }
            });
    
            return $this->sendSuccess($requests);
        }catch(Exception $e){
            return $this->sendError();
        }
    }
    
    /**
     * join
     *
     * @param  mixed $teamId
     * @return void
     */
    public function join($teamId)
    {
        $user = Auth::guard('api')->user();
        try{
            $result = $this->_model::firstOrCreate(
                [
                    'team_id' => $teamId,
                    'member_id' => $user->id,
                ],
                [
                    'team_id' => $teamId,
                    'member_id' => $user->id,
                    'invited_by' => null,
                    'status' => 'waiting',
                ]
            );

            return $this->sendSuccess($result->id);
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
    
    /**
     * invite
     *
     * @param  mixed $userId
     * @param  mixed $teamId
     * @return void
     */
    public function invite($userId, $teamId)
    {
        $user = Auth::guard('api')->user();
        try{
            $result = $this->_model::firstOrCreate(
                [
                    'team_id' => $teamId,
                    'member_id' => $userId,
                ],
                [
                    'team_id' => $teamId,
                    'member_id' => $userId,
                    'invited_by' => $user->id,
                    'status' => 'waiting'
                ]
            );

            return $this->sendSuccess($result->id);
        }catch(Exception $e){
            dd($e->getMessage());
            return $this->sendFailed();
        }
    }
    
    /**
     * approve
     *
     * @param  mixed $id
     * @return void
     */
    public function approve($id)
    {
        try{
            $request = $this->find($id);
            $request->status = 'active';
            $request->save();
            return $this->sendSuccess();
        }catch(Exception $e){
            return $this->sendFailed();
        }
    }
}