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
        //TODO
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function invite(InviteRequest $request)
    {
        //TODO
    }

    
    /**
     * cancel
     *
     * @param  mixed $id
     * @return void
     */
    public function cancel($id)
    {
        //TODO
    }
    
    /**
     * approve
     *
     * @param  mixed $id
     * @return void
     */
    public function approve($id)
    {
        //TODO
    }
}
