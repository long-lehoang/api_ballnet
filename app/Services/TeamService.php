<?php

namespace App\Services;

use App\Contracts\Team;
use Illuminate\Support\Facades\Auth;
use App\Repository\TeamRepo;

class TeamService implements Team{
    
    protected $teamRepo;    
    /**
     * __construct
     *
     * @param  mixed $teamRepo
     * @return void
     */
    function __construct(TeamRepo $teamRepo)
    {
        $this->teamRepo = $teamRepo;
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
                return $team->team;
            }
        });

        return $team;
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
                return [
                    'success' => false,
                    'message' => 'Error when find alternative admin'
                ];
            }
            
            if($newCaptain['data'] === null || ($newCaptain['data'] !== null && $newCaptain['data']->member_id === $user->id)){
                $newCaptain = $this->teamRepo->topMember($teamId);
                if(!$newCaptain['success']){
                    return [
                        'success' => false,
                        'message' => 'Error when find alternative member'
                    ];
                }else{
                    if($newCaptain['data'] === null || ($newCaptain['data'] !== null && $newCaptain['data']->member_id === $user->id)){
                        $result = $this->teamRepo->delete($team->id);
                        if(!$result){
                            return [
                                'success' => false,
                                'message' => "Can't delete team"
                            ];
                        }else{
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
                return [
                    'success' => false,
                    'message' => "Can't update new captain"
                ];
            }
        }

        //remove member
        $result = $this->teamRepo->leave($user->id, $team->id);
        if(!$result['success']){
            return [
                'success' => false,
                'message' => "Can't remove member from this team"
            ];
        }

        //check member of team > 0
        $result = $this->teamRepo->countMember($team->id);
        if(!$result['success']){
            return [
                'success' => false,
                'message' => "Error happened when count member"
            ];
        }

        //remove team when total member is 0
        if($result['data'] === 0){
            $result = $this->teamRepo->delete($team->id);
            if(!$result){
                return [
                    'success' => false,
                    'message' => "Can't delete team"
                ];
            }
        }
        //case success
        return [
            'success' => true,
            'data' => null
        ];
    }
}
