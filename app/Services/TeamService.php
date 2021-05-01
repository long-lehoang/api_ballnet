<?php

namespace App\Services;

use App\Contracts\Team;
use Illuminate\Support\Facades\Auth;
use App\Repository\TeamRepo;
use App\Repository\AdminTeamRepo;
use App\Repository\MemberTeamRepo;
use Log;

class TeamService implements Team{
    
    protected $teamRepo;    
    protected $adTeamRepo;    
    protected $mbTeamRepo;    
    /**
     * __construct
     *
     * @param  mixed $teamRepo
     * @return void
     */
    function __construct(TeamRepo $teamRepo, MemberTeamRepo $mbTeamRepo, AdminTeamRepo $adTeamRepo)
    {
        $this->teamRepo = $teamRepo;
        $this->adTeamRepo = $adTeamRepo;
        $this->mbTeamRepo = $mbTeamRepo;
    }
    /**
     * getMyTeam
     *
     * @return void
     */
    public function getMyTeam()
    {
        $user = Auth::guard('api')->user();

        //teams attended
        $team = $user->teams->map(function($team){
            if($team->status === 'active'){
                $obj = new \stdClass;
                $obj = clone $team->team;
                $obj->member = $this->teamRepo->countMember($team->team->id)['data'];
                $obj->avatarMembers = $team->team->members
                ->filter(function($member){return $member->status === 'active';})
                ->map->member
                ->map->info->map->only(['avatar'])->map->avatar->values();

                return $obj;
            }
        });
        return array_values(array_filter($team->toArray()));
    }
    public function getTeams()
    {
        $teams = $this->teamRepo->all();
        $teams = $teams->map(function($team){
            $obj = new \stdClass;
            $obj = clone $team;
            $user = Auth::guard('api')->user();
            $obj->isMember = $this->teamRepo->isMember($user->id, $team->id)['success'];
            $obj->isWaitingForApprove = $this->teamRepo->isWaitingForApprove($user->id, $team->id)['success'];
            $obj->isInvitedBy = $this->teamRepo->isInvitedBy($user->id, $team->id)['success'];
            $obj->member = $this->teamRepo->countMember($team->id)['data'];
            if($obj->isInvitedBy || $obj->isWaitingForApprove){
                $obj->idRequest = $this->mbTeamRepo->findRequest($user->id, $team->id)->id;
            }
            $obj->avatarMembers = $team->members
            ->filter(function($member){return $member->status === 'active';})
            ->map->member
            ->map->info->map->only(['avatar'])->map->avatar->values();
            return $obj;
        });

        return $teams;
    }
    /**
     * leave
     *
     * @param  mixed $teamId
     * @return void
     */
    public function leave($teamId)
    {
        //get user
        $user = Auth::guard('api')->user();

        //get team
        $team = $this->teamRepo->find($teamId);

        //check if captain leave team
        if($team->id_captain === $user->id){
            return [
                'success' => false,
                'message' => "Captain can't leave team, please change captain of team"
            ];
        }

        //remove member
        $result = $this->teamRepo->leave($user->id, $team->id);
        if(!$result['success']){
            Log::error('Error when member leave team');

            return [
                'success' => false,
                'message' => "Can't remove member from this team"
            ];
        }

        //case success
        return [
            'success' => true,
            'data' => null
        ];
    }

    public function getPermission($teamId)
    {
        $user = Auth::guard('api')->user();
        $team = $this->teamRepo->find($teamId);
        $isCaptain = $team->id_captain === $user->id;
        return [
            'isMember' => $this->teamRepo->isMember($user->id, $team->id)['success'],
            'isCaptain' => $isCaptain,
            'isAdmin' => $this->teamRepo->isAdmin($user->id, $team->id)['success']
        ];
    }
    
    /**
     * setAdmin
     *
     * @param  mixed $teamId
     * @param  mixed $admins
     * @return void
     */
    public function setAdmin($teamId, $admins)
    {
        $team = $this->teamRepo->find($teamId);
        $team->admins()->delete();
        Log::info(var_dump($admins));
        if(is_array($admins)){
            foreach ($admins as $key => $value) {
                if(!empty($value)){
                    $this->adTeamRepo->create([
                        "team_id" => $teamId,
                        "admin_id" => $value
                    ]);
                }
            }
            return true;
        }else{
            return false;
        }
    }
    
    public function getTeam($id)
    {
        $user = Auth::guard('api')->user();
        $team = $this->teamRepo->find($id);
        $team->isMember = $this->teamRepo->isMember($user->id, $team->id)['success'];
        $team->isWaitingForApprove = $this->teamRepo->isWaitingForApprove($user->id, $team->id)['success'];
        $team->isInvitedBy = $this->teamRepo->isInvitedBy($user->id, $team->id)['success'];
        $team->member = $this->teamRepo->countMember($team->id)['data'];
        if($team->isInvitedBy || $team->isWaitingForApprove){
            $team->idRequest = $this->mbTeamRepo->findRequest($user->id, $team->id)->id;
        }
        $isCaptain = $team->id_captain === $user->id;
        $team->isCaptain = $isCaptain;
        $team->isAdmin = $this->teamRepo->isAdmin($user->id, $team->id)['success'];
        return $team;
    }

    public function kickMember($memberId)
    {
        $member = $this->mbTeamRepo->find($memberId);
        if($member->member_id === $member->team->id_captain){
            return false;
        }

        if($member->admin !== null){
            if(Auth::id() !== $member->team->id_captain){
                return false;                
            }
        }

        $member->delete();
        return true;
    }

    public function getFriendToInvite($teamId)
    {
        $user = Auth::guard('api')->user();
        $team = $this->teamRepo->find($teamId);
        $members = $team->members->pluck('member_id')->toArray();
        $friends = $user->friends()->whereNotIn('id_friend',$members)->get();
        $result = $friends->map(function($friend){
            $user = $friend->friend;
            $obj = new \stdClass;
            $obj->id = $user->id;
            $obj->name = $user->name;
            $obj->avatar = $user->info->avatar;
            $obj->username = $user->username;

            return $obj;
        });
        return $result;
    }
    
    /**
     * changeCaptain
     *
     * @param  mixed $teamId
     * @param  mixed $captainId
     * @return void
     */
    public function changeCaptain($teamId, $captainId)
    {
        $member = $this->mbTeamRepo->findMember($teamId, $captainId);
        $team = $this->teamRepo->find($teamId);
        $team->id_captain = $captainId;
        $team->save();
        $this->adTeamRepo->deleteAdmin($teamId, $captainId);
    }
}
