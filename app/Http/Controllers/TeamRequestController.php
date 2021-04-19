<?php

namespace App\Http\Controllers;

use App\Repository\TeamRepo;
use App\Http\Requests\TeamRequest\JoinRequest;
use App\Http\Requests\TeamRequest\InviteRequest;

class TeamRequestController extends Controller
{
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function requestJoinTeam($teamId)
    {
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

        $result = $this->teamRepo->join($teamId);
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

        $result = $this->teamRepo->invite($userId, $teamId);

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
        $result = $this->teamRepo->delete($id);
        if($result){
            return $this->sendResponse();
        }else{
            return $this->sendError();
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
        $result = $this->teamRepo->approve($id);
        if($result['success']){
            return $this->sendResponse();
        }else{
            return $this->sendError();
        }
    }
}
