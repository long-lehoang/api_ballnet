<?php

namespace App\Http\Controllers;

use App\Repository\TeamRepo;
use App\Repository\MemberTeamRepo;
use App\Http\Requests\TeamRequest\JoinRequest;
use App\Http\Requests\TeamRequest\InviteRequest;
use App\Models\Team;

class TeamRequestController extends Controller
{
    protected $teamRepo;
    protected $memberTeamRepo;
    
    /**
     * __construct
     *
     * @param  mixed $teamRepo
     * @return void
     */
    function __construct(TeamRepo $teamRepo, MemberTeamRepo $memberTeamRepo)
    {
        $this->teamRepo = $teamRepo;
        $this->memberTeamRepo = $memberTeamRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function requestJoinTeam($teamId)
    {
        $team = $this->teamRepo->find($teamId);
        $this->authorize('member', $team);
        
        $result = $this->teamRepo->requestJoinTeam($teamId);
        if($result['success']){
            return $this->sendResponse($result['data']);
        }else{
            return $this->sendError();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myInvitation()
    {
        $result = $this->teamRepo->myInvitation();
        if($result['success']){
            return $this->sendResponse($result['data']);
        }else{
            return $this->sendError();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function join(JoinRequest $request)
    {
        $teamId = $request->input('team_id');

        $result = $this->memberTeamRepo->join($teamId);
        if($result['success']){
            return $this->sendResponse($result['data']);
        }else{
            return $this->sendError();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function invite(InviteRequest $request)
    {
        $userId = $request->input('user_id');
        $teamId = $request->input('team_id');
        $team = $this->teamRepo->find($teamId);

        $this->authorize('invite', $team);

        $result = $this->memberTeamRepo->invite($userId, $teamId);

        if($result['success']){
            return $this->sendResponse($result['data']);
        }else{
            return $this->sendError();
        }
    }

    
    /**
     * cancel
     *
     * @param  mixed $id
     * @return void
     */
    public function cancel($id)
    {
        $request = $this->memberTeamRepo->find($id);
        $this->authorize('cancel', $request);

        $request->delete();
        return $this->sendResponse();
    }
    
    /**
     * approve
     *
     * @param  mixed $id
     * @return void
     */
    public function approve($id)
    {
        $request = $this->memberTeamRepo->find($id);
        $this->authorize('approve', $request);

        $result = $this->memberTeamRepo->approve($id);
        if($result['success']){
            return $this->sendResponse();
        }else{
            return $this->sendError();
        }
    }
}
