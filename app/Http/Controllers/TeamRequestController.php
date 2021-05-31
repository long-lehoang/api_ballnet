<?php

namespace App\Http\Controllers;

use App\Repository\TeamRepo;
use App\Repository\MemberTeamRepo;
use App\Http\Requests\TeamRequest\JoinRequest;
use App\Http\Requests\TeamRequest\InviteRequest;
use App\Models\Team;
use Auth;
use Log;

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
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

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
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

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
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

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
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

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
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

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
        Log::info("[".Auth::id()."]"." ".__CLASS__."::".__FUNCTION__." [ENTRY]");

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
