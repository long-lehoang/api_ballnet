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
                $team->members = $this->teamRepo->countMember($team->id);
                return $team->team;
            }
        });

        return $team;
    }
    public function getTeams()
    {
        $teams = $this->teamRepo->all();
        $teams->map(function($team){
            $user = Auth::guard('api')->user();
            $team->isMember = $this->teamRepo->isMember($user->id, $team->id)['success'];
            $team->isWaitingForApprove = $this->teamRepo->isWaitingForApprove($user->id, $team->id)['success'];
            $team->isInvitedBy = $this->teamRepo->isInvitedBy($user->id, $team->id)['success'];
            $team->members = $this->teamRepo->countMember($team->id);
            if($team->isInvitedBy || $team->isWaitingForApprove){
                $team->idRequest = $this->mbTeamRepo->findRequest($user->id, $team->id)->id;
            }
            return $team;
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
            $newCaptain = $this->teamRepo->topAdmin($teamId);
            if(!$newCaptain['success']){
                Log::error('Error when find new captain by admin');
                return [
                    'success' => false,
                    'message' => 'Error when find alternative admin'
                ];
            }
            
            if($newCaptain['data'] === null || ($newCaptain['data'] !== null && $newCaptain['data']->member_id === $user->id)){
                $newCaptain = $this->teamRepo->topMember($teamId);
                if(!$newCaptain['success']){
                    Log::error('Error when find new captain by member');
                    return [
                        'success' => false,
                        'message' => 'Error when find alternative member'
                    ];
                }else{
                    Log::info(__CLASS__.' -> '.__FUNCTION__.' -> '.__LINE__.': topMember[data] = '.$newCaptain['data']);

                    if($newCaptain['data'] === null || ($newCaptain['data'] !== null && $newCaptain['data']->member_id === $user->id)){
                        $result = $this->teamRepo->delete($team->id);
                        if(!$result){
                            Log::error('Error when delete team');
                            return [
                                'success' => false,
                                'message' => "Can't delete team"
                            ];
                        }else{
                            Log::info(__CLASS__.' -> '.__FUNCTION__.' -> '.__LINE__.': delete Team');
                            return [
                                'success' => true,
                                'data' => null,
                            ];
                        }
                    }
                }
            }

            $newCaptain = $newCaptain['data'];
            $result = $this->teamRepo->replaceCaptain($newCaptain->member_id, $team->id);
            if(!$result['success']){
                Log::error('Error when replace new captain');

                return [
                    'success' => false,
                    'message' => "Can't update new captain"
                ];
            }
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

        //check member of team > 0
        $result = $this->teamRepo->countMember($team->id);
        if(!$result['success']){
            Log::error('Error when count member');

            return [
                'success' => false,
                'message' => "Error happened when count member"
            ];
        }

        //remove team when total member is 0
        if($result['data'] === 0){
            $result = $this->teamRepo->delete($team->id);
            if(!$result){
                Log::error('Error when delete team at line 147');

                return [
                    'success' => false,
                    'message' => "Can't delete team"
                ];
            }
            Log::info(__CLASS__.' -> '.__FUNCTION__.' -> '.__LINE__.': delete Team');
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
        if(is_array($admin)){
            foreach ($admins as $key => $value) {
                $this->adTeamRepo->create([
                    "team_id" => $teamId,
                    "admin_id" => $value
                ]);
            }
            return true;
        }else{
            return false;
        }
    }
    
}
