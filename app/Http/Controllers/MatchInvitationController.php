<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\TeamRepo;
use App\Repository\MatchInivitationRepo;
use App\Repository\MatchRepo;
use App\Http\Requests\MatchInvitation\CreateRequest;
use App\Contracts\Match;

class MatchInvitationController extends Controller
{
    protected $teamRepo;
    protected $matchInviteRepo;
    protected $matchService;

    function __construct(TeamRepo $teamRepo,MatchRepo $matchRepo, MatchInivitationRepo $matchInviteRepo, Match $matchService)
    {
        $this->teamRepo = $teamRepo;
        $this->matchInviteRepo = $matchInviteRepo;
        $this->matchService = $matchService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($teamId)
    {
        //
    }

    public function request(CreateRequest $request, $teamId)
    {
        //authorize admin
        $team = $this->teamRepo->find($teamId);
        $this->authorize('admin', $team);
        //authorize match
        $match = $this->matchRepo->find($request->match_id);
        $this->authorize('teamRequest', $match);
        //create request
        $this->matchInviteRepo->create(
            [
                "match_id" => $request->match_id,
                "team_id" => $teamId,
            ],
            [
                "status" => "requested",
            ]
        );

        return $this->sendResponse();
    }

    public function accept($teamId, $id)
    {
        //authorize
        $invitation = $this->matchInviteRepo->find($id);
        $this->authorize('acceptTeam', $invitation);

        $this->matchService->acceptTeam($id);
        return $this->sendResponse();
    }

    public function cancel($teamId, $id)
    {
        //authorize
        $invitation = $this->matchInviteRepo->find($id);
        $this->authorize('acceptTeam', $invitation);

        $this->matchService->cancelTeam($id);
        return $this->sendResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}
