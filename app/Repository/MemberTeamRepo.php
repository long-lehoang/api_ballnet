<?php
namespace App\Repository;

use Exception;
use App\Repository\BaseRepository;
use Illuminate\Support\Facades\Auth;

class MemberTeamRepo extends BaseRepository{
    /**
     * Get Model
     * @return classname
     */
    public function getModel()
    {
        return \App\Models\MemberTeam::class;
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